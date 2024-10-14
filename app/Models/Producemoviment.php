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
}
