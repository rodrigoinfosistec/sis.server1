<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Depositinput extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'depositinputs';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'deposit_name',
        'deposit_id',

        'provider_id',
        'provider_name',

        'company_id',
        'company_name',

        'user_id',
        'user_name',

        'key',
        'number',
        'range',
        'total',

        'issue',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function deposit(){return $this->belongsTo(Deposit::class);}
    public function provider(){return $this->belongsTo(Provider::class);}
    public function company(){return $this->belongsTo(Company::class);}
    public function user(){return $this->belongsTo(User::class);}
}
