<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CheckoutService
{
    protected string $merchantId = '6d2b1c9e-ec7a-4a84-9d5a-b0cfbb8e943f'; // مقدار تصادفی آماده
    protected string $baseUrl = 'https://sandbox.zarinpal.com/pg/v4/payment';

    public function test(int $amount = 1000, string $callback = 'http://localhost/verify')
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
        ])->post("{$this->baseUrl}/request.json", [
            'merchant_id' => $this->merchantId,
            'amount' => $amount,
            'callback_url' => $callback,
            'description' => 'تست زرین‌پال در حالت sandbox',
            'metadata' => [
                'email' => 'test@example.com',
                'mobile' => '09120000000'
            ],
        ]);

        $data = $response->json();

        if (isset($data['data']['code']) && $data['data']['code'] === 100) {
            return [
                'status' => 'success',
                'authority' => $data['data']['authority'],
                'url' => "https://sandbox.zarinpal.com/pg/StartPay/" . $data['data']['authority']
            ];
        }

        return [
            'status' => 'error',
            'data' => $data
        ];
    }

    public function verify(string $authority, int $amount = 1000)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
        ])->post("{$this->baseUrl}/verify.json", [
            'merchant_id' => $this->merchantId,
            'authority' => $authority,
            'amount' => $amount,
        ]);

        $data = $response->json();

        if (isset($data['data']['code']) && $data['data']['code'] === 100) {
            return [
                'status' => 'success',
                'ref_id' => $data['data']['ref_id'],
                'message' => 'تراکنش موفق'
            ];
        }

        return [
            'status' => 'failed',
            'data' => $data
        ];
    }
}
