<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'deposits';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'company_id',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function company(){return $this->belongsTo(Company::class);}
}
