<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Products;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $userMessage = $request->input('message');
        $apiKey = env('GOOGLE_API_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => 'Lỗi: Chưa cấu hình API Key.'], 500);
        }

        // 1. Lấy lịch sử chat
        $history = Session::get('chat_history', []);

        // 2. LOGIC TÌM SẢN PHẨM (RAG)
        $products = [];
        $systemContext = "";

        // Tìm theo giá (ví dụ: dưới 50 triệu)
        if (preg_match('/dưới\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
            $price = intval($matches[1]) * 1000000;
            $products = Products::where('gia', '<=', $price)
                                ->select('id', 'tensp', 'gia', 'hinh_anh') // Chỉ lấy cột cần thiết
                                ->take(3)->get();
        } 
        // Tìm theo tên (ví dụ: tìm đồng hồ Rolex)
        else if (preg_match('/(tìm|mua|xem|giá)\s+(.+)/iu', $userMessage, $matches)) {
            $keyword = $matches[2];
            $products = Products::where('tensp', 'like', "%$keyword%")
                                ->select('id', 'tensp', 'gia', 'hinh_anh')
                                ->take(3)->get();
        }

        // Nếu tìm thấy, tạo ngữ cảnh cho AI
        if ($products->count() > 0) {
            $productListText = $products->map(function($p) {
                return "- " . $p->tensp . " (Giá: " . number_format($p->gia) . " VNĐ)";
            })->implode("\n");
            
            $systemContext = "\n\n[Hệ thống]: Tìm thấy các sản phẩm sau trong database:\n" . $productListText . 
                             "\nHãy giới thiệu ngắn gọn.";
        }

        // 3. Gửi tin nhắn cho Google Gemini
        $payloadContents = $history;
        $currentMessageWithContext = $userMessage . $systemContext;

        $payloadContents[] = [
            'role' => 'user',
            'parts' => [['text' => $currentMessageWithContext]]
        ];

        try {
            $modelName = 'gemini-2.5-flash'; 
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, ['contents' => $payloadContents]);

            if ($response->successful()) {
                $data = $response->json();
                $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi không hiểu.';

                // Lưu lịch sử
                $history[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];
                $history[] = ['role' => 'model', 'parts' => [['text' => $botReply]]];
                if (count($history) > 10) $history = array_slice($history, -10);
                Session::put('chat_history', $history);

                // QUAN TRỌNG: Trả về cả reply và products
                return response()->json([
                    'reply' => $botReply,
                    'products' => $products // <--- Biến này giúp JS hiển thị danh sách
                ]);
            }

            Log::error('API Error: ' . $response->body());
            return response()->json(['reply' => 'Lỗi kết nối AI.'], 500);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }
}