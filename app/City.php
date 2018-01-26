<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'ciudades';

    public static function getCities($search)
    {
        return City::select('id', 'nombre')->where('nombre', 'like', '%' . $search . '%')->get();
    }
}
