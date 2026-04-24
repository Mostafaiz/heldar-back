<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('fileables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->morphs('fileable');
            $table->string('type')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['file_id', 'fileable_id', 'fileable_type', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fileables');
    }
};
