<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\Tercero;
use App\Transactions;
use App\Order;
use App\Helpers\Points;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Session;
use App\Tipo;
use App\Banco;

class TercerosController extends Controller {

    /**
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.terceros.index');
    }

    public function anyData() {

        $referidos = Tercero::selectRaw('id, identificacion, nombres, apellidos, email, (puntos_propios(id) + puntos_amparados(id)) as totalpuntos, rut, cedula, cuenta, state')->get();

        $send = collect($referidos);

        return Datatables::of($send)
                        ->addColumn('action', function ($send ) {
                            if ($send['state'] == true) {
                                return '<div align=center><a href="' . route('admin.terceros.show', $send['id']) . '"  class="btn btn-success btn-sm"><span class="fa fa-user"><span></a></div>';
                            } else {
                                return '';
                            }
                        })
                        ->addColumn('id', function ($send) {
                            return '<div align=left>' . $send['id'] . '</div>';
                        })
                        ->addColumn('identificacion', function ($send) {
                            return '<div align=left>' . $send['identificacion'] . '</div>';
                        })
                        ->addColumn('nombres', function ($send) {
                            return '<div align=left>' . $send['nombres'] . '</div>';
                        })
                        ->addColumn('apellidos', function ($send) {
                            return '<div align=left>' . $send['apellidos'] . '</div>';
                        })
                        ->addColumn('email', function ($send) {
                            return '<div align=left>' . $send['email'] . '</div>';
                        })
                        ->addColumn('totalpuntos', function ($send) {
                            return '<div align=left>' . number_format($send['totalpuntos']) . '</div>';
                        })
                        ->addColumn('state', function ($send) {
                            if ($send['state'] == true) {
                                return '<button class="btn btn-success btn-sm" onclick="cambiarEstado(' . $send['id'] . ',' . $send['state'] . ')">Activo</button>';
                            } else {
                                return '<button class="btn btn-danger btn-sm" onclick="cambiarEstado(' . $send['id'] . ',' . $send['state'] . ')">Inactivo</button>';
                            }
                        })
                        ->addColumn('edit', function ($send) {
                            return '<div align=center><a href="' . route('admin.terceros.edit', $send['id']) . '"  class="btn btn-warning btn-sm">Editar</a></div>';
                        })
                        ->addColumn('rut', function ($send) {
                            if ($send['rut'] == NULL) {
                                return 'Sin RUT';
                            } else {
                                $rut = $send['rut'];
                                return '<div align=center><a href="' . route('admin.terceros.descargar_documentos', ['id' => $send['id'], 'tipo' => 'rut']) . '"  class="btn btn-primary btn-xs"> RUT </a></div>';
                            }
                        })
                        ->addColumn('CC', function ($send) {
                            if ($send['cedula'] == NULL) {
                                return 'Sin cédula o Documento';
                            } else {
                                return '<div align=center><a href="' . route('admin.terceros.descargar_documentos', ['id' => $send['id'], 'tipo' => 'cedula']) . '"  class="btn btn-success btn-xs"> Cédula o Documento </a></div>';
                            }
                        })
                        ->addColumn('BANK', function ($send) {
                            if ($send['cuenta'] == NULL) {
                                return 'Sin certificación bancaria';
                            } else {
                                return '<div align=center><a href="' . route('admin.terceros.descargar_documentos', ['id' => $send['id'], 'tipo' => 'cuenta']) . '"  class="btn btn-warning btn-xs"> Certificación bancaria  </a></div>';
                            }
                        })
                        ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return view('admin.terceros.show', compact('id'));
    }

    public function anyShow(Request $request) {

        $results = DB::table('terceros as t')
                ->select('t2.id', 't2.nombres', 't2.apellidos', 't2.email')
                ->join('terceros_networks as tn', 'tn.padre_id', '=', 't.id')
                ->join('terceros as t2', 't2.id', '=', 'tn.customer_id')
                ->where('t.id', $request->id)
                ->get();

        $send = collect($results);

        return Datatables::of($send)
                        ->addColumn('id', function ($send) {
                            return '<div align=left>' . $send->id . '</div>';
                        })
                        ->addColumn('nombres', function ($send) {
                            return '<div align=left>' . $send->nombres . '</div>';
                        })
                        ->addColumn('email', function ($send) {
                            return '<div align=left>' . $send->email . '</div>';
                        })
                        ->addColumn('apellidos', function ($send) {
                            return '<div align=left>' . $send->apellidos . '</div>';
                        })
                        ->make(true);
    }

    public function edit($id) {
        $tercero = Tercero::find($id);

        if ($tercero->state === true) {
            return view('admin.terceros.edit', compact('tercero'));
        }
    }

    public function update(Request $request, $id) {
        $state = $request['state'];

        if ($state === 'false') {

            $tercero = Tercero::with('networks')->find($id);
            $tercero->state = $state;
            $tercero->numero_referidos = null;


            $networks = $tercero['networks'];
            $father = $networks[0]['pivot']['padre_id'];


            if (!is_null($father)) {

                $up = Tercero::find($father);
                $up->numero_referidos = $up->numero_referidos - 1;
                $up->save();
                $referidos = DB::table('terceros_networks')->where('padre_id', $tercero->id)->get();

                if (count($referidos) > 0) {

                    foreach ($referidos as $referido) {

                        DB::table('terceros_networks')->where('customer_id', $referido->customer_id)->update(['padre_id' => $father]);

                        DB::insert('insert into referidos_logs (tercero_id, old_father, new_father, created_at, updated_at) values (?, ?, ?, ?, ?)', [
                            $referido->customer_id,
                            $referido->padre_id,
                            $father,
                            Carbon::now(),
                            Carbon::now(),
                        ]);

                        $update = Tercero::find($father);
                        $update->numero_referidos = $update->numero_referidos + 1;
                        $update->save();
                    }
                }
            }

            if (is_null($father)) {

                $referidos = DB::table('terceros_networks')->where('padre_id', $tercero->id)->get();

                if (count($referidos) > 0) {

                    foreach ($referidos as $referido) {
                        DB::table('terceros_networks')->where('customer_id', $referido->customer_id)->update(['padre_id' => null]);
                        DB::insert('insert into referidos_logs (tercero_id, old_father, new_father, created_at, updated_at) values (?, ?, ?, ?, ?)', [
                            $referido->customer_id,
                            $referido->padre_id,
                            null,
                            Carbon::now(),
                            Carbon::now(),
                        ]);
                    }
                }
            }

            DB::table('terceros_logs')->insert([
                'tercero_id' => $tercero->id,
                'padre_id' => $father,
                'user' => currentUser()->nombre_completo,
                'ip' => $request->ip(),
                'browser' => $request->server('HTTP_USER_AGENT'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            $tercero->save();

            return redirect('admin/terceros')->with(['status' => 'Se han hechos los cambios correctamente']);
        } else {

            return redirect('admin/terceros')->with(['status' => 'No se han hecho cambios.']);
        }
    }

    public function getData(Request $request) {
        if ($request->has('textbuscar') && $request->has('id')) {
            $data = ['error' => false];
            $tercero = Tercero::with('networks')
                    ->where('email', '=', "" . strtolower($request['textbuscar']) . "")
                    ->orWhere("identificacion", '=', "" . strtolower($request['textbuscar']) . "")
                    ->first();

            if (count($tercero) > 0) {
                $networks = $tercero['networks'];
                $father = count($networks) > 0 ? $networks[0]['pivot']['padre_id'] : NULL;

                $tipo_cliente = \App\Entities\Tipo::find($tercero->tipo_cliente_id)->nombre;
                $data['tercero'] = ['id' => $tercero->id,
                    'nombre' => "$tercero->nombres $tercero->apellidos",
                    'identificacion' => $tercero->identificacion,
                    'direccion' => $tercero->direccion,
                    'telefono' => $tercero->telefono,
                    'email' => $tercero->email,
                    'tipo_cliente' => $tipo_cliente,
                    'tipo_cliente_id' => $tercero->tipo_cliente_id,
                    'error' => false];

                if (!is_null($father)) {
                    $padre = Tercero::find($father);
                    $tipo_padre = \App\Entities\Tipo::find($padre->tipo_cliente_id)->nombre;
                    $data['padre'] = ['nombre' => "$padre->nombres $padre->apellidos", 'email' => $padre->email, 'tipo_cliente' => $tipo_padre];
                    echo json_encode($data);
                } else {
                    $data['tercero']['error'] = '¡No se encuentra el usuario padre!';
                    echo json_encode($data);
                }
            } else {
                $data = array('error' => '¡No se encuentra el usuario tercero!');
                echo json_encode($data);
            }
        }
    }

    public function getPadre(Request $request) {
        if ($request->has('identificacion') && $request->has('_token')) {
            $data = ['error' => false];
            $padre = Tercero::where("identificacion", '=', "" . $request['identificacion'] . "")->first();

            if (count($padre) > 0) {
                if ($padre->identificacion != $request['tercero']) {
                    if ($padre->tipo_cliente_id == 83) {
                        $tipo_padre = \App\Entities\Tipo::find($padre->tipo_cliente_id)->nombre;
                        $data = ['id' => $padre->id, 'nombre' => "$padre->nombres $padre->apellidos", 'email' => $padre->email, 'tipo_cliente' => $tipo_padre];
                        echo json_encode($data);
                    } else {
                        $data['error'] = '¡El usuario padre no es un vendedor!';
                        echo json_encode($data);
                    }
                } else {
                    $data['error'] = '¡El usuario padre no puede ser el mismo usuario!';
                    echo json_encode($data);
                }
            } else {
                $data['error'] = '¡No se encuentra el usuario padre!';
                echo json_encode($data);
            }
        }
    }

    public function setData() {
        if (isset($_POST['id']) && isset($_POST['_token'])) {

            $api_url_good = 'https://' . env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
            $api_url_mercando = 'https://' . env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');

            $model = Tercero::find($_POST['id']);
            $email_old = $model->email;
            $model->identificacion = $_POST['identificacion'];
            $model->email = strtolower($_POST['email']);
            $model->usuario = strtolower($_POST['email']);
            $model->direccion = $_POST['direccion'];
            $model->telefono = $_POST['telefono'];
            $model->tipo_cliente_id = $_POST['tipo_cliente_id'];
            if ($model->save()) {
                $this->api_set_email($api_url_good, $email_old, $model->email);
                $this->api_set_email($api_url_mercando, $email_old, $model->email);

                echo true;
            } else {
                echo 'Hubo un error al actualizar los datos';
            }
        }
    }

    public function api_set_email($api, $email_old, $email) {

        $client = new \GuzzleHttp\Client();

        try {
            $good = $client->request('GET', $api . '/admin/customers/search.json?query=email:' . $email_old);
            $headers = $good->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];
            if ($diferencia < 20) {
                usleep(10000000);
            }

            $results = json_decode($good->getBody(), true);
            if (count($results['customers']) > 0) {

                try {
                    $res = $client->request('put', $api . '/admin/customers/' . $results['customers'][0]['id'] . '.json', array(
                        'form_params' => array(
                            'customer' => array(
                                "email" => $email,
                            )
                        )
                            )
                    );

                    $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                    $x = explode('/', $headers[0]);
                    $diferencia = $x[1] - $x[0];
                    if ($diferencia < 20) {
                        usleep(10000000);
                    }
                } catch (ClientException $e) {

                    if ($e->hasResponse()) {

                        return redirect()->back()->with(['err' => 'Se actualizó su email en el backoffice pero el usuario no existe en tiendagood']);
                    }
                }
            }
        } catch (ClientException $e) {

            if ($e->hasResponse()) {

                return redirect()->back()->with(['err' => 'Se actualizó su email en el backoffice pero el usuario no existe en tiendagood']);
            }
        }
    }

    public function Compresion() {

        $models = DB::select('SELECT fcompresion()');
        foreach ($models as $compresion) {
            print_r($compresion);
        }
    }

    public function setPadre(Request $request) {
        if ($request->has('tercero_id') && $request->has('padre_id')) {

            $tercero = Tercero::with('networks')
                    ->where('id', '=', $request['tercero_id'])
                    ->first();

            if (count($tercero) > 0) {
                $networks = $tercero['networks'][0]['pivot'];
                $update = DB::table('terceros_networks')->where('customer_id', $networks->customer_id)->update(['padre_id' => $request['padre_id']]);
                if ($update) {
                    DB::insert('insert into referidos_logs (tercero_id, old_father, new_father, created_at, updated_at) values (?, ?, ?, ?, ?)', [
                        $networks->customer_id,
                        $networks->padre_id,
                        $request['padre_id'],
                        Carbon::now(),
                        Carbon::now(),
                    ]);

                    DB::table('terceros_logs')->insert([
                        'tercero_id' => $networks->customer_id,
                        'padre_id' => $request['padre_id'],
                        'user' => currentUser()->nombre_completo,
                        'ip' => $request->ip(),
                        'browser' => $request->server('HTTP_USER_AGENT'),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    echo true;
                } else {
                    echo 'Hubo un error al actualizar los datos';
                }
            }
        } else {
            
        }
    }

    public function editarDatos() {
        return view('admin.terceros.editardatos');
    }

    public function cambiarPadre() {
        return view('admin.terceros.cambiarpadre');
    }

    function asignarOrden() {
        return view('admin.terceros.asignarorden');
    }

    public function getOrden(Request $request) {
        if ($request->has('no_orden')) {
            $data = ['error' => false];
            $orden = DB::table('orders')
                    ->where('name', strtoupper($request['no_orden']))
                    ->first();
            if ($orden != NULL) {
                $data = ['id' => $orden->id, 'tienda' => $orden->shop, 'estado' => $orden->financial_status];
                echo json_encode($data);
            } else {
                $data['error'] = '¡No se encuentra la orden de venta!';
                echo json_encode($data);
            }
        }
    }

    public function setOrden(Request $request) {
        if ($request->has('tercero_id') && $request->has('orden_id')) {
            $tercero = DB::table('terceros')
                    ->where('id', $request['tercero_id'])
                    ->first();
            if ($tercero != NULL) {
                $update = DB::table('orders')->where('id', $request['orden_id'])->update(['tercero_id' => $tercero->id]);
                if ($update) {
                    echo true;
                } else {
                    echo 'Hubo un error al actualizar los datos';
                }
            } else {
                echo 'Hubo un error al encontrar el tercero';
            }
        }
    }

    public function setState(Request $request) {
        if ($request->has('id')) {
            $tercero = Tercero::where('id', $request['id'])->first();
            if ($tercero != NULL) {
                if ($tercero->state) {
                    $respuesta = DB::select('SELECT finactivar_tercero(' . $tercero->id . ')');
                    if ($respuesta[0]->finactivar_tercero == 'INACTIVADO CON EXITO') {
                        Session::flash('flash_msg', 'Se inactivo correctamente el usuario tercero');
                        echo true;
                    } else {
                        Session::flash('flash_msg', 'Hubo un error al inactivar el usuario tercero');
                        echo false;
                    }
                } else {
                    $tercero->state = TRUE;
                    $update = $tercero->update();
                    echo $update;
                }
            } else {
                Session::flash('flash_msg', 'Hubo un error al encontrar el tercero');
                echo false;
            }
        }
    }

    public function lista_documentos() {
        return view('admin.terceros.lista_documentos');
    }

    public function descargar_documentos($id = 0, $tipo = '') {
        $tercero = Tercero::with('networks')->where('id', '=', $id)->first();
        $nombre = '';
        if ($tipo == 'rut') {
            $nombre = $tercero->rut;
        }
        if ($tipo == 'cedula') {
            $nombre = $tercero->cedula;
        }
        if ($tipo == 'cuenta') {
            $nombre = $tercero->cuenta;
        }

        if ($nombre != '0') {
            $download = public_path($nombre);
            return response()->download($download);
        } else {
            return view('admin.terceros.lista_documentos');
        }
    }

    public function activar_plan_prime(Request $request) {

        $tercero = Tercero::with('primes')->find(currentUser()->id);

        $fecha_inicio = '';
        $fecha_final = '';

        foreach ($tercero->primes as $value) {
            if ($value->estado == true) {
                $fecha_inicio = $value->fecha_inicio;
                $fecha_final = $value->fecha_final;
            }
        }

        $fecha_inicio = $fecha_inicio;
        $fecha_inicio = strtotime($fecha_inicio);
        $fecha_inicio = date("Y-m-d", $fecha_inicio);
        $fecha_final = $fecha_final;
        $fecha_final = strtotime($fecha_final);
        $fecha_final = date("Y-m-d", $fecha_final);

        if (date("Y-m-d") >= $fecha_inicio && date("Y-m-d") <= $fecha_final) {
            
        } else {
            $usuario = Tercero::find(currentUser()->id);
            $usuario->primes()->create([
                'fecha_inicio' => Carbon::now(),
                'fecha_final' => Carbon::now()->addMonth(),
                'log' => [
                    'id' => $request->getClientIp(),
                    'browser' => $request->header('User-Agent')
                ]
            ]);
        }
        //echo  $fecha_inicio;
    }

    public function actualizar_mis_datos(Request $request) {

        if ($request->has('first-name')) {

            $api_url_good = 'https://' . env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
            $api_url_mercando = 'https://' . env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');

            $this->api_set_email($api_url_good, $request['email_old'], $request->email);
            $this->api_set_email($api_url_mercando, $request['email_old'], $request->email);

            $p = '';
            if (strlen($request->phone) == 13) {
                $p .= ltrim($request->phone, '+57');
            } else {
                $p .= $request->phone;
            }

            $usuario = Tercero::find(currentUser()->id);
            $usuario->nombres = strtolower($request['first-name']);
            $usuario->apellidos = strtolower($request['last-name']);
            $usuario->direccion = strtolower($request->address);
            $usuario->telefono = strtolower($p);
            $usuario->email = strtolower($request->email);
            $usuario->usuario = strtolower($request->email);
            $usuario->celular = strtolower($p);
            $usuario->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->birthday);
 
            if (strlen($request->tipocuenta_id) >= 1) {  $usuario->tipocuenta_id = $request->tipocuenta_id;   }else{  $usuario->tipocuenta_id = null;  }
            if (strlen($request->banco_id) >= 1) {  $usuario->banco_id = $request->banco_id;   }else{  $usuario->banco_id = null;  }
            if (strlen($request->numero_cuenta) >= 1) {  $usuario->numero_cuenta = $request->numero_cuenta;   }else{  $usuario->numero_cuenta = null;  }
            

            if ($request->file('banco')) {

                $path = public_path() . "/" . $request['cuenta_old'];
                @unlink($path);
                $cuenta = $request->file('banco');
                $cuenta_nombre = str_random(30) . "." . $cuenta->getClientOriginalExtension();
                $path = public_path() . "/uploads";
                $cuenta->move($path, $cuenta_nombre);
                $usuario->cuenta = "uploads/" . $cuenta_nombre;
            }

            if ($request->file('cedula')) {

                $path = public_path() . "/" . $request['cedula_old'];
                @unlink($path);

                $cuenta = $request->file('cedula');
                $cuenta_nombre = str_random(30) . "." . $cuenta->getClientOriginalExtension();
                $path = public_path() . "/uploads";
                $cuenta->move($path, $cuenta_nombre);
                $usuario->cedula = "uploads/" . $cuenta_nombre;
            }

            if ($request->file('rut')) {

                $path = public_path() . "/" . $request['rut_old'];
                @unlink($path);
                $cuenta = $request->file('rut');
                $cuenta_nombre = str_random(30) . "." . $cuenta->getClientOriginalExtension();
                $path = public_path() . "/uploads";
                $cuenta->move($path, $cuenta_nombre);
                $usuario->rut = "uploads/" . $cuenta_nombre;
            }

            if ($request->file('foto')) {

                $path = public_path() . "/" . $request['foto_old'];
                @unlink($path);
                $cuenta = $request->file('foto');
                $cuenta_nombre = str_random(30) . "." . $cuenta->getClientOriginalExtension();
                $path = public_path() . "/uploads";
                $cuenta->move($path, $cuenta_nombre);
                $usuario->avatar = "uploads/" . $cuenta_nombre;
            }

            // Usuario creado
            $usuario->save();

            if ($request->has('prime')) {
                $usuario->primes()->create([
                    'fecha_inicio' => Carbon::now(),
                    'fecha_final' => Carbon::now()->addMonth(),
                    'log' => [
                        'id' => $request->getClientIp(),
                        'browser' => $request->header('User-Agent')
                    ]
                ]);
            }

            Session::flash('flash_msg', 'La actualizaci\u00F3n de sus datos se realizaron correctamente');
            return redirect()->action('TercerosController@actualizar_mis_datos');
        }

        $tercero = Tercero::with('networks', 'primes')->find(currentUser()->id);

        $fecha_inicio = '';
        $fecha_final = '';

        foreach ($tercero->primes as $value) {
            if ($value->estado == true) {
                $fecha_inicio = $value->fecha_inicio;
                $fecha_final = $value->fecha_final;
            }
        }

        $fecha_inicio = $fecha_inicio;
        $fecha_inicio = strtotime($fecha_inicio);
        $fecha_inicio = date("Y-m-d", $fecha_inicio);

        $fecha_final = $fecha_final;
        $fecha_final = strtotime($fecha_final);
        $fecha_final = date("Y-m-d", $fecha_final);

        $fecha_nacimiento = $tercero['fecha_nacimiento'];
        $fecha_nacimiento = date("d/m/Y", strtotime($fecha_nacimiento));


        $tipos= Tipo::with('tipos')->find(76);
        $cuentas = Tipo::with('tipos')->find(75);
        $documentos = Tipo::with('tipos')->find(74);
        $bancos = Banco::get();

        return view('admin.terceros.actualizar_datos', compact('tercero', 'fecha_nacimiento', 'fecha_inicio', 'fecha_final', 'tipos', 'cuentas', 'bancos'));
    }

    public function index_search()
    {
        return view('admin.terceros.search');
    }

    public function searching(Request $request)
    {
        if ($request->has('search')) {

            $search = $request->search;

            $puntos = 0;

            $tercero = Tercero::where('identificacion', $search)->Orwhere('email', $search)->first();

            if (count($tercero) > 0 ) {

                $good = 0;
                $mercando = 0;

                $shops = DB::select(
                    DB::raw(
                        "
                            SELECT *
                            FROM terceros_tiendas
                            WHERE tercero_id = '$tercero->id';
                        "
                    )
                );

                $tipo = DB::select(
                    DB::raw(
                        "
                            SELECT tp.nombre
                            FROM terceros t
                            INNER JOIN tipos tp ON t.tipo_id = tp.id
                            WHERE t.id = '$tercero->id';
                        "
                    )
                );

                $vendedor = DB::select(
                    DB::raw(
                        "
                            SELECT tp.nombre
                            FROM terceros t
                            INNER JOIN tipos tp ON t.tipo_cliente_id = tp.id
                            WHERE t.id = '$tercero->id';
                        "
                    )
                );

                $padre = DB::select(
                    DB::raw(
                        "
                            SELECT coalesce(t.nombres || ' ' || t.apellidos) as nombres, t.identificacion
                            FROM terceros t
                            INNER JOIN terceros_networks tk ON t.id = tk.padre_id
                            WHERE tk.customer_id = '$tercero->id';
                        "
                    )
                );

                $ultima = DB::select(
                    DB::raw(
                        "
                            SELECT  t.nombres, t.apellidos, ld.nivel, o.name,  ld.puntos, ld.comision_puntos, ld.valor_comision, ld.created_at
                            FROM liquidaciones_detalles ld
                                INNER JOIN orders o ON ld.order_id = o.id
                                INNER JOIN terceros t ON ld.hijo_id = t.id
                            WHERE ld.liquidacion_id IN (
                                  SELECT lt.liquidacion_id
                                  FROM liquidaciones_terceros lt
                                  WHERE lt.tercero_id = '$tercero->id'
                                  ORDER BY lt.id DESC LIMIT 1)
                            AND ld.tercero_id = '$tercero->id';
                        "
                    )
                );

                $descuentos = DB::select(
                    DB::raw(
                        "
                           SELECT lt.*
                            FROM liquidaciones_terceros lt
                            WHERE lt.tercero_id = '$tercero->id'
                            ORDER BY lt.id DESC LIMIT 1;
                        "
                    )
                );

                if (count($ultima) == 0) {
                    $ultima = 'Sin liquidaciones';
                }

                if (count($descuentos) == 0) {
                    $descuentos = 'Sin descuentos';
                }


                if (count($tipo) > 0) {
                    $tipo = $tipo[0]->nombre;
                } else {
                    $tipo = 'Sin tipo';
                }

                if (count($vendedor) > 0) {
                    $vendedor = $vendedor[0]->nombre;
                } else {
                    $vendedor = 'Sin tipo';
                }


                if (count($shops) > 0) {
                    $mercando = $shops[0]->customer_id_mercando;
                    $good = $shops[0]->customer_id_good;
                }

                if (count($padre) > 0) {
                    $padre = $padre[0];
                } else {
                    $padre = [];
                }

                $puntos = Points::count_own_points($tercero->id);

                return response()->json(['info' => $tercero, 'good' => $good, 'mercando' => $mercando, 'tipo' => $tipo, 'vendedor' => $vendedor, 'puntos' => $puntos, 'padre' => $padre, 'liquidacion' => $ultima, 'descuentos' => $descuentos]);
            }

            return response()->json(['msg' => 'not found information']);

        }

        return response()->json(['error' => 'not found variable search']);
    }

    public function levels(Request $request)
    {

        if ($request->has('level') && $request->has('padre_id')) {

            if ($request->level == 1) {

                $id = (int)$request->padre_id;

                $uno = DB::select(
                    DB::raw(
                        "
                            SELECT  t2.id, t2.nombres,  t2.apellidos, t2.email
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

                $send = collect($uno);

                return Datatables::of($send)
                    ->addColumn('id', function ($send) {
                        return '<div align=left>' . $send->id . '</div>';
                    })
                    ->addColumn('nombres', function ($send) {
                        return '<div align=left>' . $send->nombres . '</div>';
                    })

                    ->addColumn('apellidos', function ($send) {
                        return '<div align=left>' . $send->apellidos . '</div>';
                    })
                    ->addColumn('email', function ($send) {
                        return '<div align=left>' . $send->email . '</div>';
                    })
                    ->addColumn('level_1', function ($send) {
                        $t = Points::terceros_by_level($send->id, 1);
                        return '<div align=left>' . count($t) . '</div>';
                    })
                    ->make(true);

            }

            if ($request->level == 2) {

                $id = (int)$request->padre_id;

                $uno = DB::select(
                    DB::raw(
                        "
                            SELECT t3.id, t3.nombres,  t3.apellidos, t3.email
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

                $send = collect($uno);

                return Datatables::of($send)
                    ->addColumn('id', function ($send) {
                        return '<div align=left>' . $send->id . '</div>';
                    })
                    ->addColumn('nombres', function ($send) {
                        return '<div align=left>' . $send->nombres . '</div>';
                    })

                    ->addColumn('apellidos', function ($send) {
                        return '<div align=left>' . $send->apellidos . '</div>';
                    })
                    ->addColumn('email', function ($send) {
                        return '<div align=left>' . $send->email . '</div>';
                    })
                    ->addColumn('level_1', function ($send) {
                        $t = Points::terceros_by_level($send->id, 1);
                        return '<div align=left>' . count($t) . '</div>';
                    })
                    ->make(true);

            }

            if ($request->level == 3) {

                $id = (int)$request->padre_id;

                $uno = DB::select(
                    DB::raw(
                        "
                            SELECT t4.id as id, t4.nombres as nombres,  t4.apellidos as apellidos, t4.email as email
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

                $send = collect($uno);

                return Datatables::of($send)
                    ->addColumn('id', function ($send) {
                        return '<div align=left>' . $send->id . '</div>';
                    })
                    ->addColumn('nombres', function ($send) {
                        return '<div align=left>' . $send->nombres . '</div>';
                    })

                    ->addColumn('apellidos', function ($send) {
                        return '<div align=left>' . $send->apellidos . '</div>';
                    })
                    ->addColumn('email', function ($send) {
                        return '<div align=left>' . $send->email . '</div>';
                    })
                    ->addColumn('level_1', function ($send) {
                        $t = Points::terceros_by_level($send->id, 1);
                        return '<div align=left>' . count($t) . '</div>';
                    })
                    ->make(true);

            }

        }
    }

}
