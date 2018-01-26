<?php

namespace App;

use App\Entities\Tercero;
use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    protected $table = 'tipos_clientes';

    public function terceros()
    {
        return $this->hasMany(Tercero::class, 'tipo_cliente_id');
    }

    public static function getTypesClients($search)
    {

        if ($search == null || $search == "") {
            return TipoCliente::select('id', 'nombre')->get();
        }

        if ($search != null && $search != "") {
            return TipoCliente::select('id', 'nombre')->where('nombre', 'like', '%' . $search . '%')->get();
        }

    }

}
