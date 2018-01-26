<?php

namespace App;

use DB;
use App\Tipo;
use App\Entities\Network;
use App\RuleDetail;
use App\Http\Requests\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';

    public function details()
    {
        return $this->hasMany(RuleDetail::class, 'rule_id', 'id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id');
    }

    public static function getRules()
    {
        $rules = DB::table('networks')
            ->join('rules', 'rules.network_id', '=', 'networks.id')
            ->join('tipos', 'tipos.id', '=', 'rules.tipo_id')
            ->join('rules_details', 'rules_details.rule_id', '=', 'rules.id')
            ->select('rules_details.id as id', 'networks.name as red', 'tipos.nombre as tipo', 'rules_details.nivel as nivel', 'rules_details.comision_puntos as puntos')
            ->get();

        $send = collect($rules);

        return Datatables::of($send )

            ->addColumn('id', function ($send) {
                return '<div>' . $send->id. '</div>';
            })
            ->addColumn('red', function ($send) {
                return '<div>' . $send->red. '</div>';
            })
            ->addColumn('tipo', function ($send) {
                return '<div>' . $send->tipo . '</div>';
            })
            ->addColumn('nivel', function ($send) {
                return '<div>' . $send->nivel . '</div>';
            })
            ->addColumn('puntos', function ($send) {
                return '<div>' . $send->puntos . '</div>';
            })
            ->addColumn('action', function ($send ) {
                return '<div><a href="/admin/rules/'. $send->id .'/edit"  class="btn btn-danger btn-xs text-center">Editar</a></div>';
            })
            ->make(true);
    }

    public static function editRule($id)
    {
        return RuleDetail::with('rule.tipo', 'rule.network')->find($id);
    }

    public static function storeRule($rule)
    {

        $r = new Rule();

        $r->network_id = (int)$rule['red'];
        $r->tipo_id = (int)$rule['tipo'];
        $r->save();

        $r->details()->create([
            'nivel' => (int)$rule['nivel'],
            'comision_puntos' => (int)$rule['comision_puntos'],
        ]);

    }

    public static function updateDetail($rule)
    {
        $detail = RuleDetail::findOrFail($rule['id']);

        $detail->nivel = $rule['nivel'];
        $detail->comision_puntos = $rule['comision_puntos'];
        $detail->save();
    }
}
