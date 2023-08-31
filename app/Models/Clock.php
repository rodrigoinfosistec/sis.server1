<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clock extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clocks';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'user_id',
        'company_id',

        'start',
        'end',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function user(){return $this->belongsTo(User::class);}
    public function company(){return $this->belongsTo(Company::class);}

}
