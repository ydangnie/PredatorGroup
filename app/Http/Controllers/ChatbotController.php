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

            // 2. Tìm sản phẩm (Giữ nguyên logic của bạn)
            // Khởi tạo Collection rỗng để tránh lỗi count()
            $products = collect([]); 
            $systemContext = "";

            if (preg_match('/(dưới|thấp hơn)\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
                $price = intval($matches[2]) * 1000000;
                $products = Products::where('gia', '<=', $price)->select('id', 'tensp', 'gia', 'hinh_anh')->orderBy('gia', 'desc')->take(4)->get();
            } 
            else if (preg_match('/(trên|hơn|từ)\s*(\d+)\s*(tr|triệu|m)/iu', $userMessage, $matches)) {
                $price = intval($matches[2]) * 1000000;
                $products = Products::where('gia', '>=', $price)->select('id', 'tensp', 'gia', 'hinh_anh')->orderBy('gia', 'asc')->take(4)->get();
            }
            else if (preg_match('/(tìm|mua|xem|giá|đồng hồ)\s+(.+)/iu', $userMessage, $matches)) {
                $keyword = trim(preg_replace('/(của|hãng|hiệu|màu|cái|chiếc|nào|rẻ|mắc|tốt|đẹp)/iu', '', $matches[2]));
                if (!empty($keyword)) {
                    $products = Products::where('tensp', 'like', "%$keyword%")->select('id', 'tensp', 'gia', 'hinh_anh')->take(4)->get();
                }
            }

            // Tạo ngữ cảnh
            if ($products->count() > 0) {
                $list = $products->map(fn($p) => "- " . $p->tensp . " (" . number_format($p->gia) . "đ)")->implode("\n");
                $systemContext = "\n[Hệ thống]: Tìm thấy sản phẩm:\n" . $list . "\nHãy giới thiệu ngắn gọn.";
            }

            // 3. Chuẩn bị dữ liệu (QUAN TRỌNG: Xử lý lịch sử)
            $history = Session::get('chat_history', []);
            
            // Giới hạn lịch sử để tránh lỗi quá tải token
            if (count($history) > 6) {
                $history = array_slice($history, -6);
            }

            // Ghép tin nhắn mới
            $historyToSend = array_merge($history, [
                ['role' => 'user', 'parts' => [['text' => $userMessage . $systemContext]]]
            ]);

            // --- CẤU HÌNH MODEL CHUẨN THEO DANH SÁCH CỦA BẠN ---
            // Bạn có quyền dùng 'gemini-2.0-flash'
            $model = 'gemini-2.0-flash';
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            // 4. Gọi API
            $response = Http::withOptions(['verify' => false]) // Bỏ qua SSL local
                ->timeout(30) // Tăng thời gian chờ lên 30s
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => $historyToSend
                ]);

            // 5. Xử lý lỗi kết nối
            if ($response->failed()) {
                // Log lỗi chi tiết để debug
                Log::error('Gemini API Error (Code ' . $response->status() . '): ' . $response->body());

                // Nếu lỗi 400 (Bad Request), có thể do lịch sử chat bị lỗi => Xóa lịch sử
                if ($response->status() == 400) {
                    Session::forget('chat_history');
                    return response()->json(['reply' => 'Hệ thống vừa reset phiên chat để sửa lỗi. Bạn vui lòng hỏi lại nhé.']);
                }

                return response()->json(['reply' => 'Hệ thống AI đang bận (Mã lỗi: ' . $response->status() . '). Vui lòng thử lại sau.']);
            }

            $data = $response->json();

            // Kiểm tra cấu trúc trả về
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                Log::error('Gemini Response Invalid: ' . json_encode($data));
                return response()->json(['reply' => 'Xin lỗi, tôi không hiểu ý bạn.']);
            }

            $botReply = $data['candidates'][0]['content']['parts'][0]['text'];

            // 6. Lưu lịch sử (Chỉ lưu nếu thành công)
            $history[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];
            $history[] = ['role' => 'model', 'parts' => [['text' => $botReply]]];
            Session::put('chat_history', $history);

            return response()->json([
                'reply' => $botReply,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Controller Exception: ' . $e->getMessage());
            return response()->json(['reply' => 'Có lỗi hệ thống: ' . $e->getMessage()]);
        }
    }
}