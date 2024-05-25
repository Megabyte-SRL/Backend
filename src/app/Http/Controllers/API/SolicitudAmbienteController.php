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

            $prioridad = $this->calculatePriority($request->input('tipoReserva'), count($docentes));
            $solicitudAmbiente = SolicitudAmbiente::create([
                'docente_id' => $docente->id,
                'horario_disponible_id' => $request->input('horarioDisponibleId'),
                'capacidad' => $request->input('capacidad'),
                'grupo_id' => $request->input('grupoId'),
                'estado' => 'solicitado',
                'tipo_reserva' => $request->input('tipoReserva'),
                'prioridad' => $prioridad,
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
     * Calculate the priority of the solicitud.
     *
     * @param string $tipoReserva
     * @param int $docentesCount
     * @return int
     */
    private function calculatePriority($tipoReserva, $docentesCount)
    {
        $priorities = [
            'Emergencia' => 1,
            'Examen Mesa' => 2,
            'Parcial' => 3,
            'Clases normal' => 4,
        ];

        $priority = isset($priorities[$tipoReserva]) ? $priorities[$tipoReserva] : 4;
        $priority += $docentesCount;

        return $priority;
    }

    /**
     * Listamos todos las solicitudes realizadas.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $query = SolicitudAmbiente::where('estado', '<>', 'reservado')
            ->with([
                'docente',
                'horarioDisponible' => function($query) {
                    $query->with('ambiente');
                },
                'grupo' => function($query) {
                    $query->with(['docente', 'materia']);
                },
                'docentes'
            ]);

        // Search
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('horarioDisponible', function($q) use ($search) {
                    $q->where('fecha', 'LIKE', "%{$search}%")
                      ->orWhere('hora_inicio', 'LIKE', "%{$search}%")
                      ->orWhere('hora_fin', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('horarioDisponible.ambiente', function($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%");
                })
                ->orWhere('capacidad', 'LIKE', "%{$search}%")
                ->orWhere('estado', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        if ($request->has('sortField') && $request->has('sortDirection')) {
            $sortField = $request->input('sortField');
            $sortDirection = $request->input('sortDirection');

            // Validate sort direction
            if (in_array(strtolower($sortDirection), ['asc', 'desc'])) {
                if ($sortField == 'ambiente') {
                    $query->join('horarios_disponibles', 'solicitudes_ambientes.horario_disponible_id', '=', 'horarios_disponibles.id')
                            ->join('ambientes', 'horarios_disponibles.ambiente_id', '=', 'ambientes.id')
                            ->orderBy('ambientes.nombre', $sortDirection)
                            ->select('solicitudes_ambientes.*');
                } elseif ($sortField == 'capacidadAmbiente') {
                    $query->join('horarios_disponibles', 'solicitudes_ambientes.horario_disponible_id', '=', 'horarios_disponibles.id')
                            ->join('ambientes', 'horarios_disponibles.ambiente_id', '=', 'ambientes.id')
                            ->orderBy('ambientes.capacidad', $sortDirection)
                            ->select('solicitudes_ambientes.*');
                } elseif ($sortField == 'horario') {
                    $query->join('horarios_disponibles', 'solicitudes_ambientes.horario_disponible_id', '=', 'horarios_disponibles.id')
                            ->orderByRaw("CONCAT(hora_inicio, ' - ', hora_fin) $sortDirection")
                            ->select('solicitudes_ambientes.*');
                } elseif ($sortField == 'fecha') {
                    $query->join('horarios_disponibles', 'solicitudes_ambientes.horario_disponible_id', '=', 'horarios_disponibles.id')
                            ->orderBy('horarios_disponibles.fecha', $sortDirection)
                            ->select('solicitudes_ambientes.*');
                } else {
                    $query->orderBy($sortField, $sortDirection);
                }
            } else {
                return response()->json(['error' => 'Invalid sort direction'], 400);
            }
        }

        // Generic Filtering
        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['search', 'sortField', 'sortDirection', 'perPage', 'page']) || empty($value)) {
                continue;
            }
            $query->where($key, $value);
        }

        // Paginación
        $perPage = $request->input('perPage', 10);
        $solicitudes = $query->paginate($perPage);

        return SolicitudesAmbientesListResource::collection($solicitudes);
    }

    /**
     * Registramos una solicitud de reserva como reservado.
     *
     * @return \Illuminate\Http\Response
     */
    public function reservar($solicitud_id)
    {
        $solicitud = SolicitudAmbiente::findOrFail($solicitud_id);

        $solicitud->estado = 'reservado';
        $solicitud->save();

        return response()->json([
            'msg' => 'Ambiente reservado exitosamente',
            'data' => $solicitud
        ], 200);
    }
}
