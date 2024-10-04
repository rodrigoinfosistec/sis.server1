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
        Schema::create('employeegroupcompanies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employeegroup_id');
            $table->unsignedBigInteger('company_id');

            $table->integer('limit')->default(1);

            $table->timestamps();

            $table->foreign('employeegroup_id')->references('id')->on('employeegroups');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeegroupcompanies');
    }
};