<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $usuarioArray = [
            'email' => $this->email,
        ];

        if ($this->rol === 'docente') {
            $docente = $this->docente;
            $usuarioArray['nombre'] = $docente->nombre;
            $usuarioArray['apellido'] = $docente->apellido;
        }

        return $usuarioArray;
    }
}
