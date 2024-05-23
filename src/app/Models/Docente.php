<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Docente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'docentes';
    protected $fillable = [
        'usuario_id',
        'nombre',
        'apellido'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'docente_id');
    }
}
