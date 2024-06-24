<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarHorarioMateriasArchivoRequest;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Usuario;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class HorarioMateriasController extends Controller
{
    /**
     * Handle the file upload.
     *
     * @param GuardarHorarioMateriasArchivoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function subirArchivo(GuardarHorarioMateriasArchivoRequest $request)
    {
        try {
            $path = $request->file('file')->store('temp');
            $path = storage_path('app/' . $path);

            if (!($handle = fopen($path, 'r'))) {
                Log::error('Failed to open the file.');
                return response()->json(['msg' => 'Failed to open the file'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $header = fgetcsv($handle);
            if ($header === false) {
                fclose($handle);
                Storage::delete($path);
                return response()->json(['msg' => 'Failed to read the header'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            while ($csvLine = fgetcsv($handle)) {
                $data = array_combine($header, $csvLine);
                $this->proccessCsvRow($data);
            }
            fclose($handle);
            Storage::delete($path);

            return response()->json(['msg' => 'Archivo procesado exitosamente'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function proccessCsvRow($data)
    {
        $codigo = $data['Codigo'];
        $nombreMateria = $data['Materia'];
        $grupo = $data['Grupo'];
        $nivel = $data['Nivel'];

        $nombreDocente = $data['Nombre'];
        $apellidoDocente = $data['Apellido'];
        $emailDocente = $data['Email'];
        
        $materia = Materia::firstOrCreate([
            'codigo' => $codigo,
            'nombre' => $nombreMateria,
            'nivel' => $nivel,
        ]);

        $usuario = Usuario::firstOrCreate([
            'email' => $emailDocente,
        ], [
            'password' => Hash::make('password1234'),
            'rol' => 'docente',
        ]);

        $docente = Docente::firstOrCreate([
            'nombre' => $nombreDocente,
            'apellido' => $apellidoDocente,
            'usuario_id' => $usuario->id,
        ]);

        Grupo::create([
            'docente_id' => $docente->id,
            'materia_id' => $materia->id,
            'grupo' => $grupo,
        ]);
    }
}
