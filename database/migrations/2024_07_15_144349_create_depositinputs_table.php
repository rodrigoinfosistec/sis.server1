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
        Schema::create('depositinputs', function (Blueprint $table) {
            $table->id();

            $table->string('deposit_name');
            $table->unsignedBigInteger('deposit_id');

            $table->unsignedBigInteger('provider_id');
            $table->string('provider_name');

            $table->unsignedBigInteger('company_id');
            $table->string('company_name');

            $table->unsignedBigInteger('user_id');
            $table->string('user_name');

            $table->string('key')->unique()->nullable()->default(null);
            $table->string('number');
            $table->string('range')->default('000');

            $table->decimal('total', 12, 3)->default(0.000);

            $table->timestamp('issue');

            $table->timestamps();

            $table->foreign('deposit_id')->references('id')->on('deposits');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depositinputs');
    }
};
