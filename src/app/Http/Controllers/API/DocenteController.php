<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MateriaGrupoListResource;
use App\Http\Resources\DocentesListResource;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocenteController extends Controller
{
    /**
     *  Obtener todos las materias y grupos de un docente.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerMateriasGrupos(Request $request)
    {
        try {
            $user = Auth::user();
            $docente = Docente::where('usuario_id', $user->id)->first();
            
            if (!$docente) {
                return response()->json(['msg' => 'Docente not found'], 404);
            }

            return MateriaGrupoListResource::collection($docente->grupos);
        } catch(\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtener la lista de docentes que dan una materia especifica
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerDocentesPorMateria($materia_id)
    {
        $docentes = Docente::whereHas('grupos', function($query) use ($materia_id) {
            $query->where('materia_id', $materia_id);
        })->get();

        return DocentesListResource::collection($docentes);
    }
}
