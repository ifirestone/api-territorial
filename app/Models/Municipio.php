<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeBuscar($query, $busqueda)
    {
        if (!empty($busqueda)) {
            return  $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
        }
    }

    public function scopeProvincias($query, $provincias)
    {
        if (!empty($provincias)) {
            $data  =  explode(",", $provincias);
            return  $query->whereIn('provincia_id', $data);
        }
    }
}
