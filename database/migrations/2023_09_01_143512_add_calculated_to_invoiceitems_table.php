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
        Schema::table('invoiceitems', function (Blueprint $table) {
            $table->float('price_calculated', 12, 2)
                    ->after('cost')
                    ->nullable()->default(null);

            $table->float('card_calculated', 12, 2)
                    ->after('price_calculated')
                    ->nullable()->default(null);

            $table->float('retail_calculated', 12, 2)
                    ->after('card_calculated')
                    ->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoiceitems', function (Blueprint $table) {
            //
        });
    }
};
