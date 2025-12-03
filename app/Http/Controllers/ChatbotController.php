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

        if (!$apiKey) return response()->json(['reply' => 'Lỗi: Chưa cấu hình API Key.'], 500);

        // 1. Tìm sản phẩm trong Database 
        $products = [];
        $systemContext = "";

       
        if (preg_match('/(dưới|thấp hơn)\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
            $price = intval($matches[2]) * 1000000;
            $products = Products::where('gia', '<=', $price)
                                ->select('id', 'tensp', 'gia', 'hinh_anh')
                                ->orderBy('gia', 'desc') // Ưu tiên giá cao nhất trong khoảng
                                ->take(4)->get();
        } 
        // --- CASE 2: Tìm giá "TRÊN" 
        else if (preg_match('/(trên|hơn|từ)\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
            $price = intval($matches[2]) * 1000000;
            $products = Products::where('gia', '>=', $price)
                                ->select('id', 'tensp', 'gia', 'hinh_anh')
                                ->orderBy('gia', 'asc') // Ưu tiên giá thấp nhất trong khoảng
                                ->take(4)->get();
        }
        // --- CASE 3: Tìm theo TÊN/TỪ KHÓA 
        else if (preg_match('/(tìm|mua|xem|giá|đồng hồ)\s+(.+)/iu', $userMessage, $matches)) {
            $keyword = $matches[2];
            // Loại bỏ từ nhiễu để tìm chính xác hơn
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
            $systemContext = "\n[Hệ thống]: Tìm thấy sản phẩm khớp yêu cầu:\n" . $list . "\nHãy giới thiệu ngắn gọn.";
        } else {
            // Nếu tìm theo giá mà không có
            if (strpos($userMessage, 'triệu') !== false || strpos($userMessage, 'tr') !== false) {
                $systemContext = "\n[Hệ thống]: Không tìm thấy sản phẩm nào trong khoảng giá này. Hãy gợi ý khách xem mức giá khác.";
            }
        }

        // 2. Gửi sang Gemini AI
        try {
            $history = Session::get('chat_history', []);
            $historyToSend = array_merge($history, [
                ['role' => 'user', 'parts' => [['text' => $userMessage . $systemContext]]]
            ]);

            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => $historyToSend
                ]);

            $botReply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi chưa hiểu ý bạn.';

            // Lưu lịch sử
            $history[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];
            $history[] = ['role' => 'model', 'parts' => [['text' => $botReply]]];
            if (count($history) > 10) $history = array_slice($history, -10);
            Session::put('chat_history', $history);

            return response()->json([
                'reply' => $botReply,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }
}