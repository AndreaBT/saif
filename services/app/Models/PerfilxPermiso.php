<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilxPermiso extends Model {
    use HasFactory;

    protected $table      = 'perfilesxpermisos';
    protected $primaryKey = 'IdPerfilxPermiso';
    protected $fillable   = [
        'IdPerfilxPermiso',
        'IdPerfil',
        'IdPanel',
        'IdPermiso',
    ];
}