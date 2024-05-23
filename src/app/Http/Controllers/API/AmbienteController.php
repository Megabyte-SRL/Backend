<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarAmbienteRequest;
use App\Http\Requests\GuardarAmbientesArchivoRequest;
use App\Http\Resources\AmbientesListResource;
use App\Models\Ambiente;
use App\Models\Ubicacion;
use App\Models\HorarioDisponible;
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
     * @param  GuardarAmbienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuardarAmbienteRequest $request)
    {
        // Start transaction to ensure atomicity
        DB::beginTransaction();

        try {
            $ambienteData = $request->only(['nombre', 'capacidad', 'descripcion']);
            $ambiente = Ambiente::create($ambienteData);
            $ubicacionData = $request->input('ubicacion');
            Ubicacion::create([
                'ambiente_id' => $ambiente->id,
                'lugar' => $ubicacionData['lugar'],
                'edificio' => $ubicacionData['edificio'],
                'piso' => $ubicacionData['piso'],
            ]);

            DB::commit();

            return response()->json([
                'status' => 201,
                'res' => true,
                'msg' => 'Ambiente creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error creando ambiente: ' . $e->getMessage());

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
     * Desabilitar un registro de la base de datos.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        $ambiente = Ambiente::find($id);
        $ambiente->delete();
        
        return response()->json([
            'status' => 204,
            'res' => true,
            'msg' => 'Ambiente eliminado exitosamente'
        ]);
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

    /**
     * Handle the file upload.
     *
     * @param GuardarAmbientesArchivoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function subirArchivo(GuardarAmbientesArchivoRequest $request)
    {
        try {
            $path = $request->file('file')->store('temp');
            $path = storage_path('app/' . $path);

            $handle = fopen($path, 'r');
            if (!$handle) {
                \Log::error('Failed to open the file');
                return response() -> json(['msg' => 'Failed to open the file'], 500);
            }
            $header = fgetcsv($handle);
            
            while ($csvLine = fgetcsv($handle)) {
                $data = array_combine($header, $csvLine);
                $this->proccessCsvRow($data);
            }

            fclose($handle);
            \Storage::delete($path);

            return response()->json(['msg' => 'Archivo procesado exitosamente'], 200);
        } catch (\Exception $e) {
            \Log::error('Error cargando ambientes: '. $e->getMessage());

            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    private function proccessCsvRow($data)
    {
        $nombre = $data['Ambiente'];
        $capacidad = $data['Capacidad'];
        $descripcion = $data['Descripcion'];
        $lugar = $data['Lugar'];
        $piso = $data['Piso'];
        $edificio = $data['Edificio'];
        $horarios = array_map('trim', preg_split('/[\-–—]/', $data['Horario']));
        
        try {
            $dateObj = \DateTime::createFromFormat('d/m/Y', $data['Fecha']);
            if (!$dateObj) {
                throw new \Exception('Invalid date format.');
            }

            $fecha = $dateObj->format('Y-m-d');
        } catch (\Exception $e) {
            \Log::error('Failed to process date or save to database: ' . $e->getMessage());
        }

        $ambiente = Ambiente::firstOrCreate([
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'descripcion' => $descripcion,
        ]);

        Ubicacion::firstOrCreate([
            'ambiente_id' => $ambiente->id,
            'lugar' => $lugar,
            'edificio' => $edificio,
            'piso' => $piso
        ]);

        HorarioDisponible::create([
            'ambiente_id' => $ambiente->id,
            'fecha' => $fecha,
            'hora_inicio' => $horarios[0],
            'hora_fin' => $horarios[1],
        ]);
    }
}
