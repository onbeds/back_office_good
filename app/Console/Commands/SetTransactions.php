<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use App\Transactions;
use App\Order;

class SetTransactions extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para guardar las transacciones de las tiendas de good y mercardo y gurdarlas en la tabla transactions del backoffice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $totalguardadas = 0;
        $noguardadas = array();

        $client = new \GuzzleHttp\Client();
        $ordenes = Order::select('order_id', 'shop')
                ->where('financial_status', 'paid')
                ->get();

        foreach ($ordenes as $orden) {
            if ($orden->shop != NULL) {
                if ($orden->shop == 'good') {
                    $api = 'https://' . env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
                } elseif ($orden->shop == 'mercando') {
                    $api = 'https://' . env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
                }

                try {
                    $shop = $client->request("GET", $api . "/admin/orders/" .$orden->order_id ."/transactions.json");

                    $headers = $shop->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                    $x = explode('/', $headers[0]);
                    $diferencia = $x[1] - $x[0];
                    if ($diferencia < 20) {
                        usleep(20000000);
                    }

                    $results = json_decode($shop->getBody(), true);
                    if (count($results['transactions']) > 0) {
                        foreach ($results['transactions'] as $transaction) {
                            if ($transaction['amount'] != $orden->total_price) {
                                $save = Transactions::saveTransaction($transaction, $orden->shop);
                                if ($save) {
                                    $totalguardadas++;
                                } else {
                                    $noguardadas[] = $transaction['id'];
                                }
                            }
                        }
                    } else {
                        $this->info('No se encuentran transacciones por la orden con id ' + $orden->order_id);
                    }
                } catch (ClientException $e) {

                    if ($e->hasResponse()) {
                        $err = json_decode(($e->getResponse()->getBody()), true);
                        $this->info('error mas grande');
                    }
                }
            }
        }

        $this->info('Se guardaron ' . $totalguardadas . ' transacciones en total');
        if (count($noguardadas) > 0) {
            $this->info(count($noguardadas) . ' transacciones no se guardaron correctamente:');
            $this->info('(' . implode(',', $noguardadas) . ')');
        } else {
            $this->info('Proceso se ha realizado con exito');
        }
    }

}
