<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat_professional');
    }

    public function sendMessage(Request $request)   
    {
        $message = $request->input('message');
        $models = array_unique(array_filter([
            env('GEMINI_MODEL', 'gemini-2.5-flash'),
            env('GEMINI_FALLBACK_MODEL', 'gemini-2.5-flash-lite'),
        ]));

        if (empty($message)) {
            return response()->json([
                'reply' => 'Please type something...',
            ]);
        }

        try {
            $reply = 'AI se response nahi mila';

            foreach ($models as $model) {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => env('GOOGLE_API_KEY'),
                ])->post(
                    'https://generativelanguage.googleapis.com/v1/models/' . $model . ':generateContent',
                    [
                        'contents' => [
                            [
                                'role' => 'user',
                                'parts' => [
                                    ['text' => $message],
                                ],
                            ],
                        ],
                    ]
                );

                $data = $response->json();
                $candidateReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                $errorMessage = $data['error']['message'] ?? null;

                if ($response->successful() && $candidateReply) {
                    $reply = $candidateReply;
                    break;
                }

                $reply = $errorMessage ?? 'AI se response nahi mila';

                if (!$errorMessage || !str_contains(strtolower($errorMessage), 'high demand')) {
                    break;
                }
            }
        } catch (\Exception $e) {
            $reply = 'Error: ' . $e->getMessage();
        }

        return response()->json([
            'reply' => $reply,
        ]);
    }
}
