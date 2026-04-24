<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_cards', function (Blueprint $table) {
            $table->string('owner_name', 255)->after('id')->change();
            $table->string('bank_name', 255)->after('owner_name');
            $table->string('iban_number', 24)->after('card_number');
        });
    }

    public function down(): void
    {
        Schema::table('payment_cards', function (Blueprint $table) {
            $table->string('owner_name')->after('id')->change();
            $table->dropColumn('bank_name', 'iban_number');
        });
    }
};
