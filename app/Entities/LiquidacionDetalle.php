<?php
namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class LiquidacionDetalle extends Model {

	protected $table = 'liquidaciones_detalles';

	protected $fillable = [
		'liquidacion_id',
		'tercero_id',
		'hijo_id',
		'nivel',
		'order_id',
		'regla_detalle_id',
		'valor_comision',
	]; 

}
