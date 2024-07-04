<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Balance extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'balances';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'provider_id',
        'provider_name',

        'deposit_id',
        'deposit_name',

        'company_id',

        'user_id',
        'user_name',

        'observation',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function provider(){return $this->belongsTo(Provider::class);}
    public function company(){return $this->belongsTo(Company::class);}
    public function user(){return $this->belongsTo(User::class);}
}
