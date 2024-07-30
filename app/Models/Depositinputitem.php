<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Depositinputitem extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'depositinputitems';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'depositinput_id',
        'provideritem_id',

        'identifier',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function depositinput(){return $this->belongsTo(Depositinput::class);}
    public function provideritem(){return $this->belongsTo(Provideritem::class);}
}
