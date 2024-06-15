<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarHorariosAmbienteRequest;
use App\Http\Resources\HorariosDisponiblesListResource;
use App\Models\HorarioDisponible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioDisponibleController extends Controller
{
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
     * Listamos todos los horarios disponibles.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $query = HorarioDisponible::with(['ambiente', 'solicitudesAmbientes']);

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('fecha', 'LIKE', "%{$search}%")
                    ->orWhere('hora_inicio', 'LIKE', "%{$search}%")
                    ->orWhere('hora_fin', 'LIKE', "%{$search}%")
                    ->orWhereHas('ambiente', function ($q) use ($search) {
                        $q->where('nombre', 'LIKE', "%{$search}%")
                          ->orWhere('capacidad', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Sorting
        if ($request->has('sortField') && $request->has('sortDirection')) {
            $sortField = $request->input('sortField');
            $sortDirection = $request->input('sortDirection');

            // Validate sort direction
            if (in_array(strtolower($sortDirection), ['asc', 'desc'])) {
                if ($sortField == 'ambiente') {
                    $query->join('ambientes', 'horarios_disponibles.ambiente_id', '=', 'ambientes.id')
                          ->orderBy('ambientes.nombre', $sortDirection)
                          ->select('horarios_disponibles.*');
                } elseif ($sortField == 'capacidad') {
                    $query->join('ambientes', 'horarios_disponibles.ambiente_id', '=', 'ambientes.id')
                            ->orderBy('ambientes.capacidad', $sortDirection)
                            ->select('horarios_disponibles.*');
                } elseif ($sortField == 'horario') {
                    $query->orderByRaw("CONCAT(hora_inicio, ' - ', hora_fin) $sortDirection");
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

            // Check if the filter is for a related table
            if ($key === 'ambiente') {
                $query->whereHas('ambiente', function ($q) use ($value) {
                    $q->where('nombre', $value);
                });
            } elseif ($key === 'capacidad') {
                // Adjust the query to join the related table and apply the filter
                $query->whereHas('ambiente', function ($q) use ($value) {
                    $q->where('capacidad', $value);
                });
            } else {
                $query->where($key, $value);
            }
        }

        // Pagination
        $perPage = $request->input('perPage', 10);
        $horarios = $query->paginate($perPage);

        return HorariosDisponiblesListResource::collection($horarios);
    }

    /**
     * Listamos todos los horarios disponibles
     *
     * @return \Illuminate\Http\Response
     */
    public function horariosDisponibles(Request $request)
    {
        $query = HorarioDisponible::with(['ambiente', 'solicitudesAmbientes'])
            ->whereDoesntHave('solicitudesAmbientes', function ($query) {
                $query->where('estado', '!=', 'disponible');
            });

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('fecha', 'LIKE', "%{$search}%")
                    ->orWhere('hora_inicio', 'LIKE', "%{$search}%")
                    ->orWhere('hora_fin', 'LIKE', "%{$search}%")
                    ->orWhereHas('ambiente', function ($q) use ($search) {
                        $q->where('nombre', 'LIKE', "%{$search}%")
                          ->orWhere('capacidad', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Sorting
        if ($request->has('sortField') && $request->has('sortDirection')) {
            $sortField = $request->input('sortField');
            $sortDirection = $request->input('sortDirection');

            // Validate sort direction
            if (in_array(strtolower($sortDirection), ['asc', 'desc'])) {
                if ($sortField == 'ambiente') {
                    $query->join('ambientes', 'horarios_disponibles.ambiente_id', '=', 'ambientes.id')
                          ->orderBy('ambientes.nombre', $sortDirection)
                          ->select('horarios_disponibles.*');
                } elseif ($sortField == 'capacidad') {
                    $query->join('ambientes', 'horarios_disponibles.ambiente_id', '=', 'ambientes.id')
                            ->orderBy('ambientes.capacidad', $sortDirection)
                            ->select('horarios_disponibles.*');
                } elseif ($sortField == 'horario') {
                    $query->orderByRaw("CONCAT(hora_inicio, ' - ', hora_fin) $sortDirection");
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

            // Check if the filter is for a related table
            if ($key === 'ambiente') {
                $query->whereHas('ambiente', function ($q) use ($value) {
                    $q->where('nombre', $value);
                });
            } elseif ($key === 'capacidad') {
                // Adjust the query to join the related table and apply the filter
                $query->whereHas('ambiente', function ($q) use ($value) {
                    $q->where('capacidad', $value);
                });
            } else {
                $query->where($key, $value);
            }
        }

        // Pagination
        $perPage = $request->input('perPage', 10);
        $horarios = $query->paginate($perPage);

        return HorariosDisponiblesListResource::collection($horarios);
    }

}
