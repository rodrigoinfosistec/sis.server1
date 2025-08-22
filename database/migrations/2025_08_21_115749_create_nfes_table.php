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
        Schema::create('nfes', function (Blueprint $table) {
            $table->id();
            $table->string('chave', 44)->unique();
            $table->string('numero', 20)->nullable();
            $table->string('serie', 10)->nullable();
            $table->string('cnpj_emitente', 14)->nullable();
            $table->string('razao_emitente', 100)->nullable();
            $table->decimal('valor', 15, 2)->nullable();
            $table->date('data_emissao')->nullable();
            $table->longText('xml')->nullable(); // XML completo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfes');
    }
};
