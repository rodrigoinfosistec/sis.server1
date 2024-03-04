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
            [
                'name'  => 'employee',
                'title' => 'Funcionário',
                'icon'  => 'bi-person',
                'test'  => true,
            ],
            [
                'name'  => 'clock',
                'title' => 'Ponto',
                'icon'  => 'bi-fingerprint',
                'test'  => true,
            ],
            [
                'name'  => 'clockbase',
                'title' => 'Banco de Horas',
                'icon'  => 'bi-clock',
                'test'  => true,
            ],
            [
                'name'  => 'holiday',
                'title' => 'Feriado',
                'icon'  => 'bi-tree',
                'test'  => true,
            ],
            [
                'name'  => 'employeevacation',
                'title' => 'Férias',
                'icon'  => 'bi-sun',
                'test'  => true,
            ],
            [
                'name'  => 'employeeattest',
                'title' => 'Atestado',
                'icon'  => 'bi-clipboard-pulse',
                'test'  => true,
            ],
            [
                'name'  => 'employeelicense',
                'title' => 'Licença',
                'icon'  => 'bi-clipboard-heart',
                'test'  => true,
            ],
            [
                'name'  => 'employeeabsence',
                'title' => 'Falta',
                'icon'  => 'bi-clipboard-x',
                'test'  => true,
            ],
            [
                'name'  => 'employeeallowance',
                'title' => 'Abono',
                'icon'  => 'bi-clipboard-check',
                'test'  => true,
            ],
            [
                'name'  => 'employeeeasy',
                'title' => 'Folga',
                'icon'  => 'bi-emoji-sunglasses',
                'test'  => true,
            ],
            [
                'name'  => 'clockregistry',
                'title' => 'Registro de Ponto',
                'icon'  => 'bi-card-checklist',
                'test'  => true,
            ],
            [
                'name'  => 'clockregistryemployee',
                'title' => 'Tratamento de Ponto',
                'icon'  => 'bi-fingerprint',
                'test'  => true,
            ],
            [
                'name'  => 'pointevent',
                'title' => 'Eventos',
                'icon'  => 'bi-fingerprint',
                'test'  => true,
            ],
            [
                'name'  => 'employeebase',
                'title' => 'Espaço do Colaborador',
                'icon'  => 'bi-person-fill',
                'test'  => true,
            ],
            [
                'name'  => 'employeepay',
                'title' => 'Pagamento de Horas',
                'icon'  => 'bi-cash',
                'test'  => true,
            ],
            [
                'name'  => 'employeeseparate',
                'title' => 'Horas Avulsas',
                'icon'  => 'bi-clock-history',
                'test'  => true,
            ],
            [
                'name'  => 'brand',
                'title' => 'Marca',
                'icon'  => 'bi-shield-shaded',
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
