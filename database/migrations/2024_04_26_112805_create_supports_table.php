<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable()->unique();
            $table->string('cnpj')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('cellphone')->nullable()->unique();
            $table->string('whatsapp')->nullable()->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};
