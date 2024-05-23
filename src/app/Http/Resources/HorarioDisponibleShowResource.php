<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HorarioDisponibleShowResource extends JsonResource
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
            'fecha' => $this->fecha,
            'ambiente' => $this->ambiente->nombre,
            'horario' => $this->hora_inicio . ' - ' . $this->hora_fin,
            'capacidad' => $this->ambiente->capacidad,
        ];
    }
}
