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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();
            $table->string('ean');

            $table->boolean('status')->default(true);

            $table->unsignedBigInteger('company_id');
            $table->string('company_name');

            $table->unsignedBigInteger('provider_id');
            $table->string('provider_name');

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('provider_id')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};