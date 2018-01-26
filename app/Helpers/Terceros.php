<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 12/01/18
 * Time: 12:05 PM
 */

namespace App\Helpers;

use DB;


class Terceros
{
    public static function terceros_level_one($id)
    {
        $terceros = DB::select(
            DB::raw(
                "
                SELECT t2.*
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
                SELECT t3.*
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
                SELECT t4.*
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
}