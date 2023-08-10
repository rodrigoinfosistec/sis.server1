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
        Schema::create('usergrouppages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usergroup_id');
            $table->unsignedBigInteger('page_id');

            $table->timestamps();

            $table->foreign('usergroup_id')->references('id')->on('usergroups');
            $table->foreign('page_id')->references('id')->on('pages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usergrouppages');
    }
};
