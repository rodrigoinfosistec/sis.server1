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

            $table->unsignedBigInteger('producebrand_id');
            $table->string('producebrand_name');

            $table->unsignedBigInteger('producemeasure_id');
            $table->string('producemeasure_name');

            $table->string('reference')->nullable()->default(null);
            $table->string('ean')->nullable()->default(null);

            $table->boolean('status')->default(true);

            $table->text('observation');

            $table->timestamps();

            $table->foreign('producebrand_id')->references('id')->on('producebrands');
            $table->foreign('producemeasure_id')->references('id')->on('producemeasures');
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
