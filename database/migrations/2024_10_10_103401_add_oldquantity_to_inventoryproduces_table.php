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
        Schema::table('inventoryproduces', function (Blueprint $table) {
            $table->decimal('quantity_old', total: 16, places: 7)
                ->after('produce_name')->default(0);
            $table->decimal('quantity_diff', total: 16, places: 7)
                ->after('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventoryproduces', function (Blueprint $table) {
            //
        });
    }
};
