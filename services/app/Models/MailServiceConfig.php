<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailServiceConfig extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'IdMailServeConfig';
    protected $table      = 'mailserviceconfig';
    protected $fillable   = [
        "TipoServicio",
        "KeyService",
        "Driver",
        "Host",
        "Port",
        "Username",
        "Password",
        "Encryption",
        "Remitente",
        "NameApp",
        "Alias",
        "Destinatario",
    ];
    
}
