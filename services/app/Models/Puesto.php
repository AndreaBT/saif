<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Puesto extends Authenticatable
{
    use  HasApiTokens, HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'IdPuesto';
    protected $fillable = [
        'IdSucursal',
        'Nombre',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
