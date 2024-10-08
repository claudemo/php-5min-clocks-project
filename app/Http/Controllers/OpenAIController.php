<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OpenAIController extends Controller
{
    public function handle(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'task' => 'required|string|max:255',
        
        ]);

        $task = $validatedData['task'];

        // Construct the prompt
        $prompt = "You are someone helping the person check back on his mental health, but you have to do it in a way that is humorous and able to complete in five minutes. Think about way to guide medication or eye movement. Remember to make it intriguing, talk within 250 words";

        $apiKey = config('services.openai.api_key');

        $client = new Client();

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 3400,
                    'temperature' => 0.7,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $reply = $data['choices'][0]['message']['content'] ?? 'No response';

            return response()->json(['reply' => $reply]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('OpenAI API Request Error', [
                'message' => $e->getMessage(),
                'request' => $e->getRequest(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);

            return response()->json(['error' => 'Failed to communicate with OpenAI API'], 500);
        }
    }
}