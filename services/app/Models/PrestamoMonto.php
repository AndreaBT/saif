<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrestamoMonto extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $primaryKey = 'IdPrestamoMonto';
	protected $table = 'prestamosmontos';
	protected $fillable = [
		'Monto'
	];
}
