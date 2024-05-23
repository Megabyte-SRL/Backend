<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocenteSolicitud extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'docentes_solicitudes';
    protected $fillable = [
        'docente_id',
        'solicitud_ambiente_id'
    ];
}
