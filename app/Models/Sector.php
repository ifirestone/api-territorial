<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeBuscar($query, $busqueda)
    {
        if (!empty($busqueda)) {
            return  $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
        }
    }

    public function scopeMunicipios($query, $municipios)
    {
        if (!empty($municipios)) {
            $data  =  explode(",", $municipios);
            return  $query->whereIn('municipio_id', $data);
        }
    }
}
