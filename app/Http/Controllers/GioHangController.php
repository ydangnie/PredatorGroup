<?php

namespace App\Http\Controllers;

use App\Services\VnpayService;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class GioHangController extends Controller
{
   // 1. Hiển thị trang giỏ hàng
   public function giohang()
   {
      $cart = session()->get('cart', []);
      return view('checkout.giohang', compact('cart'));
   }

   // 2. Thêm vào giỏ (AJAX)
   public function addToCart(Request $request)
   {
      $id = $request->product_id;
      $quantity = $request->quantity;
      $product = Products::find($id);

      if (!$product) {
         return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
      }

      $cart = session()->get('cart', []);

      if (isset($cart[$id])) {
         $cart[$id]['quantity'] += $quantity;
      } else {
         $cart[$id] = [
            "name" => $product->tensp,
            "quantity" => $quantity,
            "price" => $product->gia,
            "image" => $product->hinh_anh
         ];
      }

      session()->put('cart', $cart);
      $totalQty = array_sum(array_column($cart, 'quantity'));

      return response()->json([
         'success' => true,
         'message' => 'Đã thêm vào giỏ hàng!',
         'total_qty' => $totalQty
      ]);
   }

   // 3. Cập nhật giỏ hàng (AJAX)
   public function updateCart(Request $request)
   {
      if ($request->id && $request->quantity) {
         $cart = session()->get('cart');
         $cart[$request->id]["quantity"] = $request->quantity;
         session()->put('cart', $cart);

         $itemTotal = $cart[$request->id]["quantity"] * $cart[$request->id]["price"];
         $grandTotal = 0;
         foreach ($cart as $item) {
            $grandTotal += $item['price'] * $item['quantity'];
         }

         return response()->json([
            'success' => true,
            'item_total' => number_format($itemTotal),
            'grand_total' => number_format($grandTotal)
         ]);
      }
   }

   // 4. Xóa sản phẩm (AJAX)
   public function removeCart(Request $request)
   {
      if ($request->id) {
         $cart = session()->get('cart');
         if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
         }

         $grandTotal = 0;
         foreach ($cart as $item) {
            $grandTotal += $item['price'] * $item['quantity'];
         }
         $totalQty = array_sum(array_column($cart, 'quantity'));

         return response()->json([
            'success' => true,
            'grand_total' => number_format($grandTotal),
            'cart_count' => $totalQty
         ]);
      }
   }

   // 5. Lấy số lượng (API)
   public function getCartCount()
   {
      $cart = session()->get('cart', []);
      return response()->json(['count' => array_sum(array_column($cart, 'quantity'))]);
   }

   // 6. Hiển thị trang Thanh Toán (CẬP NHẬT: Lấy thêm addresses)
   public function checkout()
   {
      $cart = session()->get('cart', []);

      if (count($cart) == 0) {
         return redirect()->route('sanpham')->with('error', 'Giỏ hàng của bạn đang trống.');
      }

      $user = Auth::user();

      // Lấy danh sách địa chỉ để chọn
      $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

      // Lấy thông tin mặc định
      $defaultAddress = $user->addresses()->where('is_default', true)->first();

      $info = [
         'name' => $defaultAddress ? $defaultAddress->name : $user->name,
         'phone' => $defaultAddress ? $defaultAddress->phone : $user->so_dien_thoai,
         'address' => $defaultAddress ? $defaultAddress->address : $user->dia_chi,
         'email' => $user->email
      ];

      return view('checkout.index', compact('cart', 'info', 'addresses'));
   }

   // 7. Xử lý Đặt Hàng
   // 7. Xử lý Đặt Hàng
  // 7. Xử lý Đặt Hàng
    public function processCheckout(Request $request, VnpayService $vnpayService)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,banking'
        ]);

        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) { $total += $item['price'] * $item['quantity']; }

        DB::beginTransaction();
        try {
            // 1. Tạo đơn hàng (Status: Pending - Chờ thanh toán)
            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'note' => $request->note,
                'payment_method' => $request->payment_method,
                'total_price' => $total,
                'status' => 'pending' // Mặc định là chờ xử lý
            ]);

            // 2. Lưu chi tiết đơn hàng
            foreach($cart as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);
            }

            DB::commit();

            // 3. Xử lý theo phương thức thanh toán

            // === TRƯỜNG HỢP 1: THANH TOÁN VNPAY ===
            if ($request->payment_method == 'banking') {
                // Tạo URL thanh toán
                $vnpUrl = $vnpayService->createPaymentUrl($order->id, $total);

                // LƯU Ý QUAN TRỌNG: Chưa xóa giỏ hàng ở đây!
                // Chỉ chuyển hướng sang VNPAY. Giỏ hàng sẽ được xóa ở hàm vnpayReturn khi thành công.

                return redirect()->to($vnpUrl);
            }

            // === TRƯỜNG HỢP 2: THANH TOÁN COD ===
            // Xóa giỏ hàng ngay lập tức vì đơn đã đặt thành công
            session()->forget('cart');

            return redirect()->route('profile.order.show', $order->id)->with('success', 'Đặt hàng thành công! Chúng tôi sẽ sớm liên hệ.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // 8. Xử lý kết quả trả về từ VNPAY
    public function vnpayReturn(Request $request)
    {
        // Lấy dữ liệu trả về
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $orderId = $request->input('vnp_TxnRef');

        $order = Order::find($orderId);

        if ($order) {
            if ($vnp_ResponseCode == '00') {
                // --- THANH TOÁN THÀNH CÔNG ---

                // 1. Cập nhật trạng thái đơn hàng thành Processing (Đã thanh toán/Đang xử lý)
                $order->update(['status' => 'processing']);

                // 2. Bây giờ mới XÓA GIỎ HÀNG
                session()->forget('cart');

                return redirect()->route('profile.order.show', $orderId)->with('success', 'Thanh toán VNPAY thành công!');
            } else {
                // --- THANH TOÁN THẤT BẠI / HỦY BỎ ---

                // 1. Cập nhật trạng thái đơn hàng thành Cancelled (Đã hủy)
                $order->update(['status' => 'cancelled']);

                // 2. KHÔNG XÓA GIỎ HÀNG -> Để người dùng có thể đặt lại hoặc chọn phương thức khác

                return redirect()->route('giohang')->with('error', 'Giao dịch thanh toán thất bại hoặc đã bị hủy.');
            }
        }

        return redirect()->route('giohang')->with('error', 'Không tìm thấy đơn hàng.');
    }
}
