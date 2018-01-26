<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 19/12/17
 * Time: 11:09 AM
 */

namespace App\Helpers;

use Mail;

class Helpers {

    public static function send_mails($user, $msg)
    {
        Mail::send('admin.send.notifications', [
            'user' => $user,
            'msg' => $msg,
        ], function ($m)  use ($user, $msg) {
            $m->from('info@tiendagood.com', 'Tienda Good');
            $m->to($user->email, $user->name)->subject($msg);
        });
    }

    public static function mailing($view, $user, $msg, $bonuses, $archivo)
    {
        Mail::send($view, [
            'user' => $user,
            'msg' => $msg,
            'bonuses' => $bonuses,
            'path' => $archivo
        ], function ($m)  use ($user, $msg, $archivo) {
            $m->from('info@tiendagood.com', 'Tienda Good');
            $m->to('ljsea6@gmail.com', $user->name)->subject($msg);
            $m->attach($archivo);
        });
    }
}


