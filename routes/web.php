<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallController;

use App\Http\Controllers\ChatController;

Route::get('/call', [CallController::class, 'showForm']);
Route::post('/call', [CallController::class, 'makeCall']);

// 🔥 AI Voice Routes
Route::match(['GET', 'POST'], '/voice', [CallController::class, 'handleCall']);
Route::post('/process-speech', [CallController::class, 'processSpeech']);




// chat routes  



Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat/send', [ChatController::class, 'sendMessage']);

































// Created a Laravel call form where a user can enter a phone number and start an outbound AI call.
// Integrated the Twilio API in CallController to place calls programmatically from the configured Twilio number.
// Added the /voice webhook route to handle incoming Twilio voice requests during the call flow.
// Implemented speech input capture using Twilio Gather with Hindi language support for real-time voice interaction.
// Connected the call flow with the Gemini API to process the user’s spoken query and generate AI-based responses.
// Built a continuous conversation loop where the assistant listens, replies, and prompts the user for the next question.
// Added response cleanup, error handling, and configurable webhook/voice settings to make the call experience stable and production-ready






// DSR-09-04-26

// project - Voice Assistant
 
 
    

// Status: Completed
// Hours Spent: 8





// gemini key- AIzaSyBF3Rb9dXp0yg3REUNWrYsBpWA2mE9upSA