<?php

namespace App\Console\Commands;


use App\Product;
use App\Variant;
use Illuminate\Console\Command;

class GetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para obtener todos los productos de la API shopify';

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

            $resa = $client->request('GET', $api_url . '/admin/products.json?limit=250&&page=' . $h);

            $headers = $resa->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];
            if ($diferencia < 20) {

                usleep(10000000);
            }

            $results = json_decode($resa->getBody(), true);

            foreach ($results['products'] as  $product) {

                $response = Product::where('shop', 'good')
                    ->where('id', $product['id'])
                    ->first();

                if(count($response) == 0) {

                    $a = $client->request('GET', $api_url . '/admin/collects.json?product_id=' . $product['id']);
                    $headers = $a->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                    $x = explode('/', $headers[0]);
                    $diferencia = $x[1] - $x[0];
                    if ($diferencia < 20) {

                        usleep(10000000);
                    }

                    $collections = json_decode($a->getBody(), true);

                    $nacional = false;

                    if (count($collections['collects']) > 0) {

                        foreach ($collections['collects'] as $collect) {

                            if ($collect['collection_id'] == 25960513573) {
                                $nacional = true;
                            }
                        }
                    }

                    if ($nacional == true) {

                        Product::createProduct($product, 'nacional', 'good');

                        foreach ($product['variants'] as $variant) {

                            Variant::createVariant($variant,  'good');

                        }

                    } else {

                        Product::createProduct($product, 'internacional', 'good');

                        foreach ($product['variants'] as $variant) {

                            Variant::createVariant($variant, 'good');
                        }
                    }
                }

                if (count($response) > 0 ) {

                    foreach ($product['variants'] as $variant) {

                        Variant::updateVariant($variant, 'good');
                    }

                    $update = Product::find($response->id);
                    $update->shop = 'good';
                    $update->title = $product['title'];
                    $update->image = $product['image'];
                    $update->images = $product['images'];
                    $update->vendor = $product['vendor'];
                    $update->save();

                }
            }

            $h++;

            if (count($results['products']) < 1) {
                $result = false;
            }

        } while($result);


        $this->info('Los productos han sido descargados correctamente');
    }
}
