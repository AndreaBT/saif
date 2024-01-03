<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

//Añadimos la clase JWTSubject
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $primaryKey = 'IdUsuario';
    protected $fillable = [
        'IdEmpresa',
        'IdSucursal',
        'IdEmpleado',
        'IdPerfil',
        'IdPuesto',
        'IdPais',
        'IdEstado',
        'IdMunicipio',

        'Nombre',
        'ApellidoPat',
        'ApellidoMat',
        'NombreCompleto',
        'Correo',
        'Telefono',

        'username',
        'password',

        'Confirmado',
        'Disponible',
        'UsuarioApp',
        'Imagen',
        'UrlImg',
    ];

    /**
     * Los atributos que serán invisibles para la serialización.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function empleados(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Empleado::class, "IdEmpleado",'IdEmpleado');
    }

    public function perfil(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Perfil::class, "IdPerfil",'IdPerfil')->select(array('IdPerfil','Nombre'));
    }

    public function puesto(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Puesto::class, "IdPuesto",'IdPuesto')->select(array('IdPuesto','Nombre'));
    }

}
