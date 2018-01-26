<?php

namespace App;

use App\Liquidacion;
use App\Entities\Tercero;
use Illuminate\Database\Eloquent\Model;

class LiquidacionTercero extends Model
{
    protected $table = 'liquidaciones_terceros';
    protected $casts = [
        'giftcard_good' => 'array',
        'giftcard_mercando' => 'array'
    ];

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class, 'liquidacion_id');
    }

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }
}
