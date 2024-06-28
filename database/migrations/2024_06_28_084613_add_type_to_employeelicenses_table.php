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
        Schema::table('employeelicenses', function (Blueprint $table) {
            $table->enum('type', ['MATERNIDADE', 'PATERNIDADE', 'CASAMENTO', 'OBITO', 'SAUDE'])
                ->after('employee_name')
                ->default('SAUDE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employeelicenses', function (Blueprint $table) {
            //
        });
    }
};
