<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfil extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $table      = 'perfiles';
    protected $primaryKey = 'IdPerfil';
    protected $fillable   = [
        'IdPerfil',
        'Nombre',
        'isDefault'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
