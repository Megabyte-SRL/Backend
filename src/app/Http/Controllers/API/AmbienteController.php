<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarAmbienteRequest;
use App\Http\Resources\AmbientesListResource;
use App\Models\Ambiente;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  Ambiente::all();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuardarAmbienteRequest $request)
    {
        // Start transaction to ensure atomicity
        DB::beginTransaction();

        try {
            $ambienteData = $request->only(['nombre', 'capacidad', 'accesibilidad', 'descripcion']);
            $ambiente = Ambiente::create($ambienteData);
            $ubicacionData = $request->input('ubicacion');
            Ubicacion::create([
                'ambiente_id' => $ambiente->id,
                'descripcion' => $ubicacionData['descripcion'],
                'edificio' => $ubicacionData['edificio'],
                'piso' => $ubicacionData['piso'],
            ]);

            DB::commit();

            return response()->json([
                'res' => true,
                'msg' => 'Ambiente creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error creando ambiente: ' . $e->getMessage());

            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Listamos todos los ambientes.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        return AmbientesListResource::collection(Ambiente::all());
    }
}
