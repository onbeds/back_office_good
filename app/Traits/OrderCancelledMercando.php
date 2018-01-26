<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 11/12/17
 * Time: 09:11 PM
 */

namespace App\Traits;

use DB;
use App\Order;
use App\Variant;
use Carbon\Carbon;
use App\Entities\Tercero;

trait OrderCancelledMercando
{
    public function OrderCancelledMercando($result, $order)
    {
        $update_order = Order::find($result->id);
        $update_order->closed_at = $order['closed_at'];
        $update_order->cancelled_at = $order['cancelled_at'];
        $update_order->cancel_reason = $order['cancel_reason'];
        $update_order->financial_status = $order['financial_status'];
        $update_order->updated_at = Carbon::parse($order['updated_at']);
        $update_order->cargue_puntos = null;
        $update_order->save();

        if (isset($order['line_items']) && count($order['line_items']) > 0) {

            foreach ($order['line_items'] as $item) {

                $variant = Variant::where('id', $item['variant_id'])
                    ->where('product_id', $item['product_id'])
                    ->where('shop', 'mercando')
                    ->first();

                if (count($variant) > 0) {

                    DB::table('variants')
                        ->where('id', $item['variant_id'])
                        ->where('product_id', $item['product_id'])
                        ->where('shop', 'mercando')
                        ->update(['sold_units' => (int)$variant->sold_units - (int)$item['quantity']]);
                }
            }
        }

        $tercero = Tercero::where('email', strtolower($order['email']))
            ->where('state', true)
            ->first();

        if (count($tercero) > 0) {

            $update = Tercero::with('networks', 'levels', 'cliente')->find($tercero->id);

            if(isset($update->cliente)) {

                if ($update->cliente->id == 85) {

                    if (count($update->networks) > 0) {

                        $padre_uno = Tercero::with('networks', 'levels')->find($update->networks[0]['pivot']['padre_id']);

                        if (count($padre_uno) > 0 && $padre_uno->state == true) {

                            $padre_uno->mispuntos = (int)$padre_uno->mispuntos - (int)$update_order->points;
                            $padre_uno->save();


                            if (count($padre_uno->networks) > 0) {

                                $padre_dos = Tercero::with('networks', 'levels')->find($padre_uno->networks[0]['pivot']['padre_id']);

                                if (count($padre_dos) > 0 && $padre_dos->state == true) {

                                    $result = DB::table('terceros_niveles')
                                        ->where('tercero_id', $padre_dos->id)
                                        ->where('nivel', 1)
                                        ->first();

                                    if (count($result) > 0) {

                                        DB::table('terceros_niveles')
                                            ->where('tercero_id', $padre_dos->id)
                                            ->where('nivel', 1)
                                            ->update(['puntos' => (int)$result->puntos - (int)$update_order->points]);

                                    }

                                    if (count($padre_dos->networks) > 0) {

                                        $padre_tres = Tercero::with('networks', 'levels')->find($padre_dos->networks[0]['pivot']['padre_id']);

                                        if (count($padre_tres) > 0 && $padre_tres->state == true) {

                                            $result = DB::table('terceros_niveles')
                                                ->where('tercero_id', $padre_tres->id)
                                                ->where('nivel', 2)
                                                ->first();

                                            if (count($result) > 0) {

                                                DB::table('terceros_niveles')
                                                    ->where('tercero_id', $padre_tres->id)
                                                    ->where('nivel', 2)
                                                    ->update(['puntos' => (int)$result->puntos - (int)$update_order->points]);

                                            }


                                            if (count($padre_tres->networks) > 0) {

                                                $padre_cuatro = Tercero::with('networks', 'levels')->find($padre_tres->networks[0]['pivot']['padre_id']);

                                                if (count($padre_cuatro) > 0 && $padre_cuatro->state == true) {

                                                    $result = DB::table('terceros_niveles')
                                                        ->where('tercero_id', $padre_cuatro->id)
                                                        ->where('nivel', 3)
                                                        ->first();

                                                    if (count($result) > 0) {

                                                        DB::table('terceros_niveles')
                                                            ->where('tercero_id', $padre_cuatro->id)
                                                            ->where('nivel', 3)
                                                            ->update(['puntos' => (int)$result->puntos - (int)$update_order->points]);

                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                } else {

                    $update->mispuntos = (int)$update->mispuntos - (int)$update_order->points;
                    $update->save();

                    if (count($update->networks) > 0) {

                        $padre_uno = Tercero::with('networks', 'levels')->find($update->networks[0]['pivot']['padre_id']);

                        if (count($padre_uno) > 0 && $padre_uno->state == true) {


                            $result = DB::table('terceros_niveles')
                                ->where('tercero_id', $padre_uno->id)
                                ->where('nivel', 1)
                                ->first();

                            if (count($result) > 0) {

                                DB::table('terceros_niveles')
                                    ->where('tercero_id', $padre_uno->id)
                                    ->where('nivel', 1)
                                    ->update(['puntos' => (int)$result->puntos - (int)$update_order->points]);
                            }

                            if (count($padre_uno->networks) > 0) {

                                $padre_dos = Tercero::with('networks', 'levels')->find($padre_uno->networks[0]['pivot']['padre_id']);

                                if (count($padre_dos) > 0 && $padre_dos->state == true) {

                                    $result = DB::table('terceros_niveles')
                                        ->where('tercero_id', $padre_dos->id)
                                        ->where('nivel', 2)
                                        ->first();

                                    if (count($result) > 0) {

                                        DB::table('terceros_niveles')
                                            ->where('tercero_id', $padre_dos->id)
                                            ->where('nivel', 2)
                                            ->update(['puntos' => (int)$result->puntos - (int)$update_order->points]);

                                    }

                                    if (count($padre_dos->networks) > 0) {

                                        $padre_tres = Tercero::with('networks', 'levels')->find($padre_dos->networks[0]['pivot']['padre_id']);

                                        if (count($padre_tres) > 0 && $padre_tres->state == true) {

                                            $result = DB::table('terceros_niveles')
                                                ->where('tercero_id', $padre_tres->id)
                                                ->where('nivel', 3)
                                                ->first();

                                            if (count($result) > 0) {

                                                DB::table('terceros_niveles')
                                                    ->where('tercero_id', $padre_tres->id)
                                                    ->where('nivel', 3)
                                                    ->update(['puntos' => (int)$result->puntos - (int)$update_order->points]);

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