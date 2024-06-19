<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudStatusChange extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'solicitud_ambiente_id',
        'estado_antiguo',
        'estado_nuevo',
        'fecha',
    ];

    // Define any relationships if necessary
    public function solicitudAmbiente()
    {
        return $this->belongsTo(SolicitudAmbiente::class);
    }
}
