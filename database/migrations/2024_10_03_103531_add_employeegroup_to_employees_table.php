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
            $table->unsignedBigInteger('employeegroup_id')
                ->after('company_name')
                ->nullable()->default(null);

            $table->string('employeegroup_name')
                ->after('employeegroup_id')
                ->nullable()->default(null);

            $table->foreign('employeegroup_id')->references('id')->on('employeegroups');
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
