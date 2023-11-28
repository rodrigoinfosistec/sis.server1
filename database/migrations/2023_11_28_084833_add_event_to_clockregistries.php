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
        Schema::table('clockregistries', function (Blueprint $table) {
            $table->string('event')
                ->after('employee_name')
                ->nullable()->default(null);

            $table->string('code')
                ->after('time')
                ->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clockregistries', function (Blueprint $table) {
            //
        });
    }
};
