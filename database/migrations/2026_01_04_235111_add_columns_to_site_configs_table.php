<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_configs', function (Blueprint $table) {
            $table->string('first_sms_phone')->nullable();
            $table->string('second_sms_phone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('site_configs', function (Blueprint $table) {
            $table->dropColumn('first_sms_phone');
            $table->dropColumn('second_sms_phone');
        });
    }
};
