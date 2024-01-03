<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestablecerPassword extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'IdReset';
    protected $table      = 'password_resets';
    protected $fillable   = [
        'IdUsuario',
        'Correo',
        'MasterKey',
        'OperationTime'
    ];

}
