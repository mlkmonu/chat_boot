<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class CallController extends Controller
{
    public function showForm()
    {
        return view('call_form'); 
    }

    public function makeCall(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        try {
            $call = $client->calls->create(
                $request->phone,
                env('TWILIO_NUMBER'),
                [
                    'url' => $this->webhookUrl('/voice'),
                ]
            );

            return back()->with('success', 'Call initiated! SID: '.$call->sid);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    
    public function handleCall(Request $request)
    {
        $voice = env('TWILIO_TTS_VOICE', 'Google.hi-IN-Wavenet-E');

        Log::info('Twilio voice webhook hit', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);

        $response = new VoiceResponse();
        $gather = $response->gather([
            'input' => 'speech',
            'language' => 'hi-IN',
            'action' => $this->webhookUrl('/process-speech'),
            'method' => 'POST',
            'timeout' => 10,
            'speechTimeout' => 'auto',
        ]);

        $gather->say('Namaste! Aap kya poochna chahte hain?', [
            'language' => 'hi-IN',
            'voice' => $voice,
        ]);

        $response->redirect($this->webhookUrl('/voice'), [
            'method' => 'POST',
        ]);

        return response((string) $response, 200)
            ->header('Content-Type', 'text/xml');
    }

    // 🤖 AI Response (Gemini)
    public function processSpeech(Request $request)
    {
        $voice = env('TWILIO_TTS_VOICE', 'Google.hi-IN-Wavenet-E');
        $userSpeech = $request->input('SpeechResult', '');
        $models = array_unique(array_filter([
            env('GEMINI_MODEL', 'gemini-2.5-flash'),
            env('GEMINI_FALLBACK_MODEL', 'gemini-2.5-flash-lite'),
        ]));

        Log::info('Twilio speech received', [
            'speech' => $userSpeech,
            'call_sid' => $request->input('CallSid'),
        ]);

        if (empty($userSpeech)) {
            $userSpeech = "Maaf kijiye, mujhe samajh nahi aaya";
        }

        try {
            // ✅ Gemini API call (correct endpoint)
            $aiText = "Maaf kijiye, abhi jawab nahi mil paya";

            foreach ($models as $model) {
                $aiResponse = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => env('GOOGLE_API_KEY'),
                ])->post(
                    'https://generativelanguage.googleapis.com/v1/models/' . $model . ':generateContent',
                    [
                        "contents" => [
                            [
                                "parts" => [
                                    ["text" => "User: ".$userSpeech]
                                ]
                            ]
                        ]
                    ]
                );

                $aiData = $aiResponse->json();
                $candidateReply = $aiData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                $errorMessage = $aiData['error']['message'] ?? null;

                if ($aiResponse->successful() && $candidateReply) {
                    $aiText = $this->normalizeForVoice($candidateReply);
                    break;
                }

                $aiText = $errorMessage ?? "Maaf kijiye, abhi jawab nahi mil paya";

                if (!$errorMessage || !str_contains(strtolower($errorMessage), 'high demand')) {
                    break;
                }
            }

        } catch (\Exception $e) {
            Log::error('Voice AI processing failed', [
                'message' => $e->getMessage(),
                'speech' => $userSpeech,
            ]);
            $aiText = "Technical issue hai, dubara try karein";
        }

        // ✅ Twilio response
        $response = new VoiceResponse();

        $response->say($aiText, [
            'language' => 'hi-IN',
            'voice' => $voice,
        ]);

        $gather = $response->gather([
            'input' => 'speech',
            'language' => 'hi-IN',
            'action' => $this->webhookUrl('/process-speech'),
            'method' => 'POST',
            'timeout' => 10,
            'speechTimeout' => 'auto',
        ]);

        $gather->say('Agar aap aur kuch poochna chahte hain to boliye.', [
            'language' => 'hi-IN',
            'voice' => $voice,
        ]);

        $response->redirect($this->webhookUrl('/voice'), [
            'method' => 'POST',
        ]);

        return response((string) $response, 200)
            ->header('Content-Type', 'text/xml');
    }

    private function normalizeForVoice(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/[\r\n\t]+/', ' ', $text);
        $text = preg_replace('/\s{2,}/', ' ', $text);
        $text = preg_replace('/[*_#`]+/', '', $text);
        $text = trim($text);

        if (mb_strlen($text) > 450) {
            $text = mb_substr($text, 0, 450) . '...';
        }

        return $text !== '' ? $text : 'Maaf kijiye, abhi jawab nahi mil paya';
    }

    private function webhookUrl(string $path): string
    {
        $baseUrl = rtrim(env('TWILIO_WEBHOOK_BASE_URL', config('app.url')), '/');

        return $baseUrl . '/' . ltrim($path, '/');
    }
}
