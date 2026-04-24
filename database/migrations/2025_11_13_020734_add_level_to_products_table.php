<?php

use App\Enums\ProductLevelsEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $productLevels = array_map(fn($case) => $case->value, ProductLevelsEnum::cases());

            $table->enum('level', $productLevels)->default(ProductLevelsEnum::BORONZE->value);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
