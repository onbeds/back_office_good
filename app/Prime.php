<?php

namespace App;

use App\Entities\Tercero;
use Illuminate\Database\Eloquent\Model;

class Prime extends Model
{
    protected $table = 'terceros_prime';

    protected $casts = [
        'log' => 'array'
    ];

    protected $guarded = [];

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }
}
