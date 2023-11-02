<?php

namespace App\Models;

use App\Exceptions\NotActive;
use App\Exceptions\NotPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    //Scopes
    public function scopeEstado($query, $estado)
    {
        if (!empty($estado)) {
            if ($estado == 'activos') {
                return  $query->where('users.activo', true);
            }
            if ($estado == 'inactivos') {
                return  $query->where('users.activo', false);
            }
        }
    }

    public function scopeBuscar($query, $busqueda)
    {
        if (!empty($busqueda)) {
            return  $query->where('users.email', 'LIKE', '%' . $busqueda . '%')
                ->orWhere('users.username', 'LIKE', '%' . $busqueda . '%')
                ->orWhere('profiles.nombre', 'LIKE', '%' . $busqueda . '%')
                ->orWhere('roles.nombre', 'LIKE', '%' . $busqueda . '%');
        }
    }

    //PERMISSIONS
    public function hasModulo($modulo)
    {
        foreach (auth()->user()->role->modulos as $item) {
            if ($item->modulo->nombre == $modulo) {
                return true;
            }
        }
        throw new NotPermissions();
    }

    public function hasPermiso($permiso)
    {
        foreach (auth()->user()->role->permisos as $item) {
            if ($item->permiso->nombre == $permiso) {
                return true;
            }
        }
        throw new NotPermissions();
    }

    public function isActivo()
    {
        if (!auth()->user()->activo) {
            throw new NotActive();
        }
        return true;
    }
}