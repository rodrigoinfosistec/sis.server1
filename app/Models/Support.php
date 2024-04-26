<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'supports';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',
        'cnpj',

        'phone',
        'cellphone',
        'whatsapp',
    ];

    /**
     * Busca WhatsApp
     * 
     * @return string whatsapp
     */
    public static function getWhatsApp() : string {
        // Inicializa variável.
        $whatsapp = '#';

        // verifica se existe WhatsApp cadastrado.
        if(!empty(Support::first()->whatsapp)) $whatsapp = 'https://api.whatsapp.com/send/?phone=55' . Support::first()->whatsapp . '&text&type=phone_number&app_absent=0';

        return $whatsapp;
    }
}
