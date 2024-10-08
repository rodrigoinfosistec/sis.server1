<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producedeposit extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'producedeposits';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'produce_id',
        'deposit_id',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function produce(){return $this->belongsTo(Produce::class);}
    public function deposit(){return $this->belongsTo(Deposit::class);}
}
