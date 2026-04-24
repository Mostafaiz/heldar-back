<?php

use App\Enums\ProductLevelsEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $userLevels = array_map(fn($case) => $case->value, ProductLevelsEnum::cases());

            $table->enum('level', $userLevels)->default(ProductLevelsEnum::BORONZE->value);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
