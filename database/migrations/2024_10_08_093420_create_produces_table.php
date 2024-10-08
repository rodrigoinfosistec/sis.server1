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
        Schema::create('produces', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->unsignedBigInteger('brand_id');
            $table->string('brand_name');

            $table->string('reference')->nullable()->default(null);
            $table->string('ean')->nullable()->default(null);

            $table->boolean('status')->default(true);

            $table->text('description');

            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produces');
    }
};
