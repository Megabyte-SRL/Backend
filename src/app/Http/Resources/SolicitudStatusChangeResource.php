<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudStatusChangeResource extends JsonResource
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
            'estado_antiguo' => $this->estado_antiguo,
            'estado_nuevo' => $this->estado_nuevo,
            'fecha' => $this->fecha,
            'solicitud_ambiente' => [
                'id' => $this->solicitudAmbiente->id,
                'fechaSolicitud' => $this->solicitudAmbiente->fechaSolicitud,
                'capacidad' => $this->solicitudAmbiente->capacidad,
                'docente' => [
                    'id' => $this->solicitudAmbiente->docente->id,
                    'nombre' => $this->solicitudAmbiente->docente->nombre,
                    'apellido' => $this->solicitudAmbiente->docente->apellido,
                ],
                'horario_disponible' => [
                    'fecha' => $this->solicitudAmbiente->horarioDisponible->fecha,
                    'hora_inicio' => $this->solicitudAmbiente->horarioDisponible->hora_inicio,
                    'hora_fin' => $this->solicitudAmbiente->horarioDisponible->hora_fin,
                    'ambiente' => [
                        'nombre' => $this->solicitudAmbiente->horarioDisponible->ambiente->nombre,
                        'capacidad' => $this->solicitudAmbiente->horarioDisponible->ambiente->capacidad,
                    ],
                ],
                'grupo' => [
                    'id' => $this->solicitudAmbiente->grupo->id,
                    'nombre' => $this->solicitudAmbiente->grupo->nombre,
                    'materia' => [
                        'id' => $this->solicitudAmbiente->grupo->materia->id,
                        'nombre' => $this->solicitudAmbiente->grupo->materia->nombre,
                    ],
                ],
            ],
        ];
    }
}
