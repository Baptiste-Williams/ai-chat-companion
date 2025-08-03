<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    // Show the chat interface with session-based messages
    public function index()
    {
        $messages = session()->get('messages', []);
        $persona = session()->get('persona', 'coach');
        $length = session()->get('length', 'short');
        $tone = session()->get('tone', 'neutral');

        return view('chat', compact('messages', 'persona', 'length', 'tone'));
    }

    // Handle message submission and call OpenAI API
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');
        $persona = $request->input('persona', 'coach');
        $length = $request->has('length') ? 'long' : 'short';
        $tone = $request->input('tone', 'neutral');

        // Save preferences in session
        session()->put('persona', $persona);
        session()->put('length', $length);
        session()->put('tone', $tone);

        // Define persona behavior
        $personaPrompt = match($persona) {
            'coach' => 'You are a motivational coach who gives uplifting advice and productivity tips.',
            'poet' => 'You are a poetic soul who responds with creativity and elegance.',
            'coder' => 'You are a skilled developer who gives clear and helpful programming advice.',
            'tupac' => 'You speak with raw honesty, wisdom from the streets, and poetic depth. Your tone is passionate, reflective, and unapologetically real.',
            'obama' => 'You respond with thoughtful insight, presidential wisdom, and a calm, articulate tone. You reflect on leadership, unity, and progress.',
            default => 'You are a helpful assistant.',
        };

        $systemPrompt = "{$personaPrompt} Respond in a {$tone} tone.";
        $maxTokens = $length === 'long' ? 500 : 150;

        // Call OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => $maxTokens,
        ]);

        $aiReply = $response->json()['choices'][0]['message']['content'] ?? 'No response from AI.';

        $messages = session()->get('messages', []);
        $messages[] = ['sender' => 'user', 'text' => $userMessage];
        $messages[] = ['sender' => 'ai', 'text' => $aiReply];
        session()->put('messages', $messages);

        return redirect()->route('chat.index');
    }
}
