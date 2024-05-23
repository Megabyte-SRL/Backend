<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SolicitudesAmbientesListResource;
use App\Http\Requests\GuardarSolicitudAmbienteRequest;
use App\Models\Docente;
use App\Models\DocenteSolicitud;
use App\Models\SolicitudAmbiente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudAmbienteController extends Controller
{
    /**
     * Registrar solicitud de ambiente.
     *
     * @param GuardarSolicitudAmbienteRequest $request
     */
    public function guardarSolicitudAmbiente(GuardarSolicitudAmbienteRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $docente = Docente::where('usuario_id', $user->id)->first();
            if (!$docente) {
                return response()->json(['msg' => 'Docente no encontrado'], 404);
            }

            $docentes = $request->input('docentes', []);
            if (empty($docentes)) {
                $docentes[] = $docente->id;
            }
            $solicitudAmbiente = SolicitudAmbiente::create([
                'docente_id' => $docente->id,
                'horario_disponible_id' => $request->input('horarioDisponibleId'),
                'capacidad' => $request->input('capacidad'),
                'grupo_id' => $request->input('grupoId'),
                'estado' => 'solicitado',
                'tipo_reserva' => $request->input('tipoReserva'),
            ]);

            foreach ($docentes as $docenteId) {
                DocenteSolicitud::create([
                    'docente_id' => $docenteId,
                    'solicitud_ambiente_id' => $solicitudAmbiente->id,
                ]);
            }

            DB::commit();
            
            return response()->json([
                'res' => true,
                'msg' => 'Solicitud registrada exitosamente'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Listamos todos las solicitudes realizadas.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $solicitudes = SolicitudAmbiente::with([
            'docente',
            'horarioDisponible' => function($query) {
                $query->with('ambiente');
            },
            'grupo' => function($query) {
                $query->with(['docente', 'materia']);
            },
            'docentes'
        ])->get();
        return SolicitudesAmbientesListResource::collection($solicitudes);
    }
}
