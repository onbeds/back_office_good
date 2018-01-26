<?php
/**
 * Created by PhpStorm.
 * User: leonardo
 * Date: 24/12/17
 * Time: 02:21 PM
 */

namespace App\Helpers;

use DB;
use App\Order;

class Points
{
    public static function own_orders($id)
    {
        $orders = Order::where('financial_status', 'paid')
            ->where('cancelled_at', null)
            ->where('comisionada', null)
            ->where('liquidacion_id', null)
            ->where('tercero_id', $id)
            ->get();

        return $orders;
    }

    public static function amparado_orders($id)
    {
        $orders_amparados = DB::select(
            DB::raw(
                "
                SELECT t.id, tn.padre_id, o.name, o.points, o.financial_status, o.liquidacion_id, o.comisionada
                FROM orders o
                  INNER JOIN terceros t ON t.id = o.tercero_id
                  INNER JOIN terceros_networks tn ON tn.customer_id = t.id
                  INNER JOIN terceros t2 ON t2.id = tn.padre_id
                WHERE t.state = TRUE
                    AND  t.tipo_cliente_id = 85
                    AND o.financial_status = 'paid'
                    AND o.cancelled_at ISNULL
                    AND o.comisionada ISNULL
                    AND o.liquidacion_id  ISNULL
                    AND t2.state = TRUE
                    AND t2.id = '$id';
            "
            )
        );

        return $orders_amparados;
    }

    public static function have_own_points($id)
    {
        $orders = self::own_orders($id);

        $orders_amparados = self::amparado_orders($id);

        if (count($orders) > 0 || count($orders_amparados) > 0) {

            return true;

        } else {

            return false;

        }
    }

    public static function count_own_points($id)
    {
        $count = 0;

        $orders = self::own_orders($id);

        $orders_amparados = self::amparado_orders($id);

        if (count($orders) > 0) {

            foreach ($orders as $order) {
                $count = $count + (int)$order->points;
            }
        }

        if (count($orders_amparados) > 0) {

            foreach ($orders_amparados as $order) {
                $count = $count + (int)$order->points;
            }
        }

        return $count;
    }

    public static function own_points($id)
    {

        if (self::have_own_points($id)) {
            return self::count_own_points($id);
        }

        return 0;
    }

    public static function terceros_level_one($id)
    {
        $terceros = DB::select(
            DB::raw(
                "
                SELECT t2.id as id
                FROM terceros t
                  INNER JOIN terceros_networks tn ON tn.padre_id = t.id
                  INNER JOIN terceros t2 ON t2.id = tn.customer_id
                WHERE t.id = '$id'
                      AND t.state = true
                      AND t2.tipo_cliente_id <> 85
                      AND t2.state = true;
            "
            )
        );

        if (count($terceros) > 0) {

            return $terceros;

        } else {

            return [];
        }
    }

    public static function terceros_level_two($id)
    {
        $terceros = DB::select(
            DB::raw(
                "
                SELECT t3.id as id
                FROM terceros t
                  INNER JOIN terceros_networks tn ON tn.padre_id = t.id
                  INNER JOIN terceros t2 ON t2.id = tn.customer_id
                  INNER JOIN terceros_networks tn2 ON tn2.padre_id = t2.id
                  INNER JOIN terceros t3 ON t3.id = tn2.customer_id
                WHERE t.id = '$id'
                      AND t.state = true
                      AND t3.tipo_cliente_id <> 85
                      AND t3.state = true;
            "
            )
        );

        if (count($terceros) > 0) {

            return $terceros;

        } else {

            return [];
        }
    }

    public static function terceros_level_tree($id)
    {

        $terceros = DB::select(
            DB::raw(
                "
                SELECT t4.id as id
                FROM terceros t
                  INNER JOIN terceros_networks tn ON tn.padre_id = t.id
                  INNER JOIN terceros t2 ON t2.id = tn.customer_id
                  INNER JOIN terceros_networks tn2 ON tn2.padre_id = t2.id
                  INNER JOIN terceros t3 ON t3.id = tn2.customer_id
                  INNER JOIN terceros_networks tn3 ON tn3.padre_id = t3.id
                  INNER JOIN terceros t4 ON t4.id = tn3.customer_id
                WHERE t.id = '$id'
                      AND t.state = true
                      AND t4.tipo_cliente_id <> 85
                      AND t4.state = true;
            "
            )
        );

        if (count($terceros) > 0) {

            return $terceros;

        } else {

            return [];
        }
    }

    public static function terceros_by_level($id, $level)
    {
        if ($level == 1) {
            return self::terceros_level_one($id);
        }

        if ($level == 2) {
            return self::terceros_level_two($id);
        }

        if ($level == 3) {
            return self::terceros_level_tree($id);
        }
    }

    public static function points(array $terceros)
    {
        $points = 0;

        foreach ($terceros as $tercero) {
            $points = $points + (int)self::count_own_points($tercero->id);
        }

        return $points;
    }

    public static function points_by_level($id, $level)
    {
        $points = 0;

        $terceros = self::terceros_by_level($id, $level);

        if (count($terceros) > 0) {
            $points = $points + (int)self::points($terceros);
        }

        return $points;

    }

    public static function points_red($id)
    {
        $total = 0;

        $points_level_one = (int)self::points_by_level($id, 1);

        $points_level_two = (int)self::points_by_level($id, 2);

        $points_level_tree = (int)self::points_by_level($id, 3);

        $total = $total + (int)$points_level_one + (int)$points_level_two + (int)$points_level_tree;

        return $total;
    }

    public static function commissions($id)
    {
        $commissions = DB::select(
            DB::raw(
                "
                SELECT t.id as tercero_id, t.nombres as nombres, t2.nombre as tipo, r.id as regla, rd.id as detalle, rd.nivel as nivel, rd.comision_puntos as comision
                FROM terceros t
                  INNER JOIN tipos t2 ON t.tipo_id = t2.id
                  INNER JOIN rules r ON t2.id = r.tipo_id
                  INNER JOIN rules_details rd ON r.id = rd.rule_id
                WHERE t.id = '$id' AND t.state = TRUE;
            "
            )
        );

        return $commissions;
    }


}