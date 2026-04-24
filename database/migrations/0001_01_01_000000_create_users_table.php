<?php

use App\Enums\UserRoleEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $user_roles = array_map(fn($case) => $case->value, UserRoleEnum::cases());
            $table->id();
            $table->string('username', 15)->unique('username_unique');
            $table->string('password', 100)->nullable();
            $table->string('name', 60)->nullable();
            $table->string('family', 60)->nullable();
            $table->string('email', 255)->nullable()->unique('email_unique');
            $table->boolean('status')->default(true);
            $table->string('address', 250)->nullable();
            $table->enum('role', $user_roles)->default(UserRoleEnum::CUSTOMER->value);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
