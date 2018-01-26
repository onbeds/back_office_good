<?php

namespace App\Console\Commands;

use App\Helpers\Helpers;
use App\Entities\Tercero;
use Illuminate\Console\Command;

class SendMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para pruebas de envio de mails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tercero = Tercero::where('email', 'oscar.fonseca.castro@gmail.com')
            ->where('state', true)
            ->first();

        if (count($tercero) > 0) {

            $update = Tercero::with('networks', 'levels', 'cliente')->find($tercero->id);

            if (isset($update->cliente)) {


                if ($update->cliente->id == 85) {

                    if (count($update->networks) > 0) {

                        $padre_uno = Tercero::with('networks', 'levels')->find($update->networks[0]['pivot']['padre_id']);

                        if (count($padre_uno) > 0 && $padre_uno->state == true) {

                            $msg = '¡En hora buena! ' . ucwords($update->nombres) . ' ' .  ucwords($update->apellidos) . ' ha sumado 0000 cómo tu consumo propio.';
                            Helpers::send_mails($padre_uno, $msg);

                            if (count($padre_uno->networks) > 0) {

                                $padre_dos = Tercero::with('networks', 'levels')->find($padre_uno->networks[0]['pivot']['padre_id']);

                                if (count($padre_dos) > 0 && $padre_dos->state == true) {

                                    $msg = '¡En hora buena! ' . ucwords($padre_uno->nombres) . ' ' .  ucwords($padre_uno->apellidos) . ' ha sumado 0000 puntos en tu nivel 1.';
                                    Helpers::send_mails($padre_dos, $msg);

                                    if (count($padre_dos->networks) > 0) {

                                        $padre_tres = Tercero::with('networks', 'levels')->find($padre_dos->networks[0]['pivot']['padre_id']);

                                        if (count($padre_tres) > 0 && $padre_tres->state == true) {

                                            $msg = '¡En hora buena! ' . ucwords($padre_uno->nombres) . ' ' .  ucwords($padre_uno->apellidos) . ' ha sumado 0000 puntos en tu nivel 2.';
                                            Helpers::send_mails($padre_tres, $msg);

                                            if (count($padre_tres->networks) > 0) {

                                                $padre_cuatro = Tercero::with('networks', 'levels')->find($padre_tres->networks[0]['pivot']['padre_id']);

                                                if (count($padre_cuatro) > 0 && $padre_cuatro->state == true) {

                                                    $msg = '¡En hora buena! ' . ucwords($padre_uno->nombres) . ' ' .  ucwords($padre_uno->apellidos) . ' ha sumado 0000 puntos en tu nivel 3.';
                                                    Helpers::send_mails($padre_cuatro, $msg);

                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                }

                if ($update->cliente->id != 85) {

                    $msg = '¡En hora buena! has sumado 0000 puntos a tus consumos propios.';
                    Helpers::send_mails($update, $msg);

                    if (count($update->networks) > 0) {

                        $padre_uno = Tercero::with('networks', 'levels')->find($update->networks[0]['pivot']['padre_id']);

                        if (count($padre_uno) > 0 && $padre_uno->state == true) {

                            $msg = '¡En hora buena! ' . ucwords($update->nombres) . ' ' .  ucwords($update->apellidos) . ' ha sumado 0000 puntos en tu nivel 1.';
                            Helpers::send_mails($padre_uno, $msg);

                            if (count($padre_uno->networks) > 0) {

                                $padre_dos = Tercero::with('networks', 'levels')->find($padre_uno->networks[0]['pivot']['padre_id']);

                                if (count($padre_dos) > 0 && $padre_dos->state == true) {

                                    $msg = '¡En hora buena! ' . ucwords($update->nombres) . ' ' .  ucwords($update->apellidos) . ' ha sumado 0000 puntos en tu nivel 2.';
                                    Helpers::send_mails($padre_dos, $msg);

                                    if (count($padre_dos->networks) > 0) {

                                        $padre_tres = Tercero::with('networks', 'levels')->find($padre_dos->networks[0]['pivot']['padre_id']);

                                        if (count($padre_tres) > 0 && $padre_tres->state == true) {

                                            $msg = '¡En hora buena! ' . ucwords($update->nombres) . ' ' .  ucwords($update->apellidos) . ' ha sumado 000 puntos en tu nivel 3.';
                                            Helpers::send_mails($padre_tres, $msg);

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
