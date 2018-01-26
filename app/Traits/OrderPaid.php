<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 11/12/17
 * Time: 10:35 AM
 */
namespace App\Traits;
use DB;
use App\Order;
use App\Variant;
use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Entities\Tercero;

trait OrderPaid
{

    public function OrderPaid($order, $order_create, $puntos)
    {
        if ($order['financial_status'] == "paid") {

            if (isset($order['line_items']) && count($order['line_items']) > 0) {
                foreach ($order['line_items'] as $item) {
                    $variant = Variant::where('id', $item['variant_id'])
                        ->where('product_id', $item['product_id'])
                        ->where('shop', 'good')
                        ->first();
                    if (count($variant) > 0) {
                        DB::table('variants')
                            ->where('id', $item['variant_id'])
                            ->where('product_id', $item['product_id'])
                            ->where('shop', 'good')
                            ->update(['sold_units' => (int)$variant->sold_units + (int)$item['quantity']]);
                    }
                }
            }

            $tercero = Tercero::where('email', strtolower($order['email']))
                ->where('state', true)
                ->first();

            if (count($tercero) > 0) {

                $update = Tercero::with('networks', 'levels', 'cliente')->find($tercero->id);

                if (isset($update->cliente)) {

                    if ($update->cliente->id == 85) {

                        if (count($update->networks) > 0) {

                            $padre_uno = Tercero::with('networks', 'levels')->find($update->networks[0]['pivot']['padre_id']);

                            if (count($padre_uno) > 0 && $padre_uno->state == true) {

                                $padre_uno->mispuntos = $padre_uno->mispuntos + $order_create->points;
                                $padre_uno->save();

                                $msg = '¡En hora buena! ' . ucwords($update->nombres) . ' ' .  ucwords($update->apellidos) . ' ha sumado ' . $order_create->points . ' cómo tus consumos propios';
                                Helpers::send_mails($padre_uno, $msg);

                                if (count($padre_uno->networks) > 0) {

                                    $padre_dos = Tercero::with('networks', 'levels')->find($padre_uno->networks[0]['pivot']['padre_id']);

                                    if (count($padre_dos) > 0 && $padre_dos->state == true) {

                                        $msg = '¡En hora buena! ' . ucwords($padre_uno->nombres) . ' ' .  ucwords($padre_uno->apellidos) . ' ha sumado ' . $order_create->points . ' puntos en tu nivel 1.';
                                        Helpers::send_mails($padre_dos, $msg);

                                        if (count($padre_dos->levels) == 0) {

                                            DB::table('terceros_niveles')->insertGetId(
                                                [
                                                    'tercero_id' => $padre_dos->id,
                                                    'nivel' =>  1,
                                                    'puntos' => $order_create->points,
                                                ]
                                            );

                                        } else {

                                            $result = DB::table('terceros_niveles')
                                                ->where('tercero_id', $padre_dos->id)
                                                ->where('nivel', 1)
                                                ->first();

                                            if (count($result) > 0) {

                                                DB::table('terceros_niveles')
                                                    ->where('tercero_id', $padre_dos->id)
                                                    ->where('nivel', 1)
                                                    ->update(['puntos' => (int)$result->puntos + (int)$order_create->points]);

                                            } else {

                                                DB::table('terceros_niveles')->insertGetId(
                                                    [
                                                        'tercero_id' => $padre_dos->id,
                                                        'nivel' =>  1,
                                                        'puntos' => $order_create->points,
                                                    ]
                                                );
                                            }
                                        }

                                        if (count($padre_dos->networks) > 0) {

                                            $padre_tres = Tercero::with('networks', 'levels')->find($padre_dos->networks[0]['pivot']['padre_id']);

                                            if (count($padre_tres) > 0 && $padre_tres->state == true) {

                                                $msg = '¡En hora buena! ' . ucwords($padre_uno->nombres) . ' ' .  ucwords($padre_uno->apellidos) . ' ha sumado ' . $order_create->points . ' puntos en tu nivel 2.';
                                                Helpers::send_mails($padre_tres, $msg);

                                                if (count($padre_tres->levels) == 0) {

                                                    DB::table('terceros_niveles')->insertGetId(
                                                        [
                                                            'tercero_id' => $padre_tres->id,
                                                            'nivel' =>  2,
                                                            'puntos' => $order_create->points,
                                                        ]
                                                    );
                                                } else {
                                                    $result = DB::table('terceros_niveles')
                                                        ->where('tercero_id', $padre_tres->id)
                                                        ->where('nivel', 2)
                                                        ->first();
                                                    if (count($result) > 0) {
                                                        DB::table('terceros_niveles')
                                                            ->where('tercero_id', $padre_tres->id)
                                                            ->where('nivel', 2)
                                                            ->update(['puntos' => (int)$result->puntos + (int)$order_create->points]);
                                                    } else {
                                                        DB::table('terceros_niveles')->insertGetId(
                                                            [
                                                                'tercero_id' => $padre_tres->id,
                                                                'nivel' =>  2,
                                                                'puntos' => $order_create->points,
                                                            ]
                                                        );
                                                    }
                                                }
                                                if (count($padre_tres->networks) > 0) {

                                                    $padre_cuatro = Tercero::with('networks', 'levels')->find($padre_tres->networks[0]['pivot']['padre_id']);

                                                    if (count($padre_cuatro) > 0 && $padre_cuatro->state == true) {

                                                        $msg = '¡En hora buena! ' . ucwords($padre_uno->nombres) . ' ' .  ucwords($padre_uno->apellidos) . ' ha sumado ' . $order_create->points . ' puntos en tu nivel 3.';
                                                        Helpers::send_mails($padre_cuatro, $msg);

                                                        if (count($padre_cuatro->levels) == 0) {

                                                            DB::table('terceros_niveles')->insertGetId(
                                                                [
                                                                    'tercero_id' => $padre_cuatro->id,
                                                                    'nivel' =>  3,
                                                                    'puntos' => $order_create->points,
                                                                ]
                                                            );

                                                        } else {

                                                            $result = DB::table('terceros_niveles')
                                                                ->where('tercero_id', $padre_cuatro->id)
                                                                ->where('nivel', 3)
                                                                ->first();

                                                            if (count($result) > 0) {

                                                                DB::table('terceros_niveles')
                                                                    ->where('tercero_id', $padre_cuatro->id)
                                                                    ->where('nivel', 3)
                                                                    ->update(['puntos' => (int)$result->puntos + (int)$order_create->points]);

                                                            } else {

                                                                DB::table('terceros_niveles')->insertGetId(
                                                                    [
                                                                        'tercero_id' => $padre_cuatro->id,
                                                                        'nivel' =>  3,
                                                                        'puntos' => $order_create->points,
                                                                    ]
                                                                );
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

                    } else {

                        $update->mispuntos = $update->mispuntos + $order_create->points;
                        $update->save();

                        $msg = '¡En hora buena! has sumado ' . $order_create->points . ' puntos a tus consumos propios.';
                        Helpers::send_mails($update, $msg);

                        if (count($update->networks) > 0) {

                            $padre_uno = Tercero::with('networks', 'levels')->find($update->networks[0]['pivot']['padre_id']);

                            if (count($padre_uno) > 0 && $padre_uno->state == true) {

                                $msg = '¡En hora buena! ' . ucwords($update->nombres) . ' ' .  ucwords($update->apellidos) . ' ha sumado ' . $order_create->points . ' puntos en tu nivel 1.';
                                Helpers::send_mails($padre_uno, $msg);

                                if (count($padre_uno->levels) == 0) {

                                    DB::table('terceros_niveles')->insertGetId(
                                        [
                                            'tercero_id' => $padre_uno->id,
                                            'nivel' =>  1,
                                            'puntos' => $order_create->points,
                                        ]
                                    );

                                } else {
                                    $result = DB::table('terceros_niveles')
                                        ->where('tercero_id', $padre_uno->id)
                                        ->where('nivel', 1)
                                        ->first();
                                    if (count($result) > 0) {
                                        DB::table('terceros_niveles')
                                            ->where('tercero_id', $padre_uno->id)
                                            ->where('nivel', 1)
                                            ->update(['puntos' => (int)$result->puntos + (int)$order_create->points]);
                                    } else {
                                        DB::table('terceros_niveles')->insertGetId(
                                            [
                                                'tercero_id' => $padre_uno->id,
                                                'nivel' =>  1,
                                                'puntos' => $order_create->points,
                                            ]
                                        );
                                    }
                                }
                                if (count($padre_uno->networks) > 0) {

                                    $padre_dos = Tercero::with('networks', 'levels')->find($padre_uno->networks[0]['pivot']['padre_id']);

                                    if (count($padre_dos) > 0 && $padre_dos->state == true) {

                                        $msg = '¡En hora buena! ' . ucwords($update->nombres) . ' ' .  ucwords($update->apellidos) . ' ha sumado ' . $order_create->points . ' puntos en tu nivel 2.';
                                        Helpers::send_mails($padre_dos, $msg);

                                        if (count($padre_dos->levels) == 0) {

                                            DB::table('terceros_niveles')->insertGetId(
                                                [
                                                    'tercero_id' => $padre_dos->id,
                                                    'nivel' =>  2,
                                                    'puntos' => $order_create->points,
                                                ]
                                            );

                                        } else {

                                            $result = DB::table('terceros_niveles')
                                                ->where('tercero_id', $padre_dos->id)
                                                ->where('nivel', 2)
                                                ->first();

                                            if (count($result) > 0) {

                                                DB::table('terceros_niveles')
                                                    ->where('tercero_id', $padre_dos->id)
                                                    ->where('nivel', 2)
                                                    ->update(['puntos' => (int)$result->puntos + (int)$order_create->points]);

                                            } else {

                                                DB::table('terceros_niveles')->insertGetId(
                                                    [
                                                        'tercero_id' => $padre_dos->id,
                                                        'nivel' =>  2,
                                                        'puntos' => $order_create->points,
                                                    ]
                                                );
                                            }
                                        }
                                        if (count($padre_dos->networks) > 0) {

                                            $padre_tres = Tercero::with('networks', 'levels')->find($padre_dos->networks[0]['pivot']['padre_id']);

                                            if (count($padre_tres) > 0 && $padre_tres->state == true) {

                                                $msg = '¡En hora buena! ' . ucwords($update->nombres) . ' ' .  ucwords($update->apellidos) . ' ha sumado ' . $order_create->points . ' puntos en tu nivel 3.';
                                                Helpers::send_mails($padre_tres, $msg);


                                                if (count($padre_tres->levels) == 0) {

                                                    DB::table('terceros_niveles')->insertGetId(
                                                        [
                                                            'tercero_id' => $padre_tres->id,
                                                            'nivel' =>  3,
                                                            'puntos' => $order_create->points,
                                                        ]
                                                    );

                                                } else {

                                                    $result = DB::table('terceros_niveles')
                                                        ->where('tercero_id', $padre_tres->id)
                                                        ->where('nivel', 3)
                                                        ->first();

                                                    if (count($result) > 0) {

                                                        DB::table('terceros_niveles')
                                                            ->where('tercero_id', $padre_tres->id)
                                                            ->where('nivel', 3)
                                                            ->update(['puntos' => (int)$result->puntos + (int)$order_create->points]);

                                                    } else {

                                                        DB::table('terceros_niveles')->insertGetId(
                                                            [
                                                                'tercero_id' => $padre_tres->id,
                                                                'nivel' =>  3,
                                                                'puntos' => $order_create->points,
                                                            ]
                                                        );
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

                $update = Order::find($order_create->id);
                $update->cargue_puntos = Carbon::now();
                $update->save();
            }
        }
    }
}