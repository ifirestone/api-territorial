<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResourceFull extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'  => $this->id,
            'nombre'  => $this->nombre,
            'descripcion'  => $this->descripcion,
            'modulos' => RoleModuloResource::collection($this->modulos),
            'permisos' => RolePermisoResource::collection($this->permisos),
        ];
    }
}