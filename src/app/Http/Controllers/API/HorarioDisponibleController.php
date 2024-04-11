<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarHorariosAmbienteRequest;
use App\Models\HorarioDisponible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioDisponibleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Guardar una lista de horarios.
     */
    public function guardarHorasDisponibles(GuardarHorariosAmbienteRequest $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->horasDisponibles as $horarioData) {
                HorarioDisponible::create([
                    'ambiente_id' => $request->ambiente_id,
                    'fecha' => $request->fecha,
                    'hora_inicio' => $horarioData['horaInicio'],
                    'hora_fin' => $horarioData['horaFin'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 201,
                'res' => true,
                'msg' => 'Horario creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'res' => false,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HorarioDisponible  $horarioDisponible
     * @return \Illuminate\Http\Response
     */
    public function show(HorarioDisponible $horarioDisponible)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HorarioDisponible  $horarioDisponible
     * @return \Illuminate\Http\Response
     */
    public function edit(HorarioDisponible $horarioDisponible)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HorarioDisponible  $horarioDisponible
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HorarioDisponible $horarioDisponible)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HorarioDisponible  $horarioDisponible
     * @return \Illuminate\Http\Response
     */
    public function destroy(HorarioDisponible $horarioDisponible)
    {
        //
    }
}
