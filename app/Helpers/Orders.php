<?php
/**
 * Created by PhpStorm.
 * User: leonardo
 * Date: 24/12/17
 * Time: 04:26 PM
 */

namespace App\Helpers;

use DB;
use App\Order;

class Orders
{
    public static function own_points($id)
    {
        $order = Order::find($id);
        return $order->points;
    }

    public static function points_by_line_items($id)
    {
        $order = DB::select(
            DB::raw(
                "
                SELECT o.id, o.name, o.points,  sum(lt.quantity * lt.points) as total
                FROM orders o
                INNER JOIN lineitems lt ON lt.order_id = o.order_id
                                           AND lt.shop = o.shop
                                           AND lt.order_name = o.name
                WHERE o.id = '$id'
                GROUP BY o.id, o.name, o.shop;
            "
            )
        );

        return $order[0]->total;
    }

    public static function order_ok($id)
    {
        $order = Order::find($id);

        if ($order->financial_status  == 'paid' && $order->cancelled_at == null && $order->comisionada == null && $order->liquidacion_id == null) {

            return true;

        }

        return false;
    }

    public static function check_points($id)
    {
        if (self::order_ok($id)) {

            $points_in_line_items = (int)self::points_by_line_items($id);
            $points_in_order = (int)self::own_points($id);

            if ($points_in_line_items != $points_in_order) {

                return false;
            }

            return true;
        }

        return false;
    }
}