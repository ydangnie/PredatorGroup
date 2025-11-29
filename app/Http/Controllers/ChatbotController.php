<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
   public function chat(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $apiKey = env('GOOGLE_API_KEY');

            if (!$apiKey) {
                return response()->json(['reply' => 'Lỗi: Chưa có API Key.'], 500);
            }

            $modelName = 'gemini-2.5-flash'; 
            
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $userMessage]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi không có câu trả lời.';
                return response()->json(['reply' => $botReply]);
            }

            Log::error('Google API Error: ' . $response->body());
            return response()->json([
                'reply' => 'Lỗi kết nối (' . $response->status() . '): ' . $response->body()
            ], 500);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }
}