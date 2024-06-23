<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FechaHabilitada extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'fechas_habilitadas';
    protected $fillable = ['fecha_inicio', 'fecha_fin'];
}
