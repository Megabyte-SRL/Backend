<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SolicitudesAmbientesListResource;
use App\Http\Resources\SolicitudStatusChangeResource;
use App\Http\Requests\GuardarSolicitudAmbienteRequest;
use App\Models\Docente;
use App\Models\DocenteSolicitud;
use App\Models\HorarioDisponible;
use App\Models\SolicitudAmbiente;
use App\Models\SolicitudStatusChange;
use App\Mail\EnviarCorreo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

            $horarioDisponible = HorarioDisponible::findOrFail($request->input('horarioDisponibleId'));
            if ($horarioDisponible->estado !== 'disponible') {
                return response()->json(['msg' => 'Horario no disponible'], 400);
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
                'tipo_reserva' => $request->input('tipoReserva'),
                'prioridad' => $prioridad,
            ]);

            $horarioDisponible->estado = 'solicitado';
            $horarioDisponible->save();

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

    public function aprobarSolicitud($solicitud_id)
    {
        DB::beginTransaction();
        try {
            $solicitud = SolicitudAmbiente::findOrFail($solicitud_id);
            $horarioDisponible = HorarioDisponible::findOrFail($solicitud->horario_disponible_id);

            $oldStatus = $horarioDisponible->estado;
            $horarioDisponible->estado = 'reservado';
            $horarioDisponible->save();

            SolicitudStatusChange::create([
                'solicitud_ambiente_id' => $solicitud->id,
                'estado_antiguo' => $oldStatus,
                'estado_nuevo' => 'reservado',
                'fecha' => now(),
            ]);

            $solicitud->delete();

            DB::commit();

            $docente = Docente::findOrFail($solicitud->docente_id);
            if ($docente->usuario) {
                $usuario = $docente->usuario;
                Mail::to($usuario->email)->send(new EnviarCorreo($solicitud, 'aprobada'));
            }
            

            return response()->json([
                'msg' => 'Solicitud aprobada exitosamente',
                'data' => $solicitud
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'msg' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rechazarSolicitud($solicitud_id)
    {
        DB::beginTransaction();
        try {
            $solicitud = SolicitudAmbiente::findOrFail($solicitud_id);
            $horarioDisponible = HorarioDisponible::findOrFail($solicitud->horario_disponible_id);

            $oldStatus = $horarioDisponible->estado;
            $horarioDisponible->estado = 'disponible';
            $horarioDisponible->save();

            SolicitudStatusChange::create([
                'solicitud_ambiente_id' => $solicitud->id,
                'estado_antiguo' => $oldStatus,
                'estado_nuevo' => 'rechazado',
                'fecha' => now(),
            ]);

            $docente = Docente::findOrFail($solicitud->docente_id);
            if ($docente->usuario) {
                $usuario = $docente->usuario;
                Mail::to($usuario->email)->send(new EnviarCorreo($solicitud, 'rechazada'));
            }

            $solicitud->delete();

            DB::commit();

            

            return response()->json([
                'msg' => 'Solicitud rechazada exitosamente',
                'data' => $solicitud
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'msg' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Listamos solicitudes usuando parametros.
     *
     * @return \Illuminate\Http\Response
     */
    public function listSolicitudes(Request $request)
    {
        $user = Auth::user();
        $query = SolicitudAmbiente::with(['docente', 'horarioDisponible.ambiente', 'grupo.docente', 'grupo.materia']);

        // If the user is a docente, filter to show only their solicitudes
        if ($user->rol == 'docente') { // Adjust the condition based on how you define roles
            $docente = Docente::where('usuario_id', $user->id)->first();
            if ($docente) {
                $query->where('docente_id', $docente->id);
            }
        }

        // Generic Filtering
        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['search', 'sortField', 'sortDirection', 'perPage', 'page']) || empty($value)) {
                continue;
            }
            if ($key === 'estado') {
                $query->whereHas('horarioDisponible', function ($q) use ($value) {
                    $q->whereIn('estado', explode(',', $value));
                });
            } else {
                $query->where($key, $value);
            }
        }

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
                } elseif ($sortField == 'capacidadReserva') {
                    $query->orderBy('capacidad', $sortDirection);
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

        // Paginación
        $perPage = $request->input('perPage', 10);
        $solicitudes = $query->paginate($perPage);

        return SolicitudesAmbientesListResource::collection($solicitudes);
    }

    public function aprobarSugerencia($solicitud_id)
    {
        DB::beginTransaction();
        try {
            $solicitud = SolicitudAmbiente::findOrFail($solicitud_id);
            $horarioDisponible = HorarioDisponible::findOrFail($solicitud->horario_disponible_id);

            $oldStatus = $horarioDisponible->estado;
            $horarioDisponible->estado = 'aceptado';
            $horarioDisponible->save();

            SolicitudStatusChange::create([
                'solicitud_ambiente_id' => $solicitud->id,
                'estado_antiguo' => $oldStatus,
                'estado_nuevo' => 'aceptado',
                'fecha' => now(),
            ]);

            DB::commit();

            return response()->json([
                'msg' => 'Sugerencia aprobada exitosamente',
                'data' => $solicitud
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'msg' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rechazarSugerencia($solicitud_id)
    {
        DB::beginTransaction();
        try {
            $solicitud = SolicitudAmbiente::findOrFail($solicitud_id);
            $horarioDisponible = HorarioDisponible::findOrFail($solicitud->horario_disponible_id);

            $oldStatus = $horarioDisponible->estado;
            $horarioDisponible->estado = 'disponible';
            $horarioDisponible->save();

            SolicitudStatusChange::create([
                'solicitud_ambiente_id' => $solicitud->id,
                'estado_antiguo' => $oldStatus,
                'estado_nuevo' => 'rechazado',
                'fecha' => now(),
            ]);

            $solicitud->delete();

            DB::commit();

            return response()->json([
                'msg' => 'Sugerencia rechazada exitosamente',
                'data' => $solicitud
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'msg' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Registrar sugerencias de ambientes para el docente.
     *
     * @return \Illuminate\Http\Response
     */
    public function sugerirHorarios(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->horariosDisponibles as $horarioId) {
                $horarioDisponible = HorarioDisponible::findOrFail($horarioId);
                $horarioDisponible->estado = 'sugerido';
                $horarioDisponible->save();

                SolicitudAmbiente::create([
                    'docente_id' => $request->input('docenteId'),
                    'horario_disponible_id' => $horarioDisponible->id,
                    'capacidad' => $request->input('capacidad'),
                    'grupo_id' => $request->input('grupoId'),
                    'tipo_reserva' => $request->input('tipoReserva'),
                    'prioridad' => 0,
                ]);
            }

            DB::commit();

            return response()->json([
                'res' => true,
                'msg' => 'Sugerencias registradas exitosamente'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function listSolicitudesStatusChanges(Request $request)
    {
        $user = Auth::user();

        $query = SolicitudStatusChange::with([
            'solicitudAmbiente.docente',
            'solicitudAmbiente.horarioDisponible.ambiente',
            'solicitudAmbiente.grupo.docente',
            'solicitudAmbiente.grupo.materia'
        ]);

        // Apply filters based on the request parameters
        if ($user->rol == 'docente') {
            $docente = Docente::where('usuario_id', $user->id)->first();
            if ($docente) {
                $query->whereHas('solicitudAmbiente', function ($q) use ($docente) {
                    $q->where('docente_id', $docente->id);
                });
            }
        }

        // Apply the same filters as in listSolicitudes
        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['search', 'sortField', 'sortDirection', 'perPage', 'page']) || empty($value)) {
                continue;
            }
            if ($key === 'estado') {
                $query->whereHas('solicitudAmbiente.horarioDisponible', function ($q) use ($value) {
                    $q->whereIn('estado', explode(',', $value));
                });
            } else {
                $query->whereHas('solicitudAmbiente', function ($q) use ($key, $value) {
                    $q->where($key, $value);
                });
            }
        }

        // Apply search filter
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('solicitudAmbiente.horarioDisponible', function ($q) use ($search) {
                    $q->where('fecha', 'LIKE', "%{$search}%")
                        ->orWhere('hora_inicio', 'LIKE', "%{$search}%")
                        ->orWhere('hora_fin', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('solicitudAmbiente.horarioDisponible.ambiente', function ($q) use ($search) {
                        $q->where('nombre', 'LIKE', "%{$search}%");
                    })
                    ->orWhere('estado_antiguo', 'LIKE', "%{$search}%")
                    ->orWhere('estado_nuevo', 'LIKE', "%{$search}%");
            });
        }

        // Apply sorting
        if ($request->has('sortField') && $request->has('sortDirection')) {
            $sortField = $request->input('sortField');
            $sortDirection = $request->input('sortDirection');

            // Validate sort direction
            if (in_array(strtolower($sortDirection), ['asc', 'desc'])) {
                if ($sortField == 'ambiente') {
                    $query->join('solicitudes_ambientes', 'solicitud_status_changes.solicitud_ambiente_id', '=', 'solicitudes_ambientes.id')
                        ->join('horarios_disponibles', 'solicitudes_ambientes.horario_disponible_id', '=', 'horarios_disponibles.id')
                        ->join('ambientes', 'horarios_disponibles.ambiente_id', '=', 'ambientes.id')
                        ->orderBy('ambientes.nombre', $sortDirection)
                        ->select('solicitud_status_changes.*');
                } elseif ($sortField == 'capacidadAmbiente') {
                    $query->join('solicitudes_ambientes', 'solicitud_status_changes.solicitud_ambiente_id', '=', 'solicitudes_ambientes.id')
                        ->join('horarios_disponibles', 'solicitudes_ambientes.horario_disponible_id', '=', 'horarios_disponibles.id')
                        ->join('ambientes', 'horarios_disponibles.ambiente_id', '=', 'ambientes.id')
                        ->orderBy('ambientes.capacidad', $sortDirection)
                        ->select('solicitud_status_changes.*');
                } elseif ($sortField == 'capacidadReserva') {
                    $query->join('solicitudes_ambientes', 'solicitud_status_changes.solicitud_ambiente_id', '=', 'solicitudes_ambientes.id')
                        ->orderBy('solicitudes_ambientes.capacidad', $sortDirection)
                        ->select('solicitud_status_changes.*');
                } elseif ($sortField == 'horario') {
                    $query->join('solicitudes_ambientes', 'solicitud_status_changes.solicitud_ambiente_id', '=', 'solicitudes_ambientes.id')
                        ->join('horarios_disponibles', 'solicitudes_ambientes.horario_disponible_id', '=', 'horarios_disponibles.id')
                        ->orderByRaw("CONCAT(horarios_disponibles.hora_inicio, ' - ', horarios_disponibles.hora_fin) $sortDirection")
                        ->select('solicitud_status_changes.*');
                } elseif ($sortField == 'fecha') {
                    $query->join('solicitudes_ambientes', 'solicitud_status_changes.solicitud_ambiente_id', '=', 'solicitudes_ambientes.id')
                        ->join('horarios_disponibles', 'solicitudes_ambientes.horario_disponible_id', '=', 'horarios_disponibles.id')
                        ->orderBy('horarios_disponibles.fecha', $sortDirection)
                        ->select('solicitud_status_changes.*');
                } else {
                    $query->orderBy($sortField, $sortDirection);
                }
            } else {
                return response()->json(['error' => 'Invalid sort direction'], 400);
            }
        }

        // Pagination
        $perPage = $request->input('perPage', 10);
        $statusChanges = $query->paginate($perPage);

        return SolicitudStatusChangeResource::collection($statusChanges);
    }

}