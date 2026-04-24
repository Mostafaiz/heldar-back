<?php

use App\Enums\Transaction\TransactionStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cart_id')->nullable()->constrained('carts')->nullOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('method')->default('online');
            $table->string('gateway')->nullable();
            $table->string('authority')->nullable();
            $table->string('ref_id')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'accepted', 'rejected', 'uploading'])->default('pending');
            $table->string('cheque_image')->nullable();
            $table->text('note')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
