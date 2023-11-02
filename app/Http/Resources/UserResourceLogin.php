<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceLogin extends JsonResource
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
            'email'  => $this->email,
            'username' => $this->username,
            'activo'  => $this->activo ? true : false,
            'role'  => new RoleResource($this->role),
            'modulos' => RoleModuloResource::collection($this->role->modulos),
            'permisos' => RolePermisoResource::collection($this->role->permisos),
        ];
    }
}