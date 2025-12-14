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
        try {
            $userMessage = $request->input('message');
            $apiKey = env('GOOGLE_API_KEY');

            // 1. Kiểm tra API Key
            if (!$apiKey) {
                return response()->json(['reply' => 'Lỗi: Chưa cấu hình API Key trong file .env']);
            }

            // 2. Xử lý logic tìm sản phẩm (Giữ nguyên logic của bạn)
            $products = [];
            $systemContext = "";

            // Logic tìm kiếm sản phẩm...
            if (preg_match('/(dưới|thấp hơn)\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
                $price = intval($matches[2]) * 1000000;
                $products = Products::where('gia', '<=', $price)
                                    ->select('id', 'tensp', 'gia', 'hinh_anh')
                                    ->orderBy('gia', 'desc')
                                    ->take(4)->get();
            } 
            else if (preg_match('/(trên|hơn|từ)\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
                $price = intval($matches[2]) * 1000000;
                $products = Products::where('gia', '>=', $price)
                                    ->select('id', 'tensp', 'gia', 'hinh_anh')
                                    ->orderBy('gia', 'asc')
                                    ->take(4)->get();
            }
            else if (preg_match('/(tìm|mua|xem|giá|đồng hồ)\s+(.+)/iu', $userMessage, $matches)) {
                $keyword = $matches[2];
                $keyword = preg_replace('/(của|hãng|hiệu|màu|cái|chiếc|nào|rẻ|mắc|tốt|đẹp)/iu', '', $keyword);
                $keyword = trim($keyword);
                
                if (!empty($keyword)) {
                    $products = Products::where('tensp', 'like', "%$keyword%")
                                        ->select('id', 'tensp', 'gia', 'hinh_anh')
                                        ->take(4)->get();
                }
            }

            // Tạo ngữ cảnh cho AI
            if ($products->count() > 0) {
                $list = $products->map(fn($p) => "- " . $p->tensp . " (" . number_format($p->gia) . "đ)")->implode("\n");
                $systemContext = "\n[Hệ thống]: Tìm thấy sản phẩm:\n" . $list . "\nHãy giới thiệu ngắn gọn.";
            }

            // 3. Chuẩn bị dữ liệu gửi sang Google
            $history = Session::get('chat_history', []);
            $historyToSend = array_merge($history, [
                ['role' => 'user', 'parts' => [['text' => $userMessage . $systemContext]]]
            ]);

            // --- CẤU HÌNH QUAN TRỌNG ĐỂ FIX LỖI 500 ---
            // Dùng gemini-1.5-flash cho ổn định (bản 2.5-flash đôi khi chưa active với key thường)
            $model = 'gemini-1.5-flash';
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            // Gọi API với verify => false để tránh lỗi SSL trên Localhost
            $response = Http::withOptions(['verify' => false]) 
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => $historyToSend
                ]);

            $data = $response->json();

            // 4. KIỂM TRA LỖI TỪ GOOGLE TRẢ VỀ (Quan trọng nhất)
            if (isset($data['error'])) {
                Log::error('Gemini API Error: ' . json_encode($data['error']));
                return response()->json([
                    'reply' => 'Hệ thống AI đang bảo trì (Lỗi API). Bạn vui lòng thử lại sau.'
                ]);
            }

            // Kiểm tra xem có câu trả lời không trước khi lấy
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return response()->json([
                    'reply' => 'Xin lỗi, tôi không hiểu ý bạn. Vui lòng hỏi lại.'
                ]);
            }

            $botReply = $data['candidates'][0]['content']['parts'][0]['text'];

            // 5. Lưu lịch sử
            $history[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];
            $history[] = ['role' => 'model', 'parts' => [['text' => $botReply]]];
            if (count($history) > 10) $history = array_slice($history, -10);
            Session::put('chat_history', $history);

            return response()->json([
                'reply' => $botReply,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            // Bắt mọi lỗi crash để không hiện màn hình đỏ (500)
            Log::error('Chatbot Controller Exception: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Có lỗi xảy ra khi xử lý tin nhắn. Vui lòng thử lại.'
            ]);
        }
    }
}