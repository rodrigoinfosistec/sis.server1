<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Providerbusinessdefault extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'providerbusinessdefaults';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'multiplier_quantity',
        'multiplier_value',
        'multiplier_ipi',
        'multiplier_ipi_aliquot',

        'margin',
        'shipping',

        'discount',
        'addition',

        'created_at',
        'updated_at',
    ];

    /**
     * Define a Negociação Padrão de Fornecedor.
     */
    public static function businessDefault() : void {
        // Negociação Padrão de Fornecedor.
        $default = Config::getBusinessDefault();

        // Verifica se não existe Negociação Padrão já cadastrada.
        if(Providerbusinessdefault::doesntExist()):
            // Cadastra a Negociação Padrão de Fornecedor.
            Providerbusinessdefault::create([
                'multiplier_quantity'    => $default['multiplier_quantity'],
                'multiplier_value'       => $default['multiplier_value'],
                'multiplier_ipi'         => $default['multiplier_ipi'],
                'multiplier_ipi_aliquot' => $default['multiplier_ipi_aliquot'],
                'margin'                 => $default['margin'],
                'shipping'               => $default['shipping'],
                'discount'               => $default['discount'],
                'addition'               => $default['addition'],
            ]);
        endif;
    }

}
