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
            $table->string('cnpj_emitente', 14)->nullable();
            $table->string('razao_emitente')->nullable();
            $table->dateTime('data_emissao')->nullable();
            $table->decimal('valor', 15, 2)->nullable();
            $table->string('xml_path')->nullable();
            $table->timestamps();
        });

        // tabela para armazenar último NSU
        Schema::create('nfe_configs', function (Blueprint $table) {
            $table->id();
            $table->string('chave', 50)->unique();
            $table->string('valor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfe_configs');
        Schema::dropIfExists('nfes');
    }
};
