<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    use HasFactory;

    protected $table = 'folios';
    protected $primaryKey = 'IdFolio';
    protected $fillable = [
        'TipoFolio',
        'Serie',
        'Numero',
    ];
}
