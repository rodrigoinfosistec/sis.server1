<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposituser extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'depositusers';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'deposit_id',
        'user_id',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function deposit(){return $this->belongsTo(Deposit::class);}
    public function user(){return $this->belongsTo(user::class);}
}
