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
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('limit_start_week')
                ->after('journey')
                ->default(475);

            $table->integer('limit_end_week')
                ->after('limit_start_week')
                ->default(1025);

            $table->integer('limit_start_saturday')
                ->after('limit_end_week')
                ->default(475);

            $table->integer('limit_end_saturday')
                ->after('limit_start_saturday')
                ->default(725);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
};
