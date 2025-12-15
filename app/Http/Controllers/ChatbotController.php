<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Products;
use Illuminate\Http\Client\ConnectionException; 

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $apiKey = env('GOOGLE_API_KEY');

            if (!$apiKey) {
                return response()->json(['reply' => 'Lỗi: Chưa cấu hình API Key.']);
            }

            // --- TỐI ƯU 1: GIẢM DỮ LIỆU GỬI ĐI ---
            $products = collect([]); 
            $systemContext = "";

            if (preg_match('/(dưới|thấp hơn)\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
                $price = intval($matches[2]) * 1000000;
                $products = Products::where('gia', '<=', $price)
                            ->select('id', 'tensp', 'gia', 'hinh_anh')
                            ->orderBy('gia', 'desc')->take(2)->get();
            } 
            else if (preg_match('/(trên|hơn|từ)\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
                $price = intval($matches[2]) * 1000000;
                $products = Products::where('gia', '>=', $price)
                            ->select('id', 'tensp', 'gia', 'hinh_anh')
                            ->orderBy('gia', 'asc')->take(2)->get();
            }
            else if (preg_match('/(tìm|mua|xem|giá|đồng hồ)\s+(.+)/iu', $userMessage, $matches)) {
                $keyword = trim(preg_replace('/(của|hãng|hiệu|màu|cái|chiếc|nào|rẻ|mắc|tốt|đẹp)/iu', '', $matches[2]));
                if (!empty($keyword)) {
                    $products = Products::where('tensp', 'like', "%$keyword%")
                                ->select('id', 'tensp', 'gia', 'hinh_anh')->take(2)->get();
                }
            }

            if ($products->count() > 0) {
                $list = $products->map(fn($p) => "- " . $p->tensp . " (" . number_format($p->gia) . "đ)")->implode("\n");
                $systemContext = "\n[Hệ thống]: Tìm thấy SP:\n" . $list . "\nGiới thiệu ngắn gọn.";
            }

            // --- TỐI ƯU 2: GIẢM LỊCH SỬ CHAT ---
            $history = Session::get('chat_history', []);
            if (count($history) > 2) {
                $history = array_slice($history, -2);
            }

            $historyToSend = array_merge($history, [
                ['role' => 'user', 'parts' => [['text' => $userMessage . $systemContext]]]
            ]);

            // --- [QUAN TRỌNG] CẬP NHẬT TÊN MODEL ĐÚNG TRONG LIST CỦA BẠN ---
            // Dùng gemini-2.0-flash-lite để tránh 404 và 429
            $model = 'gemini-2.5-flash';
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            try {
                $response = Http::withOptions(['verify' => false, 'timeout' => 30])
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, ['contents' => $historyToSend]);

            } catch (ConnectionException $e) {
                return response()->json(['reply' => 'Lỗi kết nối mạng (cURL 6). Kiểm tra Wifi server.']);
            }

            if ($response->failed()) {
                Log::error('Gemini Error: ' . $response->body());

                if ($response->status() == 429) {
                    return response()->json(['reply' => 'Hệ thống đang quá tải. Vui lòng đợi 1 phút rồi thử lại.']);
                }
                if ($response->status() == 404) {
                    // Nếu vẫn lỗi này nghĩa là tên model sai hoặc chưa được cấp quyền
                    return response()->json(['reply' => "Lỗi: Model '$model' không tồn tại hoặc bị chặn."]);
                }
                
                return response()->json(['reply' => 'Lỗi hệ thống AI (Mã: ' . $response->status() . ').']);
            }

            $data = $response->json();
            $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi chưa hiểu ý bạn.';

            // Lưu lịch sử
            $history[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];
            $history[] = ['role' => 'model', 'parts' => [['text' => $botReply]]];
            if (count($history) > 4) $history = array_slice($history, -4);
            Session::put('chat_history', $history);

            return response()->json([
                'reply' => $botReply,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Lỗi nội bộ: ' . $e->getMessage()]);
        }
    }
}