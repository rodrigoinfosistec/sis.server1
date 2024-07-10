<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposittransfer extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'deposittransfers';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'origin_id',
        'origin_name',

        'destiny_id',
        'destiny_name',

        'user_id',
        'user_name',

        'observation',

        'funded',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function origin(){return $this->belongsTo(Deposit::class);}
    public function destiny(){return $this->belongsTo(Deposit::class);}
    public function user(){return $this->belongsTo(User::class);}
}
