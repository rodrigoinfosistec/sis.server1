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
            $table->string('cpf')
                ->after('name')
                ->nullable()->default(null);

            $table->string('rg')
                ->after('cpf')
                ->nullable()->default(null);

            $table->string('cnh')
                ->after('rg')
                ->nullable()->default(null);

            $table->string('ctps')
                ->after('cnh')
                ->nullable()->default(null);
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
