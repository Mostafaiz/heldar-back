<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->unsignedInteger('quantity')->default(1);
            $table->foreignId('guarantee_id')->nullable();
            $table->foreignId('insurance_id')->nullable();
            $table->timestamps();

            $table->unique(['cart_id', 'product_variant_id', 'guarantee_id', 'insurance_id'], 'cart_item_unique');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
