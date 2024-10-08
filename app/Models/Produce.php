<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Produce extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'products';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'reference',
        'ean',

        'quantity',

        'company_id',
        'productgroup_id',
        'productmeasure_id',

        'status',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function company(){return $this->belongsTo(Company::class);}
    public function productgroup(){return $this->belongsTo(Productgroup::class);}
    public function productmeasure(){return $this->belongsTo(Productmeasure::class);}
}
