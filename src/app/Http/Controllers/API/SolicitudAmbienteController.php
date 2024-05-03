<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarSolicitudAmbienteRequest;
use App\Models\SolicitudAmbiente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudAmbienteController extends Controller
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
     * Display the specified resource.
     *
     * @param  \App\Models\SolicitudAmbiente  $solicitudAmbiente
     * @return \Illuminate\Http\Response
     */
    public function show(SolicitudAmbiente $solicitudAmbiente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SolicitudAmbiente  $solicitudAmbiente
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudAmbiente $solicitudAmbiente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SolicitudAmbiente  $solicitudAmbiente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitudAmbiente $solicitudAmbiente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SolicitudAmbiente  $solicitudAmbiente
     * @return \Illuminate\Http\Response
     */
    public function destroy(SolicitudAmbiente $solicitudAmbiente)
    {
        //
    }

    /**
     * Registrar solicitud de ambiente.
     */
    public function guardarSolicitudAmbiente(GuardarSolicitudAmbienteRequest $request)
    {
        try {
            SolicitudAmbiente::create([
                'usuario_id' => Auth::id(),
                'horario_disponible_id' => $request->input('horarioDisponibleId'),
                'capacidad' => $request->input('capacidad'),
                'materia' => $request->input('materia'),
                'estado' => 'solicitado',
            ]);

            return response()->json([
                'status' => 201,
                'res' => true,
                'msg' => 'Solicitud registrada exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'res' => false,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }
}
