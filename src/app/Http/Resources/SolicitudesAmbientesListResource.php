<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudesAmbientesListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'docente' => new DocenteShowResource($this->docente),
            'horarioDisponible' => new HorarioDisponibleShowResource($this->horarioDisponible),
            'grupo' => new GrupoShowResource($this->grupo),
            'capacidad' => $this->capacidad,
            'estado' => $this->horarioDisponible["estado"],
            'tipoReserva' => $this->tipo_reserva,
            'docentes' => DocentesListResource::collection($this->docentes),
            'prioridad' => $this->prioridad,
            'fechaSolicitud' => $this->created_at->format('d-m-Y H:i:s')
        ];
    }
}
