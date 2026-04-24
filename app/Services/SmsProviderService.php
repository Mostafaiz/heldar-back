<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsProviderService
{
    protected string $apiKey;
    protected SiteConfigService $configService;

    public function __construct()
    {
        $this->apiKey = config('services.kavenegar.api_key');
        $this->configService = new SiteConfigService();
    }

    protected function endpoint(): string
    {
        return "https://api.kavenegar.com/v1/{$this->apiKey}/verify/lookup.json";
    }

    protected function call(array $params)
    {
        try {
            $response = Http::timeout(10)->get($this->endpoint(), $params);

            if ($response->failed()) {
                throw new \Exception('SMS provider error: ' . $response->body());
            }

            return $response->json();
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'message' => 'SMS sending failed',
                'debug' => $e->getMessage(),
            ];
        }
    }

    public function getAdminReceptors(): string
    {
        $config = $this->configService->get();

        return collect([
            $config?->owner_phone,
            $config?->admin_phone,
        ])->filter()->implode(',');
    }

    public function sendOtp(string $phone, string $code)
    {
        return $this->call([
            'receptor' => $phone,
            'token' => $code,
            'template' => 'dhbag',
        ]);
    }

    public function sendStatus(string $phone, string $token, string $token2, string $token3)
    {
        $data = app(SiteConfigService::class)->getData();

        if ($data['first_sms_phone'])
            $this->call([
                'receptor' => trim($data['first_sms_phone']),
                'token' => $token,
                'token20' => urlencode($token2),
                'token3' => $token3,
                'template' => 'status',
            ]);

        if ($data['second_sms_phone'])
            $this->call([
                'receptor' => trim($data['second_sms_phone']),
                'token' => $token,
                'token20' => urlencode($token2),
                'token3' => $token3,
                'template' => 'status',
            ]);
    }

    public function sendCustomer(string $phone, string $token, string $token2, string $token3)
    {
        return $this->call([
            'receptor' => $phone,
            'token' => $token, // کد کالا
            'token20' => urlencode($token2), //رنگ
            'token3' => $token3, // تعداد
            'template' => 'customer',
        ]);
    }

    public function orderManager(string $user)
    {
        $data = app(SiteConfigService::class)->getData();

        if ($data['first_sms_phone'])
            $this->call([
                'receptor' => trim($data['first_sms_phone']),
                'token' => $user,
                'template' => 'ordermanager',
            ]);

        if ($data['second_sms_phone'])
            $this->call([
                'receptor' => trim($data['second_sms_phone']),
                'token' => $user,
                'template' => 'ordermanager',
            ]);
    }

    public function submitImage(string $token, string $token2)
    {
        return $this->call([
            'receptor' => $token,
            'token' => $token,
            'token2' => $token2,
            'template' => 'submitimage',
        ]);
    }

    public function gatewayPayOrderCustomer(string $phone, string $token)
    {
        return $this->call([
            'receptor' => $phone,
            'token' => $token,
            'template' => 'gateway-pay-order-customer',
        ]);
    }

    public function creditPayOrderCustomer(string $phone, string $token)
    {
        return $this->call([
            'receptor' => $phone,
            'token' => $token,
            'template' => 'credit-pay-order-customer',
        ]);
    }

    public function orderSentCustomer(string $phone)
    {
        return $this->call([
            'receptor' => $phone,
            'template' => 'order-sent-customer',
        ]);
    }
}
