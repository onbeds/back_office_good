<?php

namespace App\Http\Controllers;

use DB;
use Mail;
use Session;
use Carbon\Carbon;
use App\Entities\Tercero;
use App\Liquidacion;
use App\LiquidacionTercero;
use App\Order;
use App\LiquidacionDetalle;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;


use Excel;

class LiquidacionesController extends Controller {

    public function get_liquidar() {        
        $liquidaciones = DB::table('liquidaciones')->select(DB::raw('DATE(fecha_final) AS fecha_final'))->orderByRaw('fecha_final DESC')->first();
        $fecha_final = '1992-12-12'; 
        if(count($liquidaciones) > 0){ $fecha_final = $liquidaciones->fecha_final; }
        return view('admin.liquidaciones.liquidar', compact('fecha_final'));
    }

    public function post_liquidar(Request $request) {

    if ($request->has('liquidar')){

        ini_set("memory_limit", "-1");

        $liquidar = new Liquidacion();
        $liquidar->usuario_id = currentUser()->id;
        $liquidar->fecha_inicio = $request->fecha_inicio;
        $liquidar->fecha_final = $request->fecha_final;
        $liquidar->fecha_liquidacion = Carbon::now();
        $liquidar->created_at = Carbon::now();
        $liquidar->updated_at = Carbon::now();
        $liquidar->save();

        $liquidacion_id = $liquidar->id;


        $id_primer_nivel = array();
        $id_dos_nivel = array();
        $id_tres_nivel = array();


        $id_primer_nivel_amparado = array();
        $id_dos_nivel_amparado = array();
        $id_tres_nivel_amparado = array();

        $insert_primer_nivel = array();
        $insert_segundo_nivel = array();
        $insert_tercer_nivel = array();

        $level_uno = 0;
        $level_dos = 0;
        $level_tres = 0;

        $my_points = 0;

        $gente_nivel_1 = array();
        $count_add=0;
        $vendedores_liquidados = array();
        $id_vendedores = array();
        $id_vendedores_tipo = array();


        $puntos = DB::raw("(select fpl_dir(t.id::integer,0)) as puntos_propios");

//->where('t.id', 41)
//->limit(41)
    $vendedores = DB::table('terceros as t')->where('t.tipo_cliente_id', 83)->where('t.state', true)
  //    ->where('t.id', 53)
   // ->limit(41)
    ->select('t.id', 't.tipo_id', $puntos)->orderByRaw('id ASC')->get();

    foreach ($vendedores as $value_vendedor) {
        
        if($value_vendedor->puntos_propios >= 1){
            
            $points_level_1 = 0;
            $points_level_2 = 0;
            $points_level_3 = 0;

            $comision_valor_1 = 0;
            $comision_valor_2 = 0;
            $comision_valor_3 = 0;

            $id_detalle_1 = 0;
            $id_detalle_2 = 0;
            $id_detalle_3 = 0;


            /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
            /*                                                    reglas   inicio     ------------------------                                             */
            /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
            $rules = DB::table('rules')->where('tipo_id', $value_vendedor->tipo_id)->join('rules_details', 'rules_details.rule_id', '=', 'rules.id')
            ->select('nivel','comision_puntos','rules_details.id')->get();
            foreach ($rules as $rules_details_value) { 
                    if($rules_details_value->nivel == 1){
                        $comision_valor_1 = $rules_details_value->comision_puntos;
                        $id_detalle_1 = $rules_details_value->id;
                    }
                    if($rules_details_value->nivel == 2){
                        $comision_valor_2 = $rules_details_value->comision_puntos;
                        $id_detalle_2 = $rules_details_value->id;
                    }
                    if($rules_details_value->nivel == 3){
                        $comision_valor_3 = $rules_details_value->comision_puntos;
                        $id_detalle_3 = $rules_details_value->id;
                    } 
            }
            
            $id_vendedores[] = array($value_vendedor->id);
            $id_vendedores_tipo[$value_vendedor->id] = array('comision_valor_1' => $comision_valor_1, 'id_detalle_1' => $id_detalle_1, 
                                                            'comision_valor_2' => $comision_valor_2, 'id_detalle_2' => $id_detalle_2, 
                                                            'comision_valor_3' => $comision_valor_3, 'id_detalle_3' => $id_detalle_3);
            /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
            /*                                                    reglas     fin                                                                           */
            /*   ----------------------------------------------------------------------------------------------------------------------------------------  */       
        }
    }


            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */
            /*                                                     terceros y ordenes del nivel uno con sus amparados    inicio  --------------------------                  */
            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */

            $uno = DB::table('terceros as t')
                ->join('terceros_networks as tk', 'tk.customer_id', '=', 't.id')
                ->join('terceros as t2', 't2.id', '=', 'tk.customer_id')
                ->leftjoin('orders', 'orders.tercero_id', '=', 't2.id')
                ->whereIn('tk.padre_id', $id_vendedores)->where('t.state', true)->where('t2.state', true)->where('t2.tipo_cliente_id', '<>', 85)
                //->whereRaw("date(orders.created_at) >= '".$request->fecha_inicio."'")->whereRaw("date(orders.created_at) <= '".$request->fecha_final."'") 
                ->select('tk.padre_id as padre', 't2.id', 't2.email', 't2.nombres', 't2.apellidos', 't2.tipo_cliente_id','points', 'orders.id as orden_id',
                    'orders.financial_status', 'orders.cancelled_at', 'orders.comisionada', 'orders.liquidacion_id', DB::raw('DATE(orders.created_at) as created_at'), 
                    DB::raw('(select count(*) as total from orders 
                    	       inner join terceros_networks on orders.tercero_id = terceros_networks.customer_id 
                    	       inner join terceros on orders.tercero_id = terceros.id 
                    	      where terceros_networks.padre_id = t2.id and terceros.tipo_cliente_id = 85) as total'))->get();

            if (count($uno) > 0) {
                $level_uno = $level_uno + count($uno);
                foreach ($uno as $n) {

                        $id_detalle_1 = $id_vendedores_tipo[$n->padre]['id_detalle_1'];
                        $comision_valor_1 = $id_vendedores_tipo[$n->padre]['comision_valor_1'];
                    
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
                    /*                                                     ordenes del nivel uno con sus amparados   inicio     ------------------------           */
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */

                    $points_level_vendedor_1 = 0;
                    
                    if($n->total > 0){ 
                       $uno_amparados_total = 0;
                       $uno_amparados = DB::table('terceros as t')->join('terceros_networks as tk', 'tk.customer_id', '=', 't.id')->where('tk.padre_id', $n->id)
                       ->where('t.state', true)->where('t.tipo_cliente_id', 85)
                       ->whereRaw("date(orders.created_at) >= '".$request->fecha_inicio."'")->whereRaw("date(orders.created_at) <= '".$request->fecha_final."'") 
                       ->join('orders', 'orders.tercero_id', '=', 't.id')
                           ->where('financial_status', 'paid')
                           ->where('cancelled_at', null)
                           ->where('comisionada', null)
                           ->where('liquidacion_id', null)
                       ->select('t.id', 't.email', 't.nombres', 't.apellidos', 't.tipo_cliente_id', 'points', 'orders.id as orden_id','padre_id')->get();
                        foreach ($uno_amparados as $uno_amparados_value) {

                               DB::table('liquidaciones_detalles')->insert([
                               'liquidacion_id' => $liquidacion_id,
                               'tercero_id' => $n->padre,
                               'hijo_id' => $uno_amparados_value->padre_id,
                               'nivel' => 1,
                               'order_id' => $uno_amparados_value->orden_id,
                               'regla_detalle_id' => $id_detalle_1,
                               'valor_comision' => ($comision_valor_1 * $uno_amparados_value->points),
                               'puntos' => ($uno_amparados_value->points),
                               'comision_puntos' => ($comision_valor_1),
                               'created_at' => Carbon::now(),
                               'updated_at' => Carbon::now()
                               ]);

                               $id_primer_nivel_amparado[] = array($uno_amparados_value->orden_id);
                        }
                                DB::table('orders')->whereIn('id', $id_primer_nivel_amparado)->update(['comisionada' => Carbon::now(), 'liquidacion_id' => $liquidacion_id]);
                    }  
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
                    /*                                                     ordenes del nivel uno con sus amparados    fin                                          */
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */

                    if($n->financial_status == 'paid' && $n->cancelled_at == '' && $n->comisionada == '' && $n->liquidacion_id == '' && $n->created_at >= $request->fecha_inicio && $n->created_at <= $request->fecha_final){

                    $id_primer_nivel[] = array($n->orden_id);

                        $points_level_1 +=  $n->points;
                        $points_level_vendedor_1 += $n->points;

                        $insert_primer_nivel[] =  array(
                            'liquidacion_id' => $liquidacion_id,
                            'tercero_id' => $n->padre,
                            'hijo_id' => $n->id,
                            'nivel' => 1,
                            'order_id' => $n->orden_id,
                            'regla_detalle_id' => $id_detalle_1,
                            'valor_comision' => ($comision_valor_1 * $n->points),
                            'puntos' => ($n->points),
                            'comision_puntos' => ($comision_valor_1),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        );
 
                    }
                    //  $gente_nivel_1[] = array('id' => $n->id);
                }
            }
 
            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */
            /*                                                     terceros y ordenes del nivel uno con sus amparados    fin                                                 */
            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */

            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */
            /*                                                     terceros y ordenes del nivel dos con sus amparados    inicio ------------------                          */
            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */

            $dos = DB::table('terceros as t')
                ->join('terceros_networks as tk', 'tk.padre_id', '=', 't.id')
                ->join('terceros as t2', 't2.id', '=', 'tk.customer_id')
                ->join('terceros_networks as tk2', 'tk2.padre_id', '=', 't2.id')
                ->join('terceros as t3', 't3.id', '=', 'tk2.customer_id')
                ->leftjoin('orders', 'orders.tercero_id', '=', 't3.id')
                ->whereIn('t.id', $id_vendedores)->where('t.state', true)->where('t3.state', true)->where('t3.tipo_cliente_id', '<>', 85)
                //->whereRaw("date(orders.created_at) >= '".$request->fecha_inicio."'")->whereRaw("date(orders.created_at) <= '".$request->fecha_final."'") 
                ->select('t.id as padre', 't3.id', 't3.email', 't3.nombres', 't3.apellidos', 't3.tipo_cliente_id','points', 'orders.id as orden_id',
                    'orders.financial_status', 'orders.cancelled_at', 'orders.comisionada', 'orders.liquidacion_id', DB::raw('DATE(orders.created_at) as created_at'), 
                    DB::raw('(select count(*) as total from orders 
                    	       inner join terceros_networks on orders.tercero_id = terceros_networks.customer_id 
                    	       inner join terceros on orders.tercero_id = terceros.id 
                    	      where terceros_networks.padre_id = t3.id and terceros.tipo_cliente_id = 85) as total'))->get();

            if (count($dos) > 0) {

                $level_dos = $level_dos + count($dos);
                $gente_nivel_2 = array();
                $count_add=0;
                foreach ($dos as $d) {  $count_add++;

                        $id_detalle_2 = $id_vendedores_tipo[$d->padre]['id_detalle_2'];
                        $comision_valor_2 = $id_vendedores_tipo[$d->padre]['comision_valor_2'];

                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
                    /*                                                     ordenes del nivel dos con sus amparados   inicio     ------------------------           */
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */

                    $points_level_vendedor_2 = 0;

                    if($d->total > 0){ 
                       $dos_amparados_total = 0;
                       $dos_amparados = DB::table('terceros as t')->join('terceros_networks as tk', 'tk.customer_id', '=', 't.id')->where('tk.padre_id', $d->id)
                       ->where('t.state', true)->where('t.tipo_cliente_id', 85)
                       ->whereRaw("date(orders.created_at) >= '".$request->fecha_inicio."'")->whereRaw("date(orders.created_at) <= '".$request->fecha_final."'") 
                       ->join('orders', 'orders.tercero_id', '=', 't.id')
                           ->where('financial_status', 'paid')
                           ->where('cancelled_at', null)
                           ->where('comisionada', null)
                           ->where('liquidacion_id', null)
                       ->select('t.id', 't.email', 't.nombres', 't.apellidos', 't.tipo_cliente_id', 'points', 'orders.id as orden_id','padre_id')->get();
                        foreach ($dos_amparados as $dos_amparados_value) {

                                DB::table('liquidaciones_detalles')->insert([
                                'liquidacion_id' => $liquidacion_id,
                                'tercero_id' => $d->id,
                                'hijo_id' => $dos_amparados_value->padre_id,
                                'nivel' => 2,
                                'order_id' => $dos_amparados_value->orden_id,
                                'regla_detalle_id' => $id_detalle_2,
                                'valor_comision' => ($comision_valor_2 * $dos_amparados_value->points),
                                'puntos' => ($dos_amparados_value->points),
                                'comision_puntos' => ($comision_valor_2),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                                ]);

                               $id_dos_nivel_amparado[] = array($dos_amparados_value->orden_id);
                        }
                             DB::table('orders')->whereIn('id', $id_dos_nivel_amparado)->update(['comisionada' => Carbon::now(), 'liquidacion_id' => $liquidacion_id]);
                    }  
                        
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
                    /*                                                     ordenes del nivel dos con sus amparados    fin                                          */
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */

                    if($d->financial_status == 'paid' && $d->cancelled_at == '' && $d->comisionada == '' && $d->liquidacion_id == '' && $d->created_at >= $request->fecha_inicio && $d->created_at <= $request->fecha_final){

                    $id_dos_nivel[] = array($d->orden_id);

                        $points_level_2 += $d->points;
                        $points_level_vendedor_2 += $d->points;
                        
                        $insert_segundo_nivel[] =  array(
                            'liquidacion_id' => $liquidacion_id,
                            'tercero_id' => $d->padre,
                            'hijo_id' => $d->id,
                            'nivel' => 2,
                            'order_id' => $d->orden_id,
                            'regla_detalle_id' => $id_detalle_2,
                            'valor_comision' => ($comision_valor_2 * $d->points),
                            'puntos' => ($d->points),
                            'comision_puntos' => ($comision_valor_2),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        );

                    }
                    //  $gente_nivel_2[] = array('nombre' => $d->id.'-'.$d->nombres.'-'.$d->apellidos.'-'.$d->email.'-'.$d->tipo_cliente_id.' amparados: '.$count_add.' puntos: '.$points_level_vendedor_2.'<br>sd');
                }
            }

            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */
            /*                                                     terceros y ordenes del nivel dos con sus amparados    fin                                                 */
            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */

            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */
            /*                                                     terceros y ordenes del nivel tres con sus amparados    inicio ------------------                          */
            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */

            $tres = DB::table('terceros as t')
                ->join('terceros_networks as tk', 'tk.padre_id', '=', 't.id')
                ->join('terceros as t2', 't2.id', '=', 'tk.customer_id')
                ->join('terceros_networks as tk2', 'tk2.padre_id', '=', 't2.id')
                ->join('terceros as t3', 't3.id', '=', 'tk2.customer_id')
                ->join('terceros_networks as tk3', 'tk3.padre_id', '=', 't3.id')
                ->join('terceros as t4', 't4.id', '=', 'tk3.customer_id')
                ->leftjoin('orders', 'orders.tercero_id', '=', 't4.id')
                ->whereIn('t.id', $id_vendedores)->where('t.state', true)->where('t4.state', true)->where('t4.tipo_cliente_id', '<>', 85)
                //->whereRaw("date(orders.created_at) >= '".$request->fecha_inicio."'")->whereRaw("date(orders.created_at) <= '".$request->fecha_final."'") 
                ->select('t.id as padre', 't4.id', 't4.email', 't4.nombres', 't4.apellidos', 't4.tipo_cliente_id','points', 'orders.id as orden_id',
                    'orders.financial_status', 'orders.cancelled_at', 'orders.comisionada', 'orders.liquidacion_id', DB::raw('DATE(orders.created_at) as created_at'), 
                    DB::raw('(select count(*) as total from orders 
                    	       inner join terceros_networks on orders.tercero_id = terceros_networks.customer_id 
                    	       inner join terceros on orders.tercero_id = terceros.id 
                    	      where terceros_networks.padre_id = t4.id and terceros.tipo_cliente_id = 85) as total'))->get();

            if (count($tres) > 0) {

                $level_tres = $level_tres + count($tres);
                $gente_nivel_3 = array();

                foreach ($tres as $t) {

                        $id_detalle_3 = $id_vendedores_tipo[$t->padre]['id_detalle_3'];
                        $comision_valor_3 = $id_vendedores_tipo[$t->padre]['comision_valor_3'];
                        
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
                    /*                                                     ordenes del nivel tres con sus amparados   inicio     ------------------------          */
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */

                    $points_level_vendedor_3 = 0;

                    if($t->total > 0){ 
                       $tres_amparados_total = 0;
                       $tres_amparados = DB::table('terceros as t')->join('terceros_networks as tk', 'tk.customer_id', '=', 't.id')->where('tk.padre_id', $t->id)
                       ->where('t.state', true)->where('t.tipo_cliente_id', 85)
                       ->whereRaw("date(orders.created_at) >= '".$request->fecha_inicio."'")->whereRaw("date(orders.created_at) <= '".$request->fecha_final."'") 
                       ->join('orders', 'orders.tercero_id', '=', 't.id')
                           ->where('financial_status', 'paid')
                           ->where('cancelled_at', null)
                           ->where('comisionada', null)
                           ->where('liquidacion_id', null)
                       ->select('t.id', 't.email', 't.nombres', 't.apellidos', 't.tipo_cliente_id', 'points', 'orders.id as orden_id','padre_id')->get();
                        foreach ($tres_amparados as $tres_amparados_value) {

                                DB::table('liquidaciones_detalles')->insert([
                                'liquidacion_id' => $liquidacion_id,
                                'tercero_id' => $t->id,
                                'hijo_id' => $tres_amparados_value->padre_id,
                                'nivel' => 3,
                                'order_id' => $tres_amparados_value->orden_id,
                                'regla_detalle_id' => $id_detalle_3,
                                'valor_comision' => ($comision_valor_3 * $tres_amparados_value->points),
                                'puntos' => ($tres_amparados_value->points),
                                'comision_puntos' => ($comision_valor_3),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                                ]);

                               $id_tres_nivel_amparado[] = array($tres_amparados_value->orden_id);
                        }
                            DB::table('orders')->whereIn('id', $id_tres_nivel_amparado)->update(['comisionada' => Carbon::now(), 'liquidacion_id' => $liquidacion_id]);
                    }  

                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */
                    /*                                                     ordenes del nivel tres con sus amparados    fin                                         */
                    /*   ----------------------------------------------------------------------------------------------------------------------------------------  */

                    if($t->financial_status == 'paid' && $t->cancelled_at == '' && $t->comisionada == '' && $t->liquidacion_id == '' && $t->created_at >= $request->fecha_inicio && $t->created_at <= $request->fecha_final){

                    $id_tres_nivel[] = array($t->orden_id);
                        $points_level_3 += $t->points;
                        $points_level_vendedor_3 += $t->points;
 
                        $insert_tercer_nivel[] =  array(
                            'liquidacion_id' => $liquidacion_id,
                            'tercero_id' => $t->padre,
                            'hijo_id' => $t->id,
                            'nivel' => 3,
                            'order_id' => $t->orden_id,
                            'regla_detalle_id' => $id_detalle_3,
                            'valor_comision' => ($comision_valor_3 * $t->points),
                            'puntos' => ($t->points),
                            'comision_puntos' => ($comision_valor_3),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        );

                    }
                    //  $gente_nivel_3[] = array('nombre' => $t->id.'-'.$t->nombres.'-'.$t->apellidos.'-'.$t->email.'-'.$t->tipo_cliente_id.' amparados: '.$tres_amparados_total.' puntos: '.$points_level_vendedor_3.'<br>');
                }
            }


            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */
            /*                                                     terceros y ordenes del nivel tres con sus amparados    fin                                         */
            /*   ----------------------------------------------------------------------------------------------------------------------------------------------------------  */

            //echo $value_vendedor->id.' - puntos: '.$points_level_1.' - comision: '.$comision_valor_1.' - puntos: '.$points_level_2.' - comision: '.$comision_valor_2.' - puntos:  '.$points_level_3.' - comision: '.$comision_valor_3.'<br>';


        DB::table('liquidaciones_detalles')->insert($insert_primer_nivel);
        DB::table('liquidaciones_detalles')->insert($insert_segundo_nivel);
        DB::table('liquidaciones_detalles')->insert($insert_tercer_nivel); 

        DB::table('orders')->whereIn('id', $id_primer_nivel)->update(['comisionada' => Carbon::now(), 'liquidacion_id' => $liquidacion_id]);
        DB::table('orders')->whereIn('id', $id_dos_nivel)->update(['comisionada' => Carbon::now(), 'liquidacion_id' => $liquidacion_id]);
        DB::table('orders')->whereIn('id', $id_tres_nivel)->update(['comisionada' => Carbon::now(), 'liquidacion_id' => $liquidacion_id]);

        $fecha_hoy = Carbon::now();
        $insert_liquidacion_tercero = array();

        $liquidaciones_detalles = DB::table('liquidaciones_detalles as ld')
        ->join('terceros as t', 't.id', '=', 'ld.tercero_id')
        ->join('tipos as t2', 't2.id', '=', 't.tipo_id')
        ->where('ld.liquidacion_id', $liquidacion_id)
        ->select('ld.tercero_id', DB::raw('sum(ld.valor_comision) as valor_comision'), DB::raw("(select count(*) from terceros_prime where '".$fecha_hoy."' <= fecha_final and terceros_prime.tercero_id = ld.tercero_id ) as prime"), 't2.nombre', 't2.id', 't2.comision_maxima','ld.liquidacion_id')
        ->groupBy('ld.tercero_id', 't.identificacion', 't.nombres', 't.apellidos', 't.email', 't.telefono', 't2.nombre', 't2.id', 'ld.liquidacion_id')
        ->get();

        $parametros = DB::table('parametros')->select('rete_fuente','rete_ica','prime','prime_iva','transferencia','extracto','administrativo')->where('id', 1)->first();

        foreach ($liquidaciones_detalles as $value) {
             
             $valor_comision = 0; $valor_comision_descuento =  0;  $saldo = 0;  $saldo_paga = 0;  $descuentos = 0;  $saldo_favor = 0;   $tipo_pendiente_id = 0;

                        if($value->valor_comision > $value->comision_maxima){ 
                            if($value->comision_maxima != 0){
                                $valor_comision = $value->comision_maxima; 
                            }
                            else{
                                $valor_comision = $value->valor_comision; 
                            }
                        }
                        else{   
                    	    $valor_comision = $value->valor_comision;    
                        }
                        
                        if($value->prime >= 1){
                            $prime = $parametros->prime;
                            $prime_iva = round($parametros->prime*$parametros->prime_iva);
                        }
                        else {
                            $prime = 0;
                            $prime_iva = 0;
                        }

                            $valor_comision_descuento =  0;
                            $saldo_anterior = DB::table('liquidaciones_terceros')->select(DB::raw('sum(saldo) as saldo_total'))
                            ->where('tercero_id', $value->tercero_id)->where('saldo_paga', 1)->first();
                            if(count($saldo_anterior)>0){
                                $saldo_favor = $saldo_anterior->saldo_total;
                            }

                            $descuentos = ($parametros->administrativo + $parametros->extracto + $parametros->transferencia);
                            $descuentos_iva = ($parametros->administrativo + $parametros->extracto + $parametros->transferencia) * 0.19;
                            $valor_comision_descuento = $valor_comision - (round($valor_comision * $parametros->rete_fuente) + round($valor_comision * $parametros->rete_ica)) - $prime - $prime_iva;

                            if(($saldo_favor + $valor_comision_descuento) > $descuentos){
                                $valor_comision_descuento = ($saldo_favor + $valor_comision_descuento) - $descuentos - $descuentos_iva;
                                DB::table('liquidaciones_terceros')->where('tercero_id', $value->tercero_id)->update(['saldo_paga' => 0]);
                                $tipo_pendiente_id = 89;
                            } else{
                                $saldo_paga = 1;
                                $saldo = round(($valor_comision - (($valor_comision * $parametros->rete_fuente) + ($valor_comision * $parametros->rete_ica))) - $prime - $prime_iva);
                                $tipo_pendiente_id = 90;
                            }     
                        
                        $insert_liquidacion_tercero[] = array(
                            'liquidacion_id' => $value->liquidacion_id,
                            'tercero_id' => $value->tercero_id,
                            'valor_comision' => $value->valor_comision,
                            'valor_comision_paga' => $valor_comision_descuento,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),

                            'rete_fuente' => round($valor_comision * $parametros->rete_fuente),
                            'rete_ica' => round($valor_comision * $parametros->rete_ica),
                            'prime' => $prime,
                            'prime_iva' => $prime_iva,
                            'transferencia' => $parametros->transferencia,
                            'extracto' => $parametros->extracto,
                            'administrativo' => $parametros->administrativo,
                            'virtual' => $valor_comision_descuento * 0.30,
                            'giro' => $valor_comision_descuento * 0.70,

                            'saldo' => $saldo,
                            'saldo_paga' => $saldo_paga,

                            'estado_id' => 88,
                            'tipo_pendiente_id' => $tipo_pendiente_id,
                        );  

        }

        DB::table('liquidaciones_terceros')->insert($insert_liquidacion_tercero);
    } 

            Session::flash('flash_msg', 'La liquidación No '.$liquidacion_id.' se genero correctamente');
            Session::flash('id', $liquidacion_id);
            return redirect()->action('LiquidacionesController@liquidaciones_general');
    }

    public function liquidaciones_general() {
        return view('admin.liquidaciones.liquidaciones_general');
    }

    public function liquidaciones_datos() {

        $liquidaciones = DB::table('liquidaciones')
                ->select('liquidaciones.id as liqui_id','nombres', 'apellidos', DB::raw("DATE(fecha_inicio) AS fechainicio"), DB::raw("DATE(fecha_final) AS fechafinal"),'fecha_liquidacion')
                ->join('terceros', 'terceros.id', '=', 'liquidaciones.usuario_id')
                ->get();

        $send = collect($liquidaciones);

        return Datatables::of($send)
                        ->addColumn('id', function ($send) {
                            return '<div align=left>' . $send->liqui_id . '</div>';
                        })
                        ->addColumn('nombres', function ($send) {
                            return '<div align=left>' . $send->nombres.' '.$send->apellidos. '</div>';
                        })
                        ->addColumn('fecha_inicio', function ($send) {
                            return '<div align=left>' . $send->fechainicio . '</div>';
                        })
                        ->addColumn('fecha_final', function ($send) {
                            return '<div align=left>' . $send->fechafinal . '</div>';
                        })
                        ->addColumn('fecha_liquidacion', function ($send) {
                            return '<div align=left>' . $send->fecha_liquidacion . '</div>';
                        })
                        ->addColumn('excel', function ($send) {
                            return '<span align=left><a href="' . route('liquidacion.detalles_excel', $send->liqui_id) . '" target="_blank" class="btn btn-primary btn-xs"> Excel </a>
                                    <a href="' . route('liquidacion.liquidaciones_terceros_estados', $send->liqui_id) . '" class="btn btn-success btn-xs"> Cambiar estado </a></span> ';
                        })
                        ->make(true);
    }

    public function liquidaciones_extracto_comisiones($id=0) {
     //currentUser()->id
    	$usuario = currentUser()->id;
    	$liquidaciones = DB::table('liquidaciones')->select('fecha_liquidacion')->where('liquidaciones.id', $id)->first();
    	$liquidaciones_terceros = DB::table('liquidaciones_terceros')->select('estado_id', 'valor_comision_paga', 'rete_fuente','rete_ica','prime','prime_iva','transferencia','extracto','administrativo')->where('tercero_id', $usuario)->first();
        $parametros = DB::table('parametros')->select('rete_fuente','rete_ica','prime','prime_iva','transferencia','extracto','administrativo')->where('id', 1)->first();

        $mes = strtotime($liquidaciones->fecha_liquidacion);
        $mes = date("m", $mes);

    	$liquidaciones_detalles = $this->liquidaciones_extracto_comisiones_datos($usuario, $id);
    	$mes = $this->nombremes($mes);

        return view('admin.liquidaciones.extracto_comisiones', compact('id','liquidaciones_detalles','mes','parametros','liquidaciones_terceros'));
        
    }

    public function nombremes($mes){        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
    	return $meses[$mes-1];
    } 

    public function liquidaciones_extracto_comisiones_datos($id=0, $liquidacion_id=0) {

        $liquidaciones = DB::table('liquidaciones_detalles')
                ->select('nombres','apellidos', 'name', 'puntos', 'valor_comision','orders.created_at')
                ->where('liquidaciones_detalles.tercero_id', $id)
                ->where('liquidaciones_detalles.liquidacion_id', $liquidacion_id)
                ->join('terceros', 'terceros.id', '=', 'liquidaciones_detalles.hijo_id')
                ->join('orders', 'orders.id', '=', 'liquidaciones_detalles.order_id')
                ->orderByRaw('valor_comision DESC')
                ->get();

        return $liquidaciones;
/*
        $send = collect($liquidaciones);

        return Datatables::of($send)
                        ->addColumn('nombres', function ($send) {
                            return '<div align=left>' . $send->nombres . '</div>';
                        })
                        ->addColumn('apellidos', function ($send) {
                            return '<div align=left>' . $send->apellidos. '</div>';
                        })
                        ->addColumn('name', function ($send) {
                            return '<div align=left>' . $send->name . '</div>';
                        })
                        ->addColumn('puntos', function ($send) {
                            return '<div align=left>' . $send->puntos . '</div>';
                        })
                        ->addColumn('valor_comision', function ($send) {
                            return '<div align=left>' . number_format($send->valor_comision) . '</div>';
                        })
                        ->make(true);
 */
    }

    public function liquidaciones_terceros_estados($id=0) {
     //currentUser()->id 
        $parametros = DB::table('parametros')->select('rete_fuente','rete_ica','prime','prime_iva','transferencia','extracto','administrativo')->where('id', 1)->first();

        return view('admin.liquidaciones.liquidaciones_tercero_estado', compact('id','liquidaciones_detalles','mes','parametros'));
    }

    public function liquidaciones_cambiar_estado(Request $request) {
        if ($request->has('id')){ 

            $liquidaciones = DB::table('liquidaciones_terceros')
                ->select('valor_comision','rete_fuente', 'rete_ica', 'prime', 'prime_iva','valor_comision_paga')
                ->where('id', $request->id) 
                ->first();  
 
                $saldo = round($liquidaciones->valor_comision - $liquidaciones->rete_fuente - $liquidaciones->rete_ica - $liquidaciones->prime - $liquidaciones->prime_iva);  

        	if($request->tipo == "87"){ 
                if($request->valor == 87){   $saldo = 0;  $saldo_paga = 0;  }else{   $saldo = $saldo;  $saldo_paga = 1;   }
                DB::table('liquidaciones_terceros')->where('id', $request->id)->update(['estado_id' => $request->valor, 'saldo' => $saldo, 'saldo_paga' => $saldo_paga]); 
        	}
            else{
                $saldo = $saldo;  $saldo_paga = 1; 
                DB::table('liquidaciones_terceros')->where('id', $request->id)->update(['estado_id' => $request->tipo, 'tipo_pendiente_id' => $request->valor, 'saldo' => $saldo, 'saldo_paga' => $saldo_paga]); 
        	}
        } 
            return response()->json(['val' => 'bien'], 200);
    }

    public function liquidaciones_terceros_estados_datos($id=0) {

        $liquidaciones = DB::table('liquidaciones_terceros')
        ->where('liquidaciones_terceros.liquidacion_id', $id) 
        ->join('terceros', 'terceros.id', '=', 'liquidaciones_terceros.tercero_id')
        ->select('liquidaciones_terceros.id as liquitercero_id','tercero_id','identificacion', 'nombres', 'apellidos', 'telefono', 'email', 'valor_comision_paga','estado_id', 'tipo_pendiente_id', 'prime')
        ->orderByRaw('liquidaciones_terceros.valor_comision_paga ASC')->get();
     
        $tipos_estado_comision = DB::table('tipos')->where('padre_id', 86)->select('nombre','id')->orderByRaw('id ASC')->get();     
        $tipos_estado_pendiente = DB::table('tipos')->where('padre_id', 88)->select('nombre','id')->orderByRaw('id ASC')->get();   

        $datos = array();
        foreach ($liquidaciones as $send) {
            $select_1 = ''; $select_2 = '';
 
            		$prime = '';
                    if ($send->prime != '0') {
                    	 $prime = 'Si';
                    }
                    else{
                        $prime = 'No';
                    }
            
            $select_1 = '<select class="tercero_tipo_'.$send->liquitercero_id.' form-control" onchange="cambio_estado('.$send->liquitercero_id.', this.options[this.selectedIndex].value, 87, this.options[this.selectedIndex].text)" id="'.$send->liquitercero_id.'">';
            foreach ($tipos_estado_comision as $value) {
             	$seleted = '';
             	if($send->estado_id == $value->id){  $seleted = 'selected';  }
                $select_1 .= '<option value="'.$value->id.'" '.$seleted.'>'.$value->nombre.'</option>';
            }
            $select_1 .= '</select>';

            $select_2 = '<select class="tercero_pendiente_'.$send->liquitercero_id.' pendiente form-control" onchange="cambio_estado('.$send->liquitercero_id.', this.options[this.selectedIndex].value, 88, this.options[this.selectedIndex].text)" id="'.$send->liquitercero_id.'">';
            foreach ($tipos_estado_pendiente as $value) {
             	$seleted = '';
             	if($send->tipo_pendiente_id == $value->id){  $seleted = 'selected';  }
                $select_2 .= '<option value="'.$value->id.'" '.$seleted.'>'.$value->nombre.'</option>';
            }
            $select_2 .= '</select>';
/*
            array_push($datos, array('identificacion' => $send->identificacion,  'nombres' => $send->nombres, ));
*/
        	$datos[] = array('identificacion' => $send->identificacion, 
                              'nombres' => $send->nombres, 
                              'apellidos' => $send->apellidos, 
                              'telefono' => $send->telefono, 
                              'email' => $send->email, 
                              'valor_comision_paga' => $send->valor_comision_paga, 
                              'estado' => $select_1, 
                              'estado_pendiente' => $select_2, 
                              'prime' => $prime, 
                              'estado_id' => $send->estado_id, 
                              'liquitercero_id' => $send->liquitercero_id); 
        }
 
       $send = collect($datos);

        return Datatables::of($send)
                        ->addColumn('identificacion', function ($send) {
                            return '<div align=left>' . $send['identificacion'] . '</div>';
                        })
                        ->addColumn('nombres', function ($send) {
                            return '<div align=left>' . $send['nombres']. '</div>';
                        })
                        ->addColumn('apellidos', function ($send) {
                            return '<div align=left>' . $send['apellidos'] . '</div>';
                        })
                        ->addColumn('telefono', function ($send) {
                            return '<div align=left>' . $send['telefono'] . '</div>';
                        })
                        ->addColumn('email', function ($send) {
                            return '<div align=left>' . $send['email'] . '</div>';
                        })
                        ->addColumn('valor_comision_paga', function ($send) {
                            return '<div align=left>' . $send['valor_comision_paga'] . '</div>';
                        })
                        ->addColumn('prime', function ($send) { 
                            return '<div align=left>' . $send['prime']. '</div>';
                        })
                        ->addColumn('estado', function ($send) { 
                            return '<div align=left> <span>'.$send['estado'].' <input type="hidden" class="nombre_'.$send['liquitercero_id'].'" value="'.$send['nombres']." ".$send['apellidos'].'"></span>  </div>';
                        })
                        ->addColumn('estado_pendiente', function ($send) { 
                            return '<div align=left> <span>'.$send['estado_pendiente'].'</span> </div>';
                        })
                        ->addColumn('estado_id', function ($send) { 
                            return $send['estado_id'];
                        })
                        ->addColumn('liquitercero_id', function ($send) { 
                            return $send['liquitercero_id'];
                        })
                        ->make(true); 
    }

    public function liquidaciones_detalles_excel($id=0) {  

    ini_set('memory_limit', '-1'); 

    $envios =  DB::table('liquidaciones_terceros')
        ->where('liquidaciones_terceros.liquidacion_id', $id)
        ->join('terceros', 'terceros.id', '=', 'liquidaciones_terceros.tercero_id')
        ->select(DB::raw("liquidaciones_terceros.*, terceros.*, ( select coalesce(sum(saldo), 0) from liquidaciones_terceros as lt2 where lt2.tercero_id = liquidaciones_terceros.tercero_id  and lt2.liquidacion_id < liquidaciones_terceros.liquidacion_id limit 1) as saldo_anterior, ( select lt2.valor_comision from liquidaciones_terceros as lt2 where lt2.tercero_id = liquidaciones_terceros.tercero_id  and lt2.liquidacion_id < liquidaciones_terceros.liquidacion_id) as mes_anterior"))
        //->limit(10)
        ->orderByRaw('liquidaciones_terceros.valor_comision_paga ASC')->get();

        Excel::create('liquidaciones', function($excel) use ($envios) {
            $excel->sheet('liquidaciones', function($sheet) use ($envios)  {
            	$sheet->prependRow(1, array('Cedula', 'Nombres', 'Apellidos', 'Teléfono', 'Email', 
                    'Prime','Valor comision','Retefuente','Rete ICA','Prime','IVA Prime','Transferencia','Extractos
','Administrativos','Comision con descuentos','Giro Billetera Virtual','Giro Cuenta', 'Valor del saldo a favor', 'saldo paga', 'saldo anterior', 'Mes anterior'));
            	foreach ($envios as $value) {

            		$prime = '';
                    if ($value->prime != '0') {
                    	 $prime = 'Si';
                    }
                    else{
                        $prime = 'No';
                    }

                    $saldo_paga = '';
                    if ($value->saldo_paga == '1') {
                         $saldo_paga = 'No paso formula de descuentos para verificar si se acomula este saldo o no.';
                    }
                    else{
                        $saldo_paga = 'Saldos anteriores agregado a la comision actual.';
                    }

                    $sheet->prependRow(2, array($value->identificacion, $value->nombres, $value->apellidos, $value->telefono, $value->email, 
                        $prime, $value->valor_comision, $value->rete_fuente, $value->rete_ica, $value->prime, 
                        $value->prime_iva, $value->transferencia, $value->extracto, $value->administrativo, $value->valor_comision_paga, 
                        $value->virtual, $value->giro, $value->saldo, $saldo_paga, $value->saldo_anterior, $value->mes_anterior));
                }
            });
        })->export('xls');

    }

    public function liquidaciones()
    {
        
        $usuario = currentUser()->id; 
/*
         $prime = DB::table('terceros_prime as tp')->join('terceros as t', 'tp.tercero_id', '=', 't.id')->where('tp.tercero_id',  $usuario)
                    ->where('estado', true)->orderBy('tp.id', 'desc')->first();
            $prime_val='no';
                if (count($prime) > 0) {

                    $now = Carbon::now();
                    $old = Carbon::parse($prime->fecha_final);

                    if ($now <= $old) {
                        $prime_val='si';
                    }
                } 
  */
        return view('admin.liquidaciones.index', compact('prime_val'));
    }

    public function data_liquidaciones()
    {

       $id = currentUser()->id;
       
  //currentUser()->id
        //$liquidaciones = Tercero::with('liquidacion_tercero')->find($id);
        $liquidaciones = DB::table('liquidaciones_terceros')
                                        ->leftjoin('tipos', 'liquidaciones_terceros.estado_id', '=', 'tipos.id')
                                        ->join('liquidaciones', 'liquidaciones.id', '=', 'liquidaciones_terceros.liquidacion_id')
                                        ->where('liquidaciones_terceros.tercero_id', $id) 
                                        ->select(DB::raw("liquidaciones_terceros.*, liquidaciones_terceros.id as liquidacion_tercero_id ,tipos.nombre as tipo_nombre,  tipos.id as tipo_id, liquidaciones.*, 
                                            (select nombre from tipos as t where t.id = tipo_pendiente_id) as  motivo"))
                                        ->get();

        $send = collect($liquidaciones);

        return Datatables::of($send)

            ->addColumn('date', function ($send) {
                
                return '<div align=center>' . Carbon::parse($send->fecha_inicio)->format('d/m/Y') . ' - ' . Carbon::parse($send->fecha_final)->format('d/m/Y')  . '</div>';
            })
            ->addColumn('nombres', function ($send) {
                $t = Tercero::find($send->tercero_id);
                return '<div align=center>' . ucwords($t->nombres) . ' ' . ucwords($t->apellidos) . ' </div>';
            })
            ->addColumn('consignacion', function ($send) {

                $giro = 0; 

                if($send->giro >= 1){   
                    $giro = $send->giro;
                } 
                else{
                    $giro = 0; 
                } 
               
                return '<div align=center>' . number_format((float)$giro) . '</div>';
            })
            ->addColumn('bono', function ($send) {              

              $ok = LiquidacionTercero::find($send->liquidacion_tercero_id);

                if (count($ok) > 0 && $ok->bono_good == null && $ok->bono_mercando == null && $ok->giftcard_good == null && $ok->giftcard_mercando == null ) {

                    if ($ok->valor_comision_paga > 0 && $ok->estado_id == 87) {

                        $boton = '<div align=center><a href="' . route('admin.liquidaciones.edit', $ok->id) . '"  class="btn btn-warning btn-xs">Crear Bonos</a></div>';

                    }elseif ($ok->valor_comision_paga > 0 && $ok->estado_id == 88 && $ok->tipo_pendiente_id == 89) {

                        $boton =  '<div align=center>En revisión administrativa para poder crear bonos</div>';

                    }elseif ($ok->valor_comision_paga > 0 && $ok->estado_id == 88 && $ok->tipo_pendiente_id == 90) {

                        $boton =  '<div align=center>Fondos insuficientes para crear bonos</div>';

                    }elseif ($ok->valor_comision_paga > 0 && $ok->estado_id == 88 && $ok->tipo_pendiente_id == 91) {

                        $boton =  '<div align=center>Falta de documentos para generar Bonos</div>';

                    }  else {

                        $boton =  '<div align=center>Falta de documentos para generar Bonos</div>';
                    }

                } else {
                    $boton =  '<div align=center>Sus bonos ya fueron generados</div>';
                }

                $virtual = 0; 

                if($send->virtual >= 1){   
                    $virtual = $send->virtual;
                } 
                else{
                    $virtual = 0; 
                } 

                return '<div align=center>' . number_format((float)$virtual) . ' <br><br> '.($boton).' </div>';
            })
            ->addColumn('total', function ($send) {
                return '<div align=center>' . number_format((float)$send->valor_comision) . '</div>';
            })
            ->addColumn('total_paga', function ($send) {
              if($send->valor_comision_paga >= 1){           
                return '<div align=center>' . number_format((float)$send->valor_comision_paga) . '</div>';
               }
               else{
                   return '<div align=center>0</div>';
                } 
            })
            ->addColumn('rete_fuente', function ($send) {   
                return '<div align=left><b>Retefuente:</b> ' . number_format((float)$send->rete_fuente) . '<br>'.
                    '<div align=left><b>Rete ICA:</b> ' . number_format((float)$send->rete_ica) . '<br>'. 
                    '<div align=left><b>Prime: </b>' . number_format((float)$send->prime) . '<br>'.   
                    '<div align=left><b>IVA Prime:</b> ' . number_format((float)$send->prime_iva) . '<br>'. 
                    '<div align=left><b>Administrativo y transferencia: </b>' . number_format((float)$send->transferencia + (float)$send->extracto + (float)$send->administrativo) . ''.
                '<br></div>';
            })

            ->addColumn('estado', function ($send) {    
              if($send->tipo_nombre == NULL){
                return '<div align=center>Sin estado</div>';
              }  
              else if($send->tipo_nombre != NULL && $send->tipo_nombre != 'Pendiente'){          
                return '<div align=center>' . $send->tipo_nombre . '</div>';
              }
              else{
                return '<div align=center>' . $send->tipo_nombre . ' <br><br> <b>Motivo: </b> ' . $send->motivo . ' </div>';
              }
            })
            ->addColumn('edit', function ($send) {
                $ok = LiquidacionTercero::where('id', $send->id)->where('bono_good', null)->where('bono_mercando', null)->where('giftcard_good', null)->where('giftcard_mercando', null)->first();
                if (count($ok) > 0 ) {

                    if ($ok->valor_comision_paga > 0) {
                        return '<div align=center><a href="' . route('admin.liquidaciones.edit', $send->id) . '"  class="btn btn-warning btn-xs">
                        Crear Bonos                                 </a></div>';
                    }else {

                        return '<div align=center>Fondos insuficientes para generar bonos. '. $ok->valor_comision_paga .'</div>';
                    }


                } else {
                    return '<div align=center>Sus bonos ya fueron generados</div>';
                }

            })
            ->addColumn('extractos', function ($send) {

                $liquidacion = Liquidacion::find($send->liquidacion_id);
/*
                $prime = DB::table('terceros_prime as tp')
                    ->join('terceros as t', 'tp.tercero_id', '=', 't.id')
                    ->where('tp.tercero_id',  $send->tercero_id)
                    ->where('estado', true)
                    ->orderBy('tp.id', 'desc')
                    ->first();

                if (count($prime) > 0) {

                    $now = Carbon::now();
                    $old = Carbon::parse($prime->fecha_final);

                    if ($now <= $old) {
*/
                        return '<div align=center><a href="' . route('liquidacion.liquidaciones_extracto_comisiones', $send->liquidacion_id) . '"  class="btn btn-warning btn-xs">
                        Extracto
                </a></div>';
 /*
                    }

                    return '<div align=center>No es prime</div>';

                }

                return '<div align=center>No es prime</div>';
*/
            })
            ->make(true);
    }

    public function editar_liquidaciones($id)
    {
        $liquidacion_tercero = LiquidacionTercero::find($id);

        $state = true;

        if ($liquidacion_tercero->estado_id == 88 || $liquidacion_tercero->valor_comision_paga <= 0 || $liquidacion_tercero->bono_good != null || $liquidacion_tercero->bono_mercando || $liquidacion_tercero->giftcard_mercando || $liquidacion_tercero->giftcard_good) {
            $state = false;
        }

        return view('admin.liquidaciones.edit')->with(['total' => $liquidacion_tercero->virtual, 'id' => $liquidacion_tercero->id, 'consignacion' => ($liquidacion_tercero->virtual), 'bono' => ($liquidacion_tercero->virtual), 'fecha' => Carbon::parse($liquidacion_tercero->fecha_liquidacion)->diffForHumans(), 'state' => $state]);

    }

}