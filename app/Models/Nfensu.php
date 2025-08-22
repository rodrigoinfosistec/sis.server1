<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nfensu extends Model
{
    use HasFactory;

    protected $table = 'nfensus';
    protected $fillable = ['ultimo_nsu'];
}
