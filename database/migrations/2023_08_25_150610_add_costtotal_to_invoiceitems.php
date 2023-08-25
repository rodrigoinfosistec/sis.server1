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
            $table->float('cost_total', 12, 2)
                    ->after('index')
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
