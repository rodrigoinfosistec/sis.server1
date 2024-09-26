<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presenceinemployee extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'presenceinemployees';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'presencein_id',
        'employee_id',
        'employee_name',

        'is_present',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function presencein(){return $this->belongsTo(Presencein::class);}
    public function employee(){return $this->belongsTo(Employee::class);}

    /**
     * Atualiza Presence.
     * @var int $presenceinemployee_id
     * 
     * @return bool true
     */
    public static function editPresence(int $presenceinemployee_id) : bool {
        // Atualiza Presença.
        Presenceinemployee::find($presenceinemployee_id)->update([
            'is_present' => true,
        ]);

        return true;
    }
}
