<?php

namespace App\Console\Commands;


use App\Traits\OrderPaidMercando;
use Illuminate\Console\Command;
use App\Order;
use DB;
use App\LineItems;
use App\Variant;

class GetOrdersMercando extends Command
{
    use OrderPaidMercando;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:orders-mercando';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para descargar las ordenes de mercando';

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
        $api_url = 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
        $client = new \GuzzleHttp\Client();
        $result = true;
        $h = 1;

        do {

            $res = $client->request('GET', $api_url . '/admin/orders.json?limit=250&&status=any&&page=' . $h);

            $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];
            if ($diferencia < 20) {

                usleep(20000000);
            }

            $results = json_decode($res->getBody(), true);

            foreach ($results['orders'] as $order) {

                $response = Order::where('network_id', 1)
                    ->where('name', $order['name'])
                    ->where('order_id', $order['id'])
                    ->where('shop', 'mercando')
                    ->first();

                if ($order['cancelled_at'] != null || $order['cancel_reason'] != null) {

                    if(count($response) == 0) {

                        $puntos = 0;

                        if (isset($order['line_items']) && count($order['line_items']) > 0) {

                            foreach ($order['line_items'] as $item) {

                                $v = Variant::where('id', $item['variant_id'])
                                    ->where('shop', 'mercando')
                                    ->where('product_id', $item['product_id'])
                                    ->first();

                                if (count($v) > 0) {

                                    $puntos = $puntos + $v->points;

                                    $line_item = LineItems::where('line_item_id', $item['id'])
                                        ->where('shop', 'mercando')
                                        ->where('variant_id', $item['variant_id'])
                                        ->first();

                                    if (count($line_item) == 0) {

                                        LineItems::createLineItem($item, $order, $v->points, 'mercando');
                                    }

                                }
                            }
                        }

                        $id = null;
                        $p = '';
                        $s = '';

                        if (isset($order['phone']) && !empty($order['phone'])) {
                            $p =  explode('+57', $order['phone']);
                            $s = '' . $p[1];
                        }

                        $tercero = Tercero::where('email', strtolower($order['email']))->orWhere('telefono', $s)->first();

                        if (count($tercero) > 0) {
                            $id = $tercero->id;
                        }

                        $order = Order::createOrder($order, 'mercando', $puntos, 'nacional', $id);
                    }
                }

                if ($order['cancelled_at'] == null && $order['cancel_reason'] == null) {

                    if(count($response) == 0) {

                        $puntos = 0;

                        if (isset($order['line_items']) && count($order['line_items']) > 0) {

                            foreach ($order['line_items'] as $item) {

                                $v = Variant::where('id', $item['variant_id'])
                                    ->where('shop', 'mercando')
                                    ->where('product_id', $item['product_id'])
                                    ->first();

                                if (count($v) > 0) {

                                    $this->info('Puntos:' . $v->percentage . ' * ' . (int)$item['quantity'] . ' = ' . $v->percentage * (int)$item['quantity']);

                                    $puntos = ((int)$puntos + ((int)$v->percentage * (int)$item['quantity']));

                                    $line_item = LineItems::where('line_item_id', $item['id'])
                                        ->where('shop', 'mercando')
                                        ->where('variant_id', $item['variant_id'])
                                        ->first();

                                    if (count($line_item) == 0) {

                                        LineItems::createLineItem($item, $order, $v->percentage, 'mercando');
                                    }
                                }
                            }
                        }

                        $id = null;
                        $p = '';
                        $s = '';

                        if (isset($order['phone']) && !empty($order['phone'])) {
                            $p =  explode('+57', $order['phone']);
                            $s = '' . $p[1];
                        }

                        $tercero = Tercero::where('email', strtolower($order['email']))->orWhere('telefono', $s)->first();

                        if (count($tercero) > 0) {
                            $id = $tercero->id;
                        }

                        $order_create = Order::createOrder($order, 'mercando', $puntos, 'nacional', $id);

                        $this->OrderPaidMercando($order, $order_create, $puntos);
                    }
                }
            }

            $h++;

            if (count($results['orders']) < 1) {
                $result = false;
            }

        } while($result);

        $this->info('Las ordenes han sido descargados correctamente');
    }
}
