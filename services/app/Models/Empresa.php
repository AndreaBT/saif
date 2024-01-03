<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Empresa extends Model{
    use HasFactory;
    use SoftDeletes;

    protected $table        ='empresas';
    protected $primaryKey   ='IdEmpresa';
    protected $fillable     =[
        'IdEmpresa',
        'NombreComercial',
        'RazonSocial',
        'Rfc',
        'Calle',
        'NoInt',
        'NoExt',
        'Colonia',
        'Cp',
        'Referencias',
        'Telefono',
        'Imagen'
    ];
}
    

