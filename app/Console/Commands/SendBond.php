<?php

namespace App\Console\Commands;

use DB;
use View;
use App;
use App\Code;
use App\Order;
use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Entities\Tercero;
use Illuminate\Console\Command;

class SendBond extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:bond';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para generar bonos de cine mark';

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

        $info = DB::select(
            DB::raw(
                "
                SELECT t.id as user_id, t.nombres, t.apellidos, t.email, o.id, o.name, o.order_id ,(lt.quantity * 4) as quantity
                FROM  lineitems lt
                  INNER JOIN orders o ON lt.order_name = o.name AND o.shop = 'good' AND o.financial_status = 'paid' AND o.cancelled_at ISNULL
                  INNER JOIN terceros t ON o.tercero_id = t.id
                WHERE  lt.variant_id = 5011999162405 AND lt.product_id = 425003843621 AND lt.shop = 'good';
                "
            )
        );

        if (count($info) > 0) {

            foreach ($info  as $order) {

                $find = Order::with('codes')->find($order->id);

                if (count($find->codes) < (int)$order->quantity) {

                    if (count($find->codes) == 0) {

                        for ($i = 1; $i <= (int)$order->quantity; $i++) {

                            $c = DB::select(
                                DB::raw(
                                    "
                                SELECT id
                                FROM codes
                                WHERE state = FALSE
                                AND burned_at ISNULL
                                ORDER BY id ASC LIMIT 1;
                                "
                                )
                            );

                            $update = Code::find($c[0]->id);
                            $update->order_id = $order->id;
                            $update->state = true;
                            $update->burned_at = Carbon::now();
                            $update->save();

                            if ($update) {

                                $user = Tercero::find($order->user_id);

                                Helpers::mailing('admin.send.bonuses', $user, '¡Aquí está tu bono!', $update->code);
                            }

                            $this->info('Enviando email a ' . $user->email . ' con bono número: ' . $update->code);
                        }
                    }

                    if (count($find->codes) > 0 && count($find->codes) < (int)$order->quantity) {

                        $diff = (int)$order->quantity - count($find->codes);

                        for ($i = 1; $i <= (int)$diff; $i++) {

                            $c = DB::select(
                                DB::raw(
                                    "
                                SELECT id
                                FROM codes
                                WHERE state = FALSE
                                AND burned_at ISNULL
                                ORDER BY id ASC LIMIT 1;
                                "
                                )
                            );

                            $update = Code::find($c[0]->id);
                            $update->order_id = $order->id;
                            $update->state = true;
                            $update->burned_at = Carbon::now();
                            $update->save();

                            if ($update) {

                                $user = Tercero::find($order->user_id);
                                $identificacion = $user->identificacion;
                                $img = file_get_contents('https://barcode.tec-it.com/barcode.ashx?translate-esc=off&data=00000'. $update->code .'&code=EAN13&multiplebarcodes=false&unit=Fit&dpi=96&imagetype=Png&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0');
                                file_put_contents(public_path().'/uploads/' . $user->identificacion .'.png', $img);

                                $view =  View::make('emails.mail', compact('identificacion'))->render();
                                $pdf = App::make('dompdf.wrapper');
                                $pdf->loadHTML($view);
                                $pdf->stream('invoice');

                                file_put_contents(public_path().'/uploads/' . $user->identificacion .'.pdf', $pdf->stream('invoice'));

                                Helpers::mailing('admin.send.bonuses', $user, '¡Aquí está tu bono!', $update->code, public_path().'/uploads/' . $user->identificacion .'.pdf');
                            }

                            $this->info('Enviando email a ' . $user->email . ' con bono número: ' . $update->code);
                        }
                    }
                }
            }
        }

        $this->info('Bonos enviados.');
    }
}
