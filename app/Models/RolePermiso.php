<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RolePermiso extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function permiso()
    {
        return $this->hasOne(Permiso::class, 'id', 'permiso_id');
    }
}