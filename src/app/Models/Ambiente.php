<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ambiente extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['nombre', 'capacidad', 'descripcion'];

    public function horariosDisponibles()
    {
        return $this->hasMany(HorarioDisponible::class, 'ambiente_id');
    }
}
