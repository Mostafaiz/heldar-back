<?php

namespace App\Services;

use App\Models\PaymentCard;
use Exception;

class PaymentCardService
{
    public function create(array $data)
    {
        $colors = [
            '#0000CC', // darker blue
            '#CC0000', // darker red
            '#006600', // darker green
            '#CCCC00', // darker yellow
            '#7300CC', // darker violet
            '#CC8400', // darker orange
            '#802020', // darker brown
            '#CC9A00', // darker amber
            '#00CCCC', // darker cyan
            '#3DA05E', // darker emerald
            '#660066', // darker purple
        ];

        $randomColor = $colors[array_rand($colors)];

        PaymentCard::create([
            'owner_name'  => $data['ownerName'],
            'bank_name'   => $data['bankName'],
            'card_number' => $data['cardNumber'],
            'iban_number' => $data['IBANnumber'],
            'color'       => $randomColor,
        ]);
    }

    public function getAll()
    {
        return PaymentCard::orderBy('status', 'desc')->latest()->get();
    }

    public function getById(int $id)
    {
        return PaymentCard::findOrFail($id);
    }

    public function getAllCustomer()
    {
        return PaymentCard::where('status', '=', 1)->latest()->get(['owner_name', 'bank_name', 'card_number', 'iban_number']);
    }

    public function update(array $data)
    {
        PaymentCard::where('id', '=', $data['id'])->update([
            'owner_name'  => $data['ownerName'],
            'bank_name'   => $data['bankName'],
            'card_number' => $data['cardNumber'],
            'iban_number' => $data['IBANnumber'],
        ]);
    }

    public function updateCardName(int $id, string $name)
    {
        $paymentCard = PaymentCard::find($id);

        if (!$paymentCard)
            throw new Exception('Payment card not found!', '404');

        $paymentCard->update([
            'owner_name' => $name,
        ]);
    }

    public function updateCardNumber(int $id, string $number)
    {
        $paymentCard = PaymentCard::find($id);

        if (!$paymentCard)
            throw new Exception('Payment card not found!', '404');

        $paymentCard->update([
            'card_number' => $number,
        ]);
    }

    public function updateCardStatus(int $id, bool $status)
    {
        $paymentCard = PaymentCard::find($id);

        if (!$paymentCard)
            throw new Exception('Payment card not found!', '404');

        $paymentCard->update([
            'status' => $status,
        ]);
    }

    public function delete(int $id)
    {
        $paymentCard = PaymentCard::find($id);

        if (!$paymentCard)
            throw new Exception('Payment card not found!', '404');

        $paymentCard->delete();
    }
}
