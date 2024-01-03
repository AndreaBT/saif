<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilxMenu extends Model {
    use HasFactory;

    protected $table      = 'perfilesxmenus';
    protected $primaryKey = 'IdPerfilxMenu';
    protected $fillable   = [
        'IdPerfilxMenu',
        'IdPerfil',
        'IdPanel',
    ];
}