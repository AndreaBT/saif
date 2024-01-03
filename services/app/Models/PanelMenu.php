<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanelMenu extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $table      = 'panelesmenus';
    protected $primaryKey = 'IdPanel';
    protected $fillable   = [
        'IdPanel',
        'Nombre',
        'TipoMenu',
        'IdMenu',
        'IdSubMenu',
        'IdApartado',
        'IdAsociado',
        'Clave',
        'Lugar',
    ];
}