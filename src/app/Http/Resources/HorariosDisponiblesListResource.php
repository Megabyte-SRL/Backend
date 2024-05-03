<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HorariosDisponiblesListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $estado = 'disponible';
        if ($this->solicitudesAmbientes->isNotEmpty()) {
            $estado = $this->solicitudesAmbientes->first()->estado;
        }

        return [
            'id' => $this->id,
            'fecha' => $this->fecha,
            'ambiente' => $this->ambiente->nombre,
            'horario' => $this->hora_inicio . ' - ' . $this->hora_fin,
            'capacidad' => $this->ambiente->capacidad,
            'estado' => $estado
        ];
    }
}
