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
    protected $fillable = [
        'docente_id',
        'horario_disponible_id',
        'grupo_id',
        'capacidad',
        'estado',
        'tipo_reserva',
        'razon_rechazo',
        'prioridad'
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docentes_solicitudes', 'solicitud_ambiente_id', 'docente_id');
    }

    public function horarioDisponible()
    {
        return $this->belongsTo(HorarioDisponible::class, 'horario_disponible_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
}
