<?php

namespace App;

use App\Entities\Tercero;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'terceros_niveles';

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }
}
