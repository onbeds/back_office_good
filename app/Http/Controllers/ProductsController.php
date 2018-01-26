<?php

namespace App\Http\Controllers;

use App\Entities\Tercero;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Product;
use Carbon\Carbon;
use App\Variant;


class ProductsController extends Controller
{
    /**
     * Undocumented function
     *
     * @return Products
     */
    function verify_webhook($data, $hmac_header)
    {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, 'afc86df7e11dcbe0ab414fa158ac1767', true));
        return hash_equals($hmac_header, $calculated_hmac);
    }

    public function welcome()
    {
        return view('admin.reportes.products');
    }

    public function indexGood()
    {
        return view('admin.productos.good');
    }

    public function indexMercando()
    {
        return view('admin.productos.mercando');
    }

    public function anyDataGood()
    {
        $products = Product::distinct()->select('id', 'title', 'tipo_producto', 'shop')
            ->where('shop', 'good')
            ->get();

        $send = collect($products);

        return Datatables::of($send)

            ->addColumn('id', function ($send) {
                return '<div align=left>' . $send['id'] . '</div>';
            })
            ->addColumn('title', function ($send) {
                return '<div align=left>' . ucwords($send['title']) . '</div>';
            })
            ->addColumn('shop', function ($send) {
                return '<div align=left>' . ucwords($send['shop']) . '</div>';
            })
            ->addColumn('tipo_producto', function ($send) {
                return '<div align=left>' . ucwords($send->tipo_producto) . '</div>';
            })
            ->addColumn('edit', function ($send) {
                return '<div align=left><a href="' . route('admin.products.good.variants', $send['id']) . '"  class="btn btn-warning btn-xs">
                        Editar
                </a></div>';
            })
            ->make(true);
    }

    public function anyDataMercando()
    {
        $products = Product::distinct()->select('id', 'title', 'tipo_producto', 'shop')
            ->where('shop', 'mercando')
            ->get();

        $send = collect($products);

        return Datatables::of($send)

            ->addColumn('id', function ($send) {
                return '<div align=left>' . $send['id'] . '</div>';
            })
            ->addColumn('title', function ($send) {
                return '<div align=left>' . ucwords($send['title']) . '</div>';
            })
            ->addColumn('shop', function ($send) {
                return '<div align=left>' . ucwords($send['shop']) . '</div>';
            })
            ->addColumn('tipo_producto', function ($send) {
                return '<div align=left>' . ucwords($send->tipo_producto) . '</div>';
            })
            ->addColumn('edit', function ($send) {
                return '<div align=left><a href="' . route('admin.products.mercando.variants', $send['id']) . '"  class="btn btn-warning btn-xs">
                        Editar
                </a></div>';
            })
            ->make(true);
    }

    public function variants_good($id)
    {
        return view('admin.productos.variants_good')->with(['id' => $id]);
    }

    public function variants_mercando($id)
    {
        return view('admin.productos.variants_mercando')->with(['id' => $id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $input = file_get_contents('php://input');
        $api_url = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
        $client = new \GuzzleHttp\Client();

        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $verified = $this->verify_webhook(collect($input), $hmac_header);
        $resultapi = error_log('Webhook verified: '.var_export($verified, true));

        if ($resultapi == 'true') {

            $product = json_decode($input, true);

            $response = Product::where('shop', 'good')
                ->where('id', $product['id'])
                ->first();

            if(count($response) == 0) {

                $a = $client->request('GET', $api_url . '/admin/collects.json?product_id=' . $product['id']);
                $headers = $a->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                $x = explode('/', $headers[0]);
                $diferencia = $x[1] - $x[0];
                if ($diferencia < 20) {

                    usleep(20000000);
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

                        try {

                            $resb = $client->request('get', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json');

                            $rs = json_decode($resb->getBody(), true);

                            if (count($rs['metafields']) > 0) {

                                foreach ($rs['metafields'] as $r) {

                                    if ($r['key'] == 'points' && $r['namespace'] == 'variants') {

                                        try {

                                            $resc = $client->request('put', $api_url . '/admin/variants/'. $variant['id'] .'/metafields/' . $r['id'] . '.json', array(
                                                    'form_params' => array(
                                                        'metafield' => array(
                                                            'namespace' => 'variants',
                                                            'key' => 'points',
                                                            'value' => 0,
                                                            'value_type' => 'integer'
                                                        )
                                                    )
                                                )
                                            );

                                            $headers = $resc->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(20000000);
                                            }

                                        } catch (ClientException $e) {

                                            if ($e->getResponse()) {
                                                continue;
                                            }
                                        }
                                    }
                                }

                            } else {

                                try {

                                    $resd = $client->request('post', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => 0,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(20000000);
                                    }


                                } catch (ClientException $e) {

                                    if ($e->getResponse()) {
                                        continue;
                                    }
                                }
                            }

                        } catch (ClientException $e) {

                            if ($e->getResponse()) {
                                continue;
                            }
                        }
                    }

                    return response()->json(['status' => 'The resource has been created successfully'], 200);

                } else {

                    Product::createProduct($product, 'internacional', 'good');

                    foreach ($product['variants'] as $variant) {

                        Variant::createVariant($variant,  'good');

                        try {

                            $resb = $client->request('get', $api_url . '/admin/variants/' . $variant['id'] . '/metafields.json');

                            $rs = json_decode($resb->getBody(), true);

                            if (count($rs['metafields']) > 0) {

                                foreach ($rs['metafields'] as $r) {

                                    if ($r['key'] == 'points' && $r['namespace'] == 'variants') {

                                        try {

                                            $resc = $client->request('put', $api_url . '/admin/variants/' . $variant['id'] . '/metafields/' . $r['id'] . '.json', array(
                                                    'form_params' => array(
                                                        'metafield' => array(
                                                            'namespace' => 'variants',
                                                            'key' => 'points',
                                                            'value' => 0,
                                                            'value_type' => 'integer'
                                                        )
                                                    )
                                                )
                                            );

                                            $headers = $resc->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(20000000);
                                            }


                                        } catch (ClientException $e) {

                                            if ($e->getResponse()) {
                                                continue;
                                            }
                                        }
                                    }
                                }

                            } else {

                                try {

                                    $resd = $client->request('post', $api_url . '/admin/variants/' . $variant['id'] . '/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => 0,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(20000000);
                                    }

                                } catch (ClientException $e) {

                                    if ($e->getResponse()) {
                                        continue;
                                    }
                                }
                            }

                        } catch (ClientException $e) {

                            if ($e->getResponse()) {
                                continue;
                            }

                        }
                    }

                    return response()->json(['status' => 'The resource has been created successfully'], 200);
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

                return response()->json(['status' => 'The resource has been updated successfully'], 200);
            }
        }
    }

    public function create_mercando()
    {
        $input = file_get_contents('php://input');
        $api_url = 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
        $client = new \GuzzleHttp\Client();

        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $verified = $this->verify_webhook(collect($input), $hmac_header);
        $resultapi = error_log('Webhook verified: '.var_export($verified, true));

        if ($resultapi == 'true') {

            $product = json_decode($input, true);

            $response = Product::where('shop', 'mercando')
                ->where('id', $product['id'])
                ->first();

            if(count($response) == 0) {

                Product::createProduct($product, 'nacional', 'mercando');

                foreach ($product['variants'] as $variant) {

                        Variant::createVariant($variant, 'mercando');

                        try {

                            $resb = $client->request('get', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json');

                            $rs = json_decode($resb->getBody(), true);

                            if (count($rs['metafields']) > 0) {

                                foreach ($rs['metafields'] as $r) {

                                    if ($r['key'] == 'points' && $r['namespace'] == 'variants') {

                                        try {

                                            $resc = $client->request('put', $api_url . '/admin/variants/'. $variant['id'] .'/metafields/' . $r['id'] . '.json', array(
                                                    'form_params' => array(
                                                        'metafield' => array(
                                                            'namespace' => 'variants',
                                                            'key' => 'points',
                                                            'value' => 0,
                                                            'value_type' => 'integer'
                                                        )
                                                    )
                                                )
                                            );

                                            $headers = $resc->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(20000000);
                                            }

                                        } catch (ClientException $e) {

                                            if ($e->getResponse()) {
                                                continue;
                                            }
                                        }
                                    }
                                }

                            } else {

                                try {

                                    $resd = $client->request('post', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => 0,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(20000000);
                                    }


                                } catch (ClientException $e) {

                                    if ($e->getResponse()) {
                                        continue;
                                    }
                                }
                            }

                        } catch (ClientException $e) {

                            if ($e->getResponse()) {
                                continue;
                            }
                        }
                    }

                return response()->json(['status' => 'The resource has been created successfully'], 200);
            }

            if (count($response) > 0 ) {

                foreach ($product['variants'] as $variant) {

                    Variant::updateVariant($variant, 'mercando');
                }

                $update = Product::find($response->id);
                $update->shop = 'mercando';
                $update->title = $product['title'];
                $update->image = $product['image'];
                $update->images = $product['images'];
                $update->vendor = $product['vendor'];
                $update->save();

                return response()->json(['status' => 'The resource has been created successfully'], 200);
            }
        }

    }

    public function update()
    {
        $input = file_get_contents('php://input');
        $api_url = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
        $client = new \GuzzleHttp\Client();

        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $verified = $this->verify_webhook(collect($input), $hmac_header);
        $resultapi = error_log('Webhook verified: '.var_export($verified, true));

        if ($resultapi == 'true') {

            $product = json_decode($input, true);

            $response = Product::where('shop', 'good')
                ->where('id', $product['id'])
                ->first();

            if(count($response) == 0) {


                $a = $client->request('GET', $api_url . '/admin/collects.json?product_id=' . $product['id']);
                $headers = $a->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                $x = explode('/', $headers[0]);
                $diferencia = $x[1] - $x[0];
                if ($diferencia < 20) {

                    usleep(20000000);
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

                        try {

                            $resb = $client->request('get', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json');

                            $rs = json_decode($resb->getBody(), true);

                            if (count($rs['metafields']) > 0) {

                                foreach ($rs['metafields'] as $r) {

                                    if ($r['key'] == 'points' && $r['namespace'] == 'variants') {

                                        try {

                                            $resc = $client->request('put', $api_url . '/admin/variants/'. $variant['id'] .'/metafields/' . $r['id'] . '.json', array(
                                                    'form_params' => array(
                                                        'metafield' => array(
                                                            'namespace' => 'variants',
                                                            'key' => 'points',
                                                            'value' => 0,
                                                            'value_type' => 'integer'
                                                        )
                                                    )
                                                )
                                            );

                                            $headers = $resc->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(20000000);
                                            }

                                        } catch (ClientException $e) {

                                            if ($e->getResponse()) {
                                                continue;
                                            }
                                        }
                                    }
                                }

                            } else {

                                try {

                                    $resd = $client->request('post', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => 0,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(20000000);
                                    }


                                } catch (ClientException $e) {

                                    if ($e->getResponse()) {
                                        continue;
                                    }
                                }
                            }

                        } catch (ClientException $e) {

                            if ($e->getResponse()) {
                                continue;
                            }
                        }
                    }

                    return response()->json(['status' => 'The resource has been created successfully'], 200);

                } else {

                    Product::createProduct($product, 'internacional', 'good');

                    foreach ($product['variants'] as $variant) {



                        Variant::createVariant($variant,  'good');

                        try {

                            $resb = $client->request('get', $api_url . '/admin/variants/' . $variant['id'] . '/metafields.json');

                            $rs = json_decode($resb->getBody(), true);

                            if (count($rs['metafields']) > 0) {

                                foreach ($rs['metafields'] as $r) {

                                    if ($r['key'] == 'points' && $r['namespace'] == 'variants') {

                                        try {

                                            $resc = $client->request('put', $api_url . '/admin/variants/' . $variant['id'] . '/metafields/' . $r['id'] . '.json', array(
                                                    'form_params' => array(
                                                        'metafield' => array(
                                                            'namespace' => 'variants',
                                                            'key' => 'points',
                                                            'value' => 0,
                                                            'value_type' => 'integer'
                                                        )
                                                    )
                                                )
                                            );

                                            $headers = $resc->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(20000000);
                                            }


                                        } catch (ClientException $e) {

                                            if ($e->getResponse()) {
                                                continue;
                                            }
                                        }
                                    }
                                }

                            } else {

                                try {

                                    $resd = $client->request('post', $api_url . '/admin/variants/' . $variant['id'] . '/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => 0,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(20000000);
                                    }

                                } catch (ClientException $e) {

                                    if ($e->getResponse()) {
                                        continue;
                                    }
                                }
                            }

                        } catch (ClientException $e) {

                            if ($e->getResponse()) {
                                continue;
                            }
                        }
                    }

                    return response()->json(['status' => 'The resource has been created successfully'], 200);
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

                return response()->json(['status' => 'The resource has been updated successfully'], 200);
            }
        }
    }

    public function update_listing()
    {
        return response()->json(['info' => 'Funciona']);
        $input = file_get_contents('php://input');
        $api_url = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
        $client = new \GuzzleHttp\Client();
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $verified = $this->verify_webhook(collect($input), $hmac_header);
        $resultapi = error_log('Webhook verified: '.var_export($verified, true));

        if ($resultapi == 'true') {

            return response()->json(['info' => 'Se pudo comprobar el webhook']);

            $product = json_decode($input, true);



            $response = Product::where('shop', 'good')
                ->where('id', $product['id'])
                ->first();

            if(count($response) == 0) {


                $a = $client->request('GET', $api_url . '/admin/collects.json?product_id=' . $product['id']);
                $headers = $a->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                $x = explode('/', $headers[0]);
                $diferencia = $x[1] - $x[0];
                if ($diferencia < 20) {

                    usleep(20000000);
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

                        try {

                            $resb = $client->request('get', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json');

                            $rs = json_decode($resb->getBody(), true);

                            if (count($rs['metafields']) > 0) {

                                foreach ($rs['metafields'] as $r) {

                                    if ($r['key'] == 'points' && $r['namespace'] == 'variants') {

                                        try {

                                            $resc = $client->request('put', $api_url . '/admin/variants/'. $variant['id'] .'/metafields/' . $r['id'] . '.json', array(
                                                    'form_params' => array(
                                                        'metafield' => array(
                                                            'namespace' => 'variants',
                                                            'key' => 'points',
                                                            'value' => 0,
                                                            'value_type' => 'integer'
                                                        )
                                                    )
                                                )
                                            );

                                            $headers = $resc->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(20000000);
                                            }

                                        } catch (ClientException $e) {

                                            if ($e->getResponse()) {
                                                continue;
                                            }
                                        }
                                    }
                                }

                            } else {

                                try {

                                    $resd = $client->request('post', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => 0,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(20000000);
                                    }


                                } catch (ClientException $e) {

                                    if ($e->getResponse()) {
                                        continue;
                                    }
                                }
                            }

                        } catch (ClientException $e) {

                            if ($e->getResponse()) {
                                continue;
                            }
                        }
                    }

                    return response()->json(['status' => 'The resource has been created successfully'], 200);

                } else {

                    Product::createProduct($product, 'internacional', 'good');

                    foreach ($product['variants'] as $variant) {



                        Variant::createVariant($variant,  'good');

                        try {

                            $resb = $client->request('get', $api_url . '/admin/variants/' . $variant['id'] . '/metafields.json');

                            $rs = json_decode($resb->getBody(), true);

                            if (count($rs['metafields']) > 0) {

                                foreach ($rs['metafields'] as $r) {

                                    if ($r['key'] == 'points' && $r['namespace'] == 'variants') {

                                        try {

                                            $resc = $client->request('put', $api_url . '/admin/variants/' . $variant['id'] . '/metafields/' . $r['id'] . '.json', array(
                                                    'form_params' => array(
                                                        'metafield' => array(
                                                            'namespace' => 'variants',
                                                            'key' => 'points',
                                                            'value' => 0,
                                                            'value_type' => 'integer'
                                                        )
                                                    )
                                                )
                                            );

                                            $headers = $resc->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(20000000);
                                            }


                                        } catch (ClientException $e) {

                                            if ($e->getResponse()) {
                                                continue;
                                            }
                                        }
                                    }
                                }

                            } else {

                                try {

                                    $resd = $client->request('post', $api_url . '/admin/variants/' . $variant['id'] . '/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => 0,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(20000000);
                                    }

                                } catch (ClientException $e) {

                                    if ($e->getResponse()) {
                                        continue;
                                    }
                                }
                            }

                        } catch (ClientException $e) {

                            if ($e->getResponse()) {
                                continue;
                            }
                        }
                    }

                    return response()->json(['status' => 'The resource has been created successfully'], 200);
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

                return response()->json(['status' => 'The resource has been updated successfully'], 200);
            }
        }

        return response()->json(['info' => 'No se pudo comprobar el webhook']);
    }

    public function update_mercando()
    {
        $input = file_get_contents('php://input');
        $api_url = 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
        $client = new \GuzzleHttp\Client();

        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $verified = $this->verify_webhook(collect($input), $hmac_header);
        $resultapi = error_log('Webhook verified: '.var_export($verified, true));

        if ($resultapi == 'true') {

            $product = json_decode($input, true);

            $response = Product::where('shop', 'mercando')
                ->where('id', $product['id'])
                ->first();

            if(count($response) == 0) {

                Product::createProduct($product, 'nacional', 'mercando');

                foreach ($product['variants'] as $variant) {

                    Variant::createVariant($variant,  'mercando');

                    try {

                        $resb = $client->request('get', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json');

                        $rs = json_decode($resb->getBody(), true);

                        if (count($rs['metafields']) > 0) {

                            foreach ($rs['metafields'] as $r) {

                                if ($r['key'] == 'points' && $r['namespace'] == 'variants') {

                                    try {

                                        $resc = $client->request('put', $api_url . '/admin/variants/'. $variant['id'] .'/metafields/' . $r['id'] . '.json', array(
                                                'form_params' => array(
                                                    'metafield' => array(
                                                        'namespace' => 'variants',
                                                        'key' => 'points',
                                                        'value' => 0,
                                                        'value_type' => 'integer'
                                                    )
                                                )
                                            )
                                        );

                                        $headers = $resc->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                        $x = explode('/', $headers[0]);
                                        $diferencia = $x[1] - $x[0];
                                        if ($diferencia < 10) {
                                            usleep(20000000);
                                        }

                                    } catch (ClientException $e) {

                                        if ($e->getResponse()) {
                                            continue;
                                        }
                                    }
                                }
                            }

                        } else {

                            try {

                                $resd = $client->request('post', $api_url . '/admin/variants/'. $variant['id'] .'/metafields.json', array(
                                    'form_params' => array(
                                        'metafield' => array(
                                            'namespace' => 'variants',
                                            'key' => 'points',
                                            'value' => 0,
                                            'value_type' => 'integer'
                                        )
                                    )
                                ));

                                $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                $x = explode('/', $headers[0]);
                                $diferencia = $x[1] - $x[0];
                                if ($diferencia < 20) {

                                    usleep(20000000);
                                }

                            } catch (ClientException $e) {

                                if ($e->getResponse()) {
                                    continue;
                                }
                            }
                        }

                    } catch (ClientException $e) {

                        if ($e->getResponse()) {
                            continue;
                        }
                    }
                }

                return response()->json(['status' => 'The resource has been created successfully'], 200);

            }

            if (count($response) > 0 ) {

                foreach ($product['variants'] as $variant) {

                    Variant::updateVariant($variant, 'mercando');
                }

                $update = Product::find($response->id);
                $update->shop = 'mercando';
                $update->title = $product['title'];
                $update->image = $product['image'];
                $update->images = $product['images'];
                $update->vendor = $product['vendor'];
                $update->save();

                return response()->json(['status' => 'The resource has been updated successfully'], 200);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
