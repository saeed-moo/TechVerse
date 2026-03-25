<?php

namespace App\Http\Controllers;

use App\Services\ChatBotService;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    protected $chatBot;

    public function __construct(ChatBotService $chatBot)
    {
        $this->chatBot = $chatBot;
    }

    /**
     * Send message to chatbot
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Get conversation history from session
        $history = session('chatbot_history', []);

        // Get AI response
        $response = $this->chatBot->sendMessage($request->message, $history);

        // Add to history
        $history[] = ['role' => 'user', 'content' => $request->message];
        $history[] = ['role' => 'assistant', 'content' => $response];

        // Keep only last 10 messages (5 exchanges)
        if (count($history) > 10) {
            $history = array_slice($history, -10);
        }

        // Save to session
        session(['chatbot_history' => $history]);

        return response()->json([
            'success' => true,
            'message' => $response,
        ]);
    }

    /**
     * Clear chat history
     */
    public function clearHistory()
    {
        session()->forget('chatbot_history');

        return response()->json([
            'success' => true,
            'message' => 'Chat history cleared',
        ]);
    }
}
