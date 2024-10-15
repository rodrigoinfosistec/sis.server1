<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producemoviment extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'producemoviments';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'produce_id',
        'deposit_id',
        'company_id',
        'user_id',

        'type',

        'user_id',
        'identification',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function produce(){return $this->belongsTo(Produce::class);}
    public function deposit(){return $this->belongsTo(Deposit::class);}
    public function company(){return $this->belongsTo(Company::class);}
    public function user(){return $this->belongsTo(User::class);}

    /**
     * Traduz enum Type.
     * 
     * @var string $name
     * 
     * @return string $new_name
     */
    public static function typeName(string $name) : string {
        switch($name):
            case 'balanco':
                $new_name = 'Balanço';
            break;

            case 'saida':
                $new_name = 'Saída';
            break;

            case 'entrada':
                $new_name = 'Entrada';
            break;

            case 'transferencia':
                $new_name = 'Transferência';
            break;

            default:
                $new_name = 'Indefinido';
            break;
        endswitch;

        return $new_name;
    }

    /**
     * Traduz string Identification.
     * 
     * @var string $string
     * 
     * @return int
     */
    public static function identification(string $string) : int {
        $a = explode('{', $string); // Retira {
        $b = explode('}', $a[1]); // Retira }
        $c = explode(',', $b[0]); // Retira ,
        $d = explode(':', $c[0]); // Retira :
        $id = $d[1];

        return (int)$id;
    }
}
