<?php

namespace App;
use App\Rule;
use Illuminate\Database\Eloquent\Model;

class RuleDetail extends Model
{
    protected $table = 'rules_details';
    protected $fillable = ['nivel', 'comision_puntos', 'vendedores_directos'];

    public function rule()
    {
        return $this->belongsTo(Rule::class, 'rule_id');
    }
}
