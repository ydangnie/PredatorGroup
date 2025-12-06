<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use App\Services\VnpayService;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

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
      if (empty($cart)) {
         return back()->with('error', 'Giỏ hàng trống!');
      }

      $total = 0;
      foreach ($cart as $item) {
         $total += $item['price'] * $item['quantity'];
      }

      // Xử lý mã giảm giá nếu có (đoạn code cũ của bạn)
      if (session()->has('coupon')) {
         $discount = session('coupon')['discount'];
         $total = max(0, $total - $discount);
      }

      DB::beginTransaction();
      try {
         // --- 1. KIỂM TRA VÀ TRỪ TỒN KHO ---
         foreach ($cart as $id => $item) {
            $product = Products::lockForUpdate()->find($id); // Khóa dòng để tránh xung đột

            if (!$product) {
               throw new \Exception("Sản phẩm \"{$item['name']}\" không tồn tại.");
            }

            if ($product->so_luong < $item['quantity']) {
               throw new \Exception("Sản phẩm \"{$item['name']}\" chỉ còn {$product->so_luong} cái, không đủ số lượng bạn đặt.");
            }

            // Trừ tồn kho
            $product->decrement('so_luong', $item['quantity']);
         }

         // --- 2. TẠO ĐƠN HÀNG ---
         $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'note' => $request->note,
            'payment_method' => $request->payment_method,
            'total_price' => $total,
            'status' => 'pending'
         ]);

         foreach ($cart as $id => $item) {
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

         // --- 3. XỬ LÝ THANH TOÁN ---
         if ($request->payment_method == 'banking') {
            $vnpUrl = $vnpayService->createPaymentUrl($order->id, $total);
            return redirect($vnpUrl);
         }

         // COD
         session()->forget('cart');
         session()->forget('coupon');
         return redirect()->route('profile.order.show', $order->id)->with('success', 'Đặt hàng thành công!');
      } catch (\Exception $e) {
         DB::rollBack();
         return back()->with('error', 'Lỗi: ' . $e->getMessage());
      }
   }

   // 8. Xử lý kết quả trả về từ VNPAY
   public function vnpayReturn(Request $request)
   {
      // ... code cũ lấy $vnp_ResponseCode, $orderId ...
      $vnp_ResponseCode = $request->input('vnp_ResponseCode');
      $orderId = $request->input('vnp_TxnRef');
      $order = Order::find($orderId);

      if ($order) {
         if ($vnp_ResponseCode == '00') {
            // ... Thành công (giữ nguyên logic cũ) ...
            $order->update(['status' => 'processing']);
            session()->forget('cart');
            session()->forget('coupon');
            return redirect()->route('profile.order.show', $orderId)->with('success', 'Thanh toán thành công!');
         } else {
            // ... Thất bại -> HOÀN LẠI KHO ...
            if ($order->status != 'cancelled') {
               $order->update(['status' => 'cancelled']);

               // Cộng lại số lượng hàng vào kho
               foreach ($order->items as $item) {
                  $product = Products::find($item->product_id);
                  if ($product) {
                     $product->increment('so_luong', $item->quantity);
                  }
               }
            }

            return redirect()->route('giohang')->with('error', 'Thanh toán thất bại. Đơn hàng đã bị hủy.');
         }
      }


      return redirect()->route('giohang')->with('error', 'Không tìm thấy đơn hàng.');
   }
   public function applyCoupon(Request $request)
   {
      $code = $request->input('code');
      // Tìm voucher theo mã
      $voucher = Voucher::where('code', $code)->first();

      // --- KIỂM TRA ĐIỀU KIỆN ---
      if (!$voucher) {
         return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại!']);
      }
      if ($voucher->quantity <= 0) {
         return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng!']);
      }
      if (!$voucher->status) {
         return response()->json(['success' => false, 'message' => 'Mã giảm giá hiện đang bị khóa!']);
      }

      $now = Carbon::now();
      if ($voucher->start_date && $now->lt($voucher->start_date)) {
         return response()->json(['success' => false, 'message' => 'Mã giảm giá chưa đến thời gian áp dụng!']);
      }
      if ($voucher->end_date && $now->gt($voucher->end_date)) {
         return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn!']);
      }

      // --- TÍNH TOÁN GIÁ TRỊ ---
      $cart = session()->get('cart', []);
      $total = 0;
      foreach ($cart as $item) {
         $total += $item['price'] * $item['quantity'];
      }

      // (Thêm đoạn này để trừ tiền giảm giá)
      if (session()->has('coupon')) {
         $discount = session('coupon')['discount'];
         $total = $total - $discount;
      }

      $discount = 0;
      if ($voucher->type == 'fixed') {
         $discount = $voucher->value;
      } elseif ($voucher->type == 'percent') {
         $discount = $total * ($voucher->value / 100);
      }

      // Đảm bảo không giảm quá số tiền tổng
      if ($discount > $total) {
         $discount = $total;
      }

      // Lưu vào session để dùng cho bước Thanh toán sau này
      session()->put('coupon', [
         'code' => $voucher->code,
         'discount' => $discount,
         'type' => $voucher->type,
         'value' => $voucher->value
      ]);

      $newTotal = $total - $discount;

      return response()->json([
         'success' => true,
         'message' => 'Áp dụng mã thành công!',
         'discount_string' => number_format($discount, 0, ',', '.') . '₫',
         'total_string' => number_format($newTotal, 0, ',', '.') . '₫'
      ]);
   }
}
