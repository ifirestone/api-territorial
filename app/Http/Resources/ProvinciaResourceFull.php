<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProvinciaResourceFull extends JsonResource
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
            'nombre' => $this->name,
            'code' => $this->code,
            'identifier' => $this->identifier,
            'region_code' => $this->region_code,
            'municipios' => MunicipioResource::collection($this->municipios),
            'distritos' => DistritoResource::collection($this->distritos),
        ];
    }
}
