<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Role extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function permisos()
    {
        return $this->hasMany(RolePermiso::class);
    }

    public function modulos()
    {
        return $this->hasMany(RoleModulo::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}