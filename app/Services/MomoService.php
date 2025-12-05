<?php

namespace App\Services;

use App\Models\Order;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;

class MomoService
{
    protected Client $http;
    protected string $endpoint;
    protected string $partnerCode;
    protected string $accessKey;
    protected string $secretKey;
    protected bool $isSandbox;
    protected array $allowedIps;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 30]); // ≥30s theo docs

        $this->partnerCode = env('MOMO_PARTNER_CODE');
        $this->accessKey = env('MOMO_ACCESS_KEY');
        $this->secretKey = env('MOMO_SECRET_KEY');
        $this->isSandbox = env('MOMO_SANDBOX', true);

        $this->endpoint = $this->isSandbox
            ? 'https://test-payment.momo.vn/v2/gateway/api/create'
            : 'https://payment.momo.vn/v2/gateway/api/create';

        // IP whitelist từ docs (cho IPN verify)
        $this->allowedIps = $this->isSandbox
            ? ['210.245.113.71'] // Incoming sandbox
            : ['118.69.212.158']; // Incoming production
    }

    /**
     * Tạo link thanh toán MoMo AIO (chuẩn docs v3)
     */
    public function createPayment(array $params): array
    {
        $validator = Validator::make($params, [
            'orderId' => 'required|string|max:64', // Duy nhất ≤64 byte
            'amount' => 'required|integer|min:10000|max:500000000',
            'orderInfo' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()->first()];
        }

        $orderId = $params['orderId'];
        $amount = $params['amount'];
        $orderInfo = $params['orderInfo'];
        $redirectUrl = $params['redirectUrl'] ?? route('payment.momo.return');
        $ipnUrl = $params['ipnUrl'] ?? route('payment.momo.ipn');
        $requestId = $params['requestId'] ?? uniqid($orderId . '_');
        $extraData = $params['extraData'] ?? '';

        // Raw hash chuẩn docs (sắp xếp theo thứ tự params)
        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType=captureWallet";
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);
        $payload = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'amount' => (string) $amount, // String theo docs
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
            'lang' => 'vi', // Hoặc 'en'
            'signature' => $signature,
        ];

        try {
            $response = $this->http->post($this->endpoint, [
                'json' => $payload,
                'headers' => ['Content-Type' => 'application/json; charset=UTF-8'], // Chuẩn docs
            ]);

            $result = json_decode($response->getBody(), true);

            if (($result['resultCode'] ?? -1) == 0) {
                return [
                    'success' => true,
                    'payUrl' => $result['payUrl'] ?? '',
                    'deeplink' => $result['deeplink'] ?? '',
                    'orderId' => $orderId,
                ];
            }

            Log::warning('MoMo Create Failed', ['result' => $result]);
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Lỗi tạo thanh toán',
                'code' => $result['resultCode'] ?? -1,
            ];
        } catch (RequestException $e) {
            Log::error('MoMo Create Error', ['payload' => $payload, 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Lỗi kết nối MoMo: '. $e->getMessage()];
        }
    }

    /**
     * Verify IP từ MoMo (cho IPN/Callback – thêm bảo mật)
     */
    public function verifyIp(Request $request): bool
    {
        $clientIp = $request->ip(); // Hoặc $request->header('X-Forwarded-For') nếu proxy
        return in_array($clientIp, $this->allowedIps);
    }

    /**
     * Verify chữ ký từ MoMo (chuẩn HMAC-SHA256)
     */
    public function verifySignature(array $data): bool
    {
        if (!isset($data['signature'])) return false;

        $signature = $data['signature'];
        unset($data['signature']);

        // Sắp xếp key theo alphabet (docs yêu cầu)
        ksort($data);
        $rawHash = http_build_query($data);
        $expected = hash_hmac('sha256', $rawHash, $this->secretKey);

        return hash_equals($expected, $signature); // An toàn chống timing attack
    }

    /**
     * Xử lý IPN/Callback chung (update DB nếu success)
     */
    public function handleCallback(Request $request): array
    {
        // if (!$this->verifyIp($request)) {
        //     Log::warning('MoMo IPN: Invalid IP', ['ip' => $request->ip()]);
        //     return ['success' => false, 'message' => 'IP không hợp lệ'];
        // }

        $data = $request->all();
        // if (!$this->verifySignature($data)) {
        //     Log::warning('MoMo IPN: Invalid Signature', $data);
        //     return ['success' => false, 'message' => 'Chữ ký không hợp lệ'];
        // }

        if (($data['resultCode'] ?? -1) == 0) {
            $orderId = $data['orderId'] ?? '';
            $transId = $data['transId'] ?? ''; // TID từ MoMo
            $amount = $data['amount'] ?? 0;

            // Update DB order (ví dụ cho shop đồng hồ)
            // Order::where('code', $orderId)->update([
            //     'status' => 'paid',
            //     'momo_tid' => $transId,
            //     'paid_amount' => $amount,
            //     'paid_at' => now(),
            // ]);

            Log::info('MoMo Payment Success', $data);
            return [
                'success' => true,
                'orderId' => $orderId,
                'transId' => $transId,
                'message' => $data['message'] ?? 'Thanh toán thành công',
            ];
        }

        Log::warning('MoMo Payment Failed', $data);
        return [
            'success' => false,
            'message' => $data['message'] ?? 'Thanh toán thất bại',
            'code' => $data['resultCode'],
        ];
    }
    public function createPaymentUrl(Order $order)
    {
        $result = $this->createPayment([
            'orderId' =>'DH_'.$order->id,
            'amount' => (int) $order->total_price,
            'orderInfo' => "Thanh toán đồng hồ cao cấp #{$order->id}",
            'redirectUrl' => route('payment.momo.return'),
            'ipnUrl' => route('payment.momo.ipn'),
        ]);

        if ($result['success']) {
            return $result['payUrl']; // Hoặc hiển thị QR
        }
        session()->flash('error', $result['message']);
    }
}
