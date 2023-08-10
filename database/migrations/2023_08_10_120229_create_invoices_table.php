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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('provider_id');
            $table->string('provider_name');

            $table->unsignedBigInteger('company_id');
            $table->string('company_name');

            $table->string('key')->unique();
            $table->string('number');
            $table->string('range');

            $table->float('total', 12, 3);

            $table->timestamp('issue');

            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
