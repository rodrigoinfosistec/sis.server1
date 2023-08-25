<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'configs';

    /**
     * Definições do Usuário "MASTER".
     * 
     * @return array $userMaster
     */
    public static function getUserMaster() : array {
        // Definições do Usuário "MASTER".
        $userMaster = [
            'hashMaster' => 'App1000#',
        ];

        return $userMaster;
    }

    /**
     * Define as áginas.
     * 
     * @return array $pages
     */
    public static function getPages() : array {
        // Define as áginas.
        $pages = [
            [
                'name'  => 'home',
                'title' => 'Principal',
                'icon'  => 'bi-house-fill',
                'test'  => false,
            ],
            [
                'name'  => 'logo',
                'title' => 'Logo',
                'icon'  => 'bi-card-image',
                'test'  => false,
            ],
            [
                'name'  => 'user',
                'title' => 'Usuário',
                'icon'  => 'bi-person-fill',
                'test'  => true,
            ],
            [
                'name'  => 'usergroup',
                'title' => 'Grupo de Usuário',
                'icon'  => 'bi-people-fill',
                'test'  => true,
            ],
            [
                'name'  => 'audit',
                'title' => 'Auditoria',
                'icon'  => 'bi-binoculars-fill',
                'test'  => true,
            ],
            [
                'name'  => 'company',
                'title' => 'Empresa',
                'icon'  => 'bi-diagram-3',
                'test'  => true,
            ],
            [
                'name'  => 'provider',
                'title' => 'Fornecedor',
                'icon'  => 'bi-truck',
                'test'  => true,
            ],
            [
                'name'  => 'productgroup',
                'title' => 'Grupo de Produto',
                'icon'  => 'bi-boxes',
                'test'  => true,
            ],
            [
                'name'  => 'invoice',
                'title' => 'Nota Fiscal',
                'icon'  => 'bi-receipt',
                'test'  => true,
            ],
            [
                'name'  => 'product',
                'title' => 'Produto',
                'icon'  => 'bi-box',
                'test'  => true,
            ],
        ];

        return $pages;
    }

    /**
     * Definição da Negociação Padrão de Fornecedor.
     * 
     * @return array $business
     */
    public static function getBusinessDefault() : array {
        // Definição da Negociação Padrão de Fornecedor.
        $business = [
            'multiplier_quantity'    => 100.00,
            'multiplier_value'       => 100.00,
            'multiplier_ipi'         => 100.00,
            'multiplier_ipi_aliquot' => 100.00,
            'margin'                 => 34.00,
            'shipping'               => 5.00,
            'discount'               => 0.00,
            'addition'               => 0.00,
        ];

        return $business;
    }

    /**
     * Busca a quantidade escrita no "home/**quantidade.txt"
     * 
     * @return int $file
     */
    public static function getQtdCarousel() : int {
        // Caminho.
        $path = public_path('img/home/carousel/');

        // Arquivo.
        $file_name = 'quantidade.txt';

        // Primeira linha do arquivo.
        (int)$file = file($path . $file_name)[0];

        return $file;
    }

}
