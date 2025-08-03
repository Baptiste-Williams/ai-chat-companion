<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ChatController;

// Show the chat interface
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

// Handle message submission
Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');

// Reset the conversation
Route::post('/chat/reset', function () {
    session()->forget(['messages', 'persona']);
    return redirect()->route('chat.index');
})->name('chat.reset');

// Switch persona without sending a message
Route::post('/chat/persona', function (Request $request) {
    $persona = $request->input('persona', 'coach');
    session()->put('persona', $persona);
    return redirect()->route('chat.index');
})->name('chat.persona');
