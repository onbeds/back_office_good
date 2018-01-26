<?php

namespace App\Http\Controllers;

use DB;
use App\Product;
use App\Variant;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;


class VariantsController extends Controller
{

    public function variants(Request $request)
    {
        $variants = DB::table('variants')
            ->join('products', 'variants.product_id', '=', 'products.id')
            ->distinct()
            ->select('products.tipo_producto as tipo', 'variants.id as id', 'products.vendor as vendor', 'variants.title as title', 'variants.price as price', 'variants.sold_units as sold_units', 'variants.percentage as percentage', 'products.title as product')
            ->where('products.shop', 'good')
            ->where('products.id', $request->id)
            ->where('products.handle', '!=', 'example-t-shirt')
            ->get();
        $send = collect($variants);
        return Datatables::of($send)
            ->addColumn('id', function ($send) {
                return '<div align=left>' . $send->id . '</div>';
            })
            ->addColumn('title', function ($send) {
                if ($send->title == 'Default Title') {
                    return '<div align=left>' . $send->product . '</div>';
                } else {
                    return '<div align=left>' . $send->product . ' ' . $send->title . '</div>';
                }
            })
            ->addColumn('tipo', function ($send) {
                return '<div align=left>' . ucwords($send->tipo) . '</div>';
            })
            ->addColumn('vendor', function ($send) {
                return '<div align=left>' . ucwords($send->vendor) . '</div>';
            })
            ->addColumn('price', function ($send) {
                return '<div align=left>' . number_format($send->price) . '</div>';
            })
            ->addColumn('sold_units', function ($send) {
                return '<div align=left>' . number_format($send->sold_units) . '</div>';
            })
            ->addColumn('percentage', function ($send) {
                return '<div align=left><input id='. $send->id .' name='. $send->id .'  type="number" value="'.number_format($send->percentage).'"></div>';
            })
            ->make(true);
    }
    public function variants_mercando(Request $request)
    {
        $variants = DB::table('variants')
            ->join('products', 'variants.product_id', '=', 'products.id')
            ->distinct()
            ->select('products.tipo_producto as tipo', 'variants.id as id', 'products.vendor as vendor', 'variants.title as title', 'variants.price as price', 'variants.sold_units as sold_units', 'variants.percentage as percentage', 'products.title as product')
            ->where('products.tipo_producto', 'nacional')
            ->where('products.id', $request->id)
            ->where('products.handle', '!=', 'example-t-shirt')
            ->where('products.shop', 'mercando')
            ->get(['products.id']);
        $send = collect($variants);
        return Datatables::of($send)
            ->addColumn('id', function ($send) {
                return '<div align=left>' . $send->id . '</div>';
            })
            ->addColumn('title', function ($send) {
                if ($send->title == 'Default Title') {
                    return '<div align=left>' . $send->product . '</div>';
                } else {
                    return '<div align=left>' . $send->product . ' ' . $send->title . '</div>';
                }
            })
            ->addColumn('tipo', function ($send) {
                return '<div align=left>' . ucwords($send->tipo) . '</div>';
            })
            ->addColumn('price', function ($send) {
                return '<div align=left>' . number_format($send->price) . '</div>';
            })
            ->addColumn('sold_units', function ($send) {
                return '<div align=left>' . number_format($send->sold_units) . '</div>';
            })
            ->addColumn('percentage', function ($send) {
                return '<div align=left><input id='. $send->id .' name='. $send->id .'  type="number" value="'.number_format($send->percentage).'"></div>';
            })
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.variants.index');
    }

    public function mercando()
    {
        return view('admin.variants.mercando');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function update(Request $request)
    {
        $api_url = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
        $client = new \GuzzleHttp\Client();

        if (isset($request['value'])) {

            $datas = explode('&', $request['value']);

            foreach ($datas as $data) {

                $r = explode('=', $data);

                $variant = Variant::where('id', $r[0])->where('shop', 'good')->first();




                if (count($variant) > 0) {

                    if ($r[1] != "") {



                        if ((int)$variant->percentage != (int)$r[1]) {



                            DB::table('variants')
                                ->where('id', $r[0])
                                ->where('shop', 'good')
                                ->where('product_id', $variant->product_id)
                                ->update([
                                    'percentage' => $r[1],
                                    'user_id' => currentUser()->id,
                                    'updated_at' => Carbon::now()
                                    ]);

                            try {

                                $res = $client->request('get', $api_url . '/admin/variants/'. $variant->id .'/metafields.json');

                                $results = json_decode($res->getBody(), true);


                                if (count($results['metafields']) > 0) {

                                    foreach ($results['metafields'] as $result) {

                                        if ($result['key'] == 'points' && $result['namespace'] == 'variants') {



                                            try {

                                                $res = $client->request('put', $api_url . '/admin/variants/'. $variant->id .'/metafields/' . $result['id'] . '.json', array(
                                                        'form_params' => array(
                                                            'metafield' => array(
                                                                'namespace' => 'variants',
                                                                'key' => 'points',
                                                                'value' => $r[1],
                                                                'value_type' => 'integer'
                                                            )
                                                        )
                                                    )
                                                );



                                                $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                                $x = explode('/', $headers[0]);
                                                $diferencia = $x[1] - $x[0];
                                                if ($diferencia < 10) {
                                                    usleep(30000000);
                                                }

                                                $r = json_decode($res->getBody(), true);

                                            } catch (ClientException $e) {

                                                if ($e->getResponse()) {
                                                    return response()->json(['data' => $e->getResponse()->getBody()]);
                                                }
                                            }
                                        }
                                    }

                                } else {

                                    try {

                                        $res = $client->request('post', $api_url . '/admin/variants/'. $variant->id .'/metafields.json', array(
                                            'form_params' => array(
                                                'metafield' => array(
                                                    'namespace' => 'variants',
                                                    'key' => 'points',
                                                    'value' => $r[1],
                                                    'value_type' => 'integer'
                                                )
                                            )
                                        ));

                                        $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                        $x = explode('/', $headers[0]);
                                        $diferencia = $x[1] - $x[0];
                                        if ($diferencia < 20) {

                                            usleep(30000000);
                                        }

                                    } catch (ClientException $e) {

                                        if ($e->getResponse()) {
                                            return response()->json(['data' => $e->getResponse()->getBody()]);
                                        }
                                    }
                                }

                            } catch (ClientException $e) {

                                if ($e->getResponse()) {
                                    return response()->json(['data' => $e->getResponse()->getBody()]);
                                }
                            }
                        }
                    }
                }
            }

            return response()->json(['data' => 'actualización terminada']);
        }
    }

    public function update_mercando(Request $request)
    {
        $api_url = 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
        $client = new \GuzzleHttp\Client();

        if (isset($request['value'])) {

            $datas = explode('&', $request['value']);

            foreach ($datas as $data) {

                $r = explode('=', $data);

                $variant = Variant::where('id', $r[0])->where('shop', 'mercando')->first();

                if (count($variant) > 0) {


                    if ($r[1] != "") {

                        DB::table('variants')
                            ->where('id', $r[0])
                            ->where('shop', 'mercando')
                            ->where('product_id', $variant->product_id)
                            ->update([
                                'percentage' => $r[1],
                                'user_id' => currentUser()->id,
                                'updated_at' => Carbon::now()
                            ]);

                        try {

                            $res = $client->request('get', $api_url . '/admin/variants/'. $variant->id .'/metafields.json');

                            $results = json_decode($res->getBody(), true);

                            if (count($results['metafields']) > 0) {

                                foreach ($results['metafields'] as $result) {

                                    if ($result['key'] == 'points' && $result['namespace'] == 'variants') {

                                        try {

                                            $res = $client->request('put', $api_url . '/admin/variants/'. $variant->id .'/metafields/' . $result['id'] . '.json', array(
                                                    'form_params' => array(
                                                        'metafield' => array(
                                                            'namespace' => 'variants',
                                                            'key' => 'points',
                                                            'value' => $r[1],
                                                            'value_type' => 'integer'
                                                        )
                                                    )
                                                )
                                            );

                                            $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(10000000);
                                            }

                                            //return json_decode($res->getBody(), true);

                                        } catch (ClientException $e) {

                                            if ($e->getResponse()) {
                                                continue;
                                            }
                                        }
                                    }
                                }

                            } else {

                                try {

                                    $res = $client->request('post', $api_url . '/admin/variants/'. $variant->id .'/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => $r[1],
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(10000000);
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

                    } else {

                        DB::table('variants')
                            ->where('id', $r[0])
                            ->where('shop', 'mercando')
                            ->where('product_id', $variant->product_id)
                            ->update([
                                'percentage' => $r[1],
                                'user_id' => currentUser()->id,
                                'updated_at' => Carbon::now()
                            ]);

                        try {

                            $res = $client->request('get', $api_url . '/admin/variants/'. $variant->id .'/metafields.json');

                            $results = json_decode($res->getBody(), true);

                            if (count($results['metafields']) > 0) {

                                foreach ($results['metafields'] as $result) {

                                    if ($result['key'] == 'points' && $result['namespace'] == 'variants') {

                                        try {

                                            $res = $client->request('put', $api_url . '/admin/variants/'. $variant->id .'/metafields/' . $result['id'] . '.json', array(
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

                                            $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                            $x = explode('/', $headers[0]);
                                            $diferencia = $x[1] - $x[0];
                                            if ($diferencia < 10) {
                                                usleep(10000000);
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

                                    $res = $client->request('post', $api_url . '/admin/variants/'. $variant->id .'/metafields.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => 0,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    ));

                                    $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                    $x = explode('/', $headers[0]);
                                    $diferencia = $x[1] - $x[0];
                                    if ($diferencia < 20) {

                                        usleep(10000000);
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
                }

            }
            return response()->json(['data' => 'actualización terminada']);
        }
    }

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
