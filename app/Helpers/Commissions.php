<?php
/**
 * Created by PhpStorm.
 * User: leonardo
 * Date: 24/12/17
 * Time: 07:08 PM
 */

namespace App\Helpers;

use DB;
use App\Prime;
use App\Liquidacion;
use App\Helpers\Points;
use App\Entities\Tercero;

class Commissions
{
    // Función para saber si un tercero es vendedor
    public static function is_seller($id)
    {
        $tercero = Tercero::find($id);

        if ($tercero->tipo_cliente_id == 83) {

            return true;

        }

        return false;
    }

    // Función para saber si un tecero es un cliente
    public static function is_client($id)
    {
        $tercero = Tercero::find($id);

        if ($tercero->tipo_cliente_id == 84) {

            return true;

        }

        return false;
    }

    // Función para saber si un tercero es amparado
    public static function is_covered($id)
    {
        $tercero = Tercero::find($id);

        if ($tercero->tipo_cliente_id == 85) {

            return true;
        }

        return false;
    }

    // Función para saber si un tercero es prime
    public static function is_prime($id)
    {
        if (self::is_seller($id)) {

            $prime = Prime::where('tercero_id', $id)
                ->where('estado', true)
                ->first();

            if (count($prime) > 0) {
                return true;
            }

            return false;
        }

        return false;
    }

    public static function no_type($id)
    {
        if (self::is_seller($id)) {
            $tercero = Tercero::find($id);
            $tercero->tipo_id = null;
            $tercero->save();
        }
    }

    public static function assign_without_type()
    {
        DB::raw(
            "
                UPDATE terceros  SET tipo_id = null
                WHERE id NOT IN
                (
                  (SELECT t.id
                      FROM terceros t
                      INNER JOIN orders o ON o.tercero_id = t.id
                      WHERE t.tipo_cliente_id = 83
                      AND o.financial_status = 'paid'
                      AND o.comisionada ISNULL
                      AND o.cancelled_at ISNULL
                      AND o.liquidacion_id ISNULL)
                      UNION
                      (SELECT t.id
                      FROM terceros t
                        INNER JOIN terceros_networks tn ON t.id = tn.padre_id
                        INNER JOIN terceros t2 ON tn.customer_id = t2.id
                        INNER JOIN orders o ON t2.id = o.tercero_id
                      WHERE t2.tipo_cliente_id = 85
                            AND o.financial_status = 'paid'
                            AND o.comisionada ISNULL
                            AND o.cancelled_at ISNULL
                            AND o.liquidacion_id ISNULL)
                );
            "
        );
    }

    public static function assign_with_type()
    {
        $terceros = DB::select(
            DB::raw(
                "
                    SELECT id FROM terceros WHERE id in
                    (
                      (SELECT t.id
                      FROM terceros t
                      INNER JOIN orders o ON o.tercero_id = t.id
                      WHERE t.tipo_cliente_id = 83
                      AND o.financial_status = 'paid'
                      AND o.comisionada ISNULL
                      AND o.cancelled_at ISNULL
                      AND o.liquidacion_id ISNULL)
                      UNION
                      (SELECT t.id
                      FROM terceros t
                        INNER JOIN terceros_networks tn ON t.id = tn.padre_id
                        INNER JOIN terceros t2 ON tn.customer_id = t2.id
                        INNER JOIN orders o ON t2.id = o.tercero_id
                      WHERE t2.tipo_cliente_id = 85
                            AND o.financial_status = 'paid'
                            AND o.comisionada ISNULL
                            AND o.cancelled_at ISNULL
                            AND o.liquidacion_id ISNULL)
                    );
            "
            )
        );

        return $terceros;
    }

    public static function junior($id)
    {
        if (self::is_seller($id)) {
            $tercero = Tercero::find($id);
            $tercero->tipo_id = 2;
            $tercero->save();
        }
    }

    public static function senior($id)
    {
        if (self::is_seller($id)) {
            $tercero = Tercero::find($id);
            $tercero->tipo_id = 3;
            $tercero->save();
        }
    }

    public static function master($id)
    {
        if (self::is_seller($id)) {
            $tercero = Tercero::find($id);
            $tercero->tipo_id = 4;
            $tercero->save();
        }
    }

    public static function change($id, $points)
    {
        if ($points > 1 && $points <= 299) {
            self::junior($id);
        }

        if ($points >= 300 && $points <= 599) {
            self::senior($id);
        }

        if ($points >= 600 ) {
            self::master($id);
        }
    }

    public static function change_type($id)
    {
        $own_points = Points::own_points($id);
        $red_points = Points::points_red($id);

        if ($own_points == 0) {

            self::no_type($id);
        }

        if ($own_points > 0) {

            if ($own_points > $red_points) {
                self::change($id, $red_points);
            }

            if ($own_points < $red_points) {
                self::change($id, $own_points);
            }

            if ($own_points == $red_points) {
                self::change($id, $red_points);
            }
        }
    }

    public static function junior_prime($id)
    {
        $tercero = Tercero::find($id);

        if (self::is_prime($id) && $tercero->tipo_id == 2) {
            $tercero->tipo_id = 5;
            $tercero->save();
        }
    }

    public static function senior_prime($id)
    {
        $tercero = Tercero::find($id);

        if (self::is_prime($id) && $tercero->tipo_id == 3) {
            $tercero->tipo_id = 6;
            $tercero->save();
        }
    }

    public static function master_prime($id)
    {
        $tercero = Tercero::find($id);

        if (self::is_prime($id) && $tercero->tipo_id == 4) {
            $tercero->tipo_id = 72;
            $tercero->save();
        }
    }

}