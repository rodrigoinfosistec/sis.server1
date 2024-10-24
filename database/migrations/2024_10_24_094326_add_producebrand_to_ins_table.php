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
        Schema::table('ins', function (Blueprint $table) {
            $table->unsignedBigInteger('producebrand_id')
                ->after('deposit_name')
                ->nullable()->default(null);

            $table->string('producebrand_name')
                ->after('producebrand_id')
                ->nullable()->default(null);

            $table->foreign('producebrand_id')->references('id')->on('producebrands');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ins', function (Blueprint $table) {
            //
        });
    }
};
