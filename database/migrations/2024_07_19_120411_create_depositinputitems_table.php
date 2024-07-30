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
        Schema::create('depositinputitems', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('depositinput_id');
            $table->unsignedBigInteger('provideritem_id');

            $table->integer('identifier');

            $table->decimal('quantity', total: 16, places: 7)->default(0);

            $table->timestamps();

            $table->foreign('depositinput_id')->references('id')->on('depositinputs');
            $table->foreign('provideritem_id')->references('id')->on('provideritems');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depositinputitems');
    }
};
