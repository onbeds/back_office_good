<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 21/12/17
 * Time: 05:08 PM
 */

namespace App\Traits;

use Carbon\Carbon;
use App\Liquidacion;


trait Liquidar
{
    public function Liquidar($order)
    {
        if ($order->financial_status == "paid" && $order->comisionada == null && $order->cancelled_at == null && $order->liquidacion_id == null && $order->tercero_id != null) {

            $tercero = Tercero::where('id', $order->tercero_id)
                ->where('state', true)
                ->first();

            if (count($tercero) > 0) {

                $update = Tercero::with('networks', 'levels', 'cliente')->find($tercero->id);

                if (isset($update->cliente)) {

                    /*$liquidacion = Liquidacion::create([
                        'usuario_id' => currentUser()->id,
                        'fecha_inicio' => Carbon::now()->subMonth(),
                        'fecha_final' => Carbon::now(),
                        'fecha_liquidacion' => Carbon::now()
                    ]);
                    
                    if ($liquidacion) {
                        
                    }*/

                    if ($update->cliente->id == 85) {

                        if (count($update->networks) > 0) {

                            $padre_uno = Tercero::with('networks', 'levels', 'tipo')->find($update->networks[0]['pivot']['padre_id']);

                            if (count($padre_uno) > 0 && $padre_uno->state == true) {

                                if (count($padre_uno->networks) > 0) {

                                    $padre_dos = Tercero::with('networks', 'levels', 'tipo')->find($padre_uno->networks[0]['pivot']['padre_id']);

                                    if (count($padre_dos) > 0 && $padre_dos->state == true) {


                                        if (count($padre_dos->networks) > 0) {

                                            $padre_tres = Tercero::with('networks', 'levels', 'tipo')->find($padre_dos->networks[0]['pivot']['padre_id']);

                                            if (count($padre_tres) > 0 && $padre_tres->state == true) {


                                                if (count($padre_tres->networks) > 0) {

                                                    $padre_cuatro = Tercero::with('networks', 'levels', 'tipo')->find($padre_tres->networks[0]['pivot']['padre_id']);

                                                    if (count($padre_cuatro) > 0 && $padre_cuatro->state == true) {


                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    } else {

                        if (count($update->networks) > 0) {

                            $padre_uno = Tercero::with('networks', 'levels', 'tipo')->find($update->networks[0]['pivot']['padre_id']);

                            if (count($padre_uno) > 0 && $padre_uno->state == true) {

                                return $padre_uno->tipo;

                                if (count($padre_uno->networks) > 0) {

                                    $padre_dos = Tercero::with('networks', 'levels', 'tipo')->find($padre_uno->networks[0]['pivot']['padre_id']);

                                    if (count($padre_dos) > 0 && $padre_dos->state == true) {


                                        if (count($padre_dos->networks) > 0) {

                                            $padre_tres = Tercero::with('networks', 'levels', 'tipo')->find($padre_dos->networks[0]['pivot']['padre_id']);

                                            if (count($padre_tres) > 0 && $padre_tres->state == true) {


                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
    }
}