<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudAmbiente extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The associated with the model.
     * @var string
     */
    protected $table = 'solicitudes_ambientes';
    protected $fillable = ['usuario_id', 'horario_disponible_id', 'capacidad', 'materia', 'estado'];

    public function horarioDisponible()
    {
        return $this->belongsTo(HorarioDisponible::class, 'horario_disponible_id');
    }
}
