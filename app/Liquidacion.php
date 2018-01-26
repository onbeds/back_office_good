<?php

namespace App;

use App\Entities\Tercero;
use App\LiquidacionTercero;
use App\LiquidacionDetalle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidaciones';
    protected $guarded = [];

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(LiquidacionDetalle::class, 'liquidacion_id');
    }

    public function liquidacion_tercero()
    {
        return $this->hasOne(LiquidacionTercero::class, 'liquidacion_id');
    }


    public static function createLiquidacion()
    {
        return self::create([
            'usuario_id' => currentUser()->id,
            'fecha_inicio' => Carbon::now()->subMonth(),
            'fecha_final' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'fecha_liquidacion' => Carbon::now()
        ]);
    }
}
