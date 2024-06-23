<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FechaHabilitada;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FechaHabilitadaController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        $fecha = FechaHabilitada::create([
            'fecha_inicio' => $validatedData['fechaInicio'],
            'fecha_fin' => $validatedData['fechaFin'],
        ]);

        return response()->json([
            'msg' => 'Fechas de inscripción registradas correctamente',
            'data' => $fecha
        ], 201);
    }

    public function checkFecha()
    {
        $fecha = FechaHabilitada::where('deleted_at', null)->orderBy('fecha_fin', 'desc')->first();

        if (!$fecha) {
            return response()->json([
                'msg' => 'empty'
            ]);
        }

        $currentDate = Carbon::now();

        if ($currentDate->greaterThanOrEqualTo($fecha->fecha_fin)) {
            // Soft delete the record
            $fecha->delete();
            return response()->json([
                'msg' => 'update'
            ]);
        }

        return response()->json([
            'msg' => 'valid',
            'data' => $fecha
        ]);
    }

    public function getFechas()
    {
        $fecha = FechaHabilitada::whereNull('deleted_at')->orderBy('fecha_fin', 'desc')->first();

        if (!$fecha) {
            return response()->json(['msg' => 'No hay fechas registradas'], 404);
        }

        return response()->json(['fechaInicio' => $fecha->fecha_inicio, 'fechaFin' => $fecha->fecha_fin]);
    }
}
