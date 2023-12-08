<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_per_minute');
            $table->unsignedBigInteger('data_size_per_month');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
