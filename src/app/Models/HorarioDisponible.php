<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioDisponible extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'horarios_disponibles';
    protected $fillable = ['ambiente_id', 'fecha', 'hora_inicio', 'hora_fin'];
}
