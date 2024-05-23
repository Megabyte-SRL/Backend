<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materia extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'codigo',
        'nombre',
        'nivel'
    ];

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'materia_id');
    }
}
