<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioDisponible extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'horarios_disponibles';
    protected $fillable = ['ambiente_id', 'fecha', 'hora_inicio', 'hora_fin'];

    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class, 'ambiente_id');
    }

    public function solicitudesAmbientes()
    {
        return $this->hasMany(SolicitudAmbiente::class, 'horario_disponible_id');
    }
}
