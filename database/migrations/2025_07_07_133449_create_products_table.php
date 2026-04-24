<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('english_name', 255)->nullable();
            $table->string('slug', 255)->unique();
            $table->string('brand', 255)->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('attribute_group_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
