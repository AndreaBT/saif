<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelxPermiso extends Model {
    use HasFactory;

    protected $table      = 'panelesxpermisos';
    protected $primaryKey = 'IdPanelxPermiso';
    protected $fillable   = [
        'IdPanelxPermiso',
        'IdPanel',
        'IdPermiso'
    ];
    
}
