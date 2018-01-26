<?php

namespace App;

use App\Entities\Tercero;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'terceros_tiendas';

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }
}
