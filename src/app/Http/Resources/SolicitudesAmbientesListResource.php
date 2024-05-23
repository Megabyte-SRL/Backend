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
            'estado' => $this->estado,
            'tipoReserva' => $this->tipoReserva,
            'docentes' => DocentesListResource::collection($this->docentes)
        ];
    }
}
