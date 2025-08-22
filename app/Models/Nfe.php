<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nfe extends Model
{
    use HasFactory;

    protected $table = 'nfes';

    protected $fillable = [
        'chave',
        'numero',
        'serie',
        'cnpj_emitente',
        'razao_emitente',
        'valor',
        'data_emissao',
        'xml',
    ];
}
