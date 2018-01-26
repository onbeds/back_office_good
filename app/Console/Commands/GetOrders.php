<?php

namespace App\Console\Commands;

use App\Customer;
use App\Entities\Network;
use App\Entities\Tercero;
use App\LineItems;
use App\Logorder;
use App\Order;
use App\Product;
use App\Traits\OrderPaid;
use App\Variant;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class GetOrders extends Command
{
    use OrderPaid;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para obtener todos las ordenes de la API shopify';

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
        $api_url = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
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
                    ->where('shop', 'good')
                    ->first();

                if ($order['cancelled_at'] != null || $order['cancel_reason'] != null) {

                    if(count($response) == 0) {

                        $tipo_orden = '';
                        $i = 0;
                        $n = 0;
                        $puntos = 0;

                        if (isset($order['line_items']) && count($order['line_items']) > 0) {

                            foreach ($order['line_items'] as $item) {

                                $v = Variant::where('id', $item['variant_id'])
                                    ->where('shop', 'good')
                                    ->where('product_id', $item['product_id'])
                                    ->first();

                                if (count($v) > 0) {

                                    $puntos = $puntos + $v->points;

                                    $line_item = LineItems::where('line_item_id', $item['id'])
                                        ->where('shop', 'good')
                                        ->where('variant_id', $item['variant_id'])
                                        ->first();

                                    if (count($line_item) == 0) {

                                        LineItems::createLineItem($item, $order, $v->points, 'good');
                                    }

                                    $product = Product::find($item['product_id']);

                                    if ($product->tipo_producto == 'nacional') {
                                        $n++;
                                    }
                                    if ($product->tipo_producto == 'internacional') {
                                        $i++;
                                    }
                                }
                            }
                        }

                        if ($i > 0 && $n > 0) {
                            $tipo_orden .= 'nacional/internacional';
                            $i = 0;
                            $n = 0;
                        }
                        if ($i > 0 && $n == 0) {
                            $tipo_orden .= 'internacional';
                            $i = 0;
                            $n = 0;
                        }
                        if ($i == 0 && $n > 0) {
                            $tipo_orden .= 'nacional';
                            $i = 0;
                            $n = 0;
                        }

                        $id = null;
                        $tercero = Tercero::where('email', strtolower($order['email']))->first();

                        if (count($tercero) > 0) {
                            $id = $tercero->id;
                        }

                        $order = Order::createOrder($order, 'good', $puntos, $tipo_orden, $id);

                        $tipo_orden = '';

                    }
                }

                if ($order['cancelled_at'] == null && $order['cancel_reason'] == null) {

                    if(count($response) == 0) {

                        $tipo_orden = '';
                        $i = 0;
                        $n = 0;
                        $puntos = 0;

                        if (isset($order['line_items']) && count($order['line_items']) > 0) {

                            foreach ($order['line_items'] as $item) {

                                $v = Variant::where('id', $item['variant_id'])
                                    ->where('shop', 'good')
                                    ->where('product_id', $item['product_id'])
                                    ->first();
                                

                                if (count($v) > 0) {

                                    $this->info('Puntos:' . $v->percentage . ' * ' . (int)$item['quantity'] . ' = ' . $v->percentage * (int)$item['quantity']);

                                    $puntos = ((int)$puntos + ((int)$v->percentage * (int)$item['quantity']));

                                    $line_item = LineItems::where('line_item_id', $item['id'])
                                        ->where('shop', 'good')
                                        ->where('variant_id', $item['variant_id'])
                                        ->first();

                                    if (count($line_item) == 0) {

                                        LineItems::createLineItem($item, $order, $v->percentage, 'good');
                                    }

                                    $product = Product::find($item['product_id']);

                                    if ($product->tipo_producto == 'nacional') {
                                        $n++;
                                    }
                                    if ($product->tipo_producto == 'internacional') {
                                        $i++;
                                    }
                                }
                            }
                        }

                        if ($i > 0 && $n > 0) {
                            $tipo_orden .= 'nacional/internacional';
                            $i = 0;
                            $n = 0;
                        }
                        if ($i > 0 && $n == 0) {
                            $tipo_orden .= 'internacional';
                            $i = 0;
                            $n = 0;
                        }
                        if ($i == 0 && $n > 0) {
                            $tipo_orden .= 'nacional';
                            $i = 0;
                            $n = 0;
                        }

                        $id = null;
                        $tercero = Tercero::where('email', strtolower($order['email']))->first();

                        if (count($tercero) > 0) {
                            $id = $tercero->id;
                        }

                        $order_create = Order::createOrder($order, 'good', $puntos, $tipo_orden, $id);

                        $tipo_orden = '';

                        $this->OrderPaid($order, $order_create, $puntos);

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
