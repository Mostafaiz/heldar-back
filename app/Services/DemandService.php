<?php

namespace App\Services;

use App\Exceptions\User\UserException;
use App\Models\Demand;
use App\Models\ProductVariant;

class DemandService
{
    public function addDemands($requests, $productId)
    {
        $user = auth()->user();

        if (!isset($user))
            UserException::userNotFound();

        $requests = array_filter($requests, fn($value) => $value['quantity'] != 0);

        $requestIds = array_column($requests, 'product_variant_id');

        Demand::where('user_id', '=', $user->id)->whereIn('product_variant_id', $requestIds)->delete();

        $data = collect($requests)->map(function ($req) use ($productId, $user) {
            return [
                'product_id' => $productId,
                'product_variant_id' => $req['product_variant_id'],
                'count' => $req['quantity'],
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        Demand::insert($data);
    }

    public function getUserDemands()
    {
        $user = auth()->user();

        $demands = $user?->demands()->with(['product:id,name', 'productVariant', 'productVariant.pattern.colors', 'user'])->get() ?? [];

        return $demands;
    }

    public function getAllDemands()
    {
        return Demand::with(['product:id,name', 'productVariant', 'productVariant.pattern.colors', 'user'])->get();
    }

    public function deleteDemand(int $id)
    {
        Demand::where('id', '=', $id)->delete();
    }
}
