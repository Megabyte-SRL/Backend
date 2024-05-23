<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GrupoController extends Controller
{
    
    /**
     *  Obtener todos las materias y grupos de un docente.
     *
     * @param $request
     */
    public function obtenerMateriasGruposDocente(Request $request)
    {
        try {
            
        } catch(\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
