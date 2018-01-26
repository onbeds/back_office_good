<?php

namespace App;
use DB;
use App\Rule;
use App\Entities\Tercero;
use App\Http\Requests\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'tipos';

    public function tipos()
    {
        return $this->hasMany(Tipo::class, 'padre_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'padre_id');
    }



    public function terceros()
    {
        return $this->hasMany(Tercero::class, 'tipo_id');
    }

    public function rules()
    {
        return $this->hasMany(Rule::class, 'tipo_id');
    }


    public static function getTipos()
    {
        $tipos =DB::table('tipos')->orderBy('created_at', 'asc')->where('nombre', '!=', 'Terceros')->get();

        $send = collect($tipos);

        return Datatables::of($send )

            ->addColumn('id', function ($send) {
                return '<div>' . $send->id. '</div>';
            })
            ->addColumn('tipo', function ($send) {
                return '<div>' . ucfirst($send->nombre). '</div>';
            })
            ->addColumn('puntos_minimos', function ($send) {
                return '<div>' . number_format((int)$send->puntos_minimos) . '</div>';
            })
            ->addColumn('puntos_maximos', function ($send) {
                return '<div>' . number_format((int)$send->puntos_maximos) . '</div>';
            })
            ->addColumn('comision_maxima', function ($send) {
                return '<div>' . number_format((int)$send->comision_maxima) . '</div>';
            })
            ->addColumn('action', function ($send ) {
                return '<div><a href="/admin/tipos/'. $send->id .'/edit"  class="btn btn-danger btn-xs text-center">Editar</a></div>';
            })
            ->make(true);
    }

    public static function createTipo($tipo)
    {
        $create = new Tipo();
        $create->nombre = $tipo['nombre'];
        $create->puntos_minimos = $tipo['puntos_minimos'];
        $create->puntos_maximos = $tipo['puntos_maximos'];
        $create->comision_maxima = $tipo['comision_maxima'];
        $create->padre_id = 1;
        $create->save();
    }

    public static function editTipo($id)
    {
        return Tipo::findorFail($id);
    }

    public static function updateTipo($tipo, $id)
    {
       $update = Tipo::findOrFail($id);
       $update->puntos_minimos = $tipo['puntos_minimos'];
       $update->puntos_maximos = $tipo['puntos_maximos'];
       $update->comision_maxima = $tipo['comision_maxima'];
       $update->save();

    }

    public static function searchByName($name)
    {
        return Tipo::where('nombre', $name)->first();
    }
}
