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

            $table->string('name', length: 120);
            $table->string('code', length: 20)->unique();

            $table->string('reference', length: 30)->nullable()->default(null);
            $table->string('ean', length: 20)->nullable()->default(null);

            $table->decimal('cost', total: 16, places: 7)->nullable()->default(null);
            $table->decimal('margin', total: 16, places: 7)->nullable()->default(null);
            $table->decimal('value', total: 16, places: 7)->nullable()->default(null);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('productgroup_id')->nullable()->default(null);
            $table->unsignedBigInteger('productmeasure_id')->nullable()->default(null);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('productgroup_id')->references('id')->on('productgroups');
            $table->foreign('productmeasure_id')->references('id')->on('productmeasures');
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
