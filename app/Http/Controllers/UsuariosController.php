<?php
namespace App\Http\Controllers;

use App\Banco;
use App\Prime;
use DB;
use App\Tipo;
use App\Documento;
use App\TipoCliente;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Validator;
use App\Entities\Ciudad;
use App\Entities\Oficina;
use App\Entities\Tercero;
use App\Entities\Network;
use App\Entities\Tercero_network;
use App\Http\Controllers\Controller;
use App\Http\Requests\Terceros\Usuarios\EditaUsuario;
use App\Http\Requests\Terceros\Usuarios\NuevoUsuario;
use App\Http\Requests\Terceros\Usuarios\NuevoUsuarioexterno;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;
use Illuminate\Http\Request;
use Styde\Html\Facades\Alert;
use Yajra\Datatables\Datatables;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use App\Helpers\GuzzleHttp;

use Mail;

class UsuariosController extends Controller {
    /**
     * Display a listing of the  resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function terms()
    {
        return view('admin.terms.terms');
    }
    
    public function termsprime()
    {
        return view('admin.terms.termsprime');
    }

    public function verified_email(Request $request)
    {
        if ($request->has('email')) {

            $email = Tercero::where('email', strtolower($request->email))->first();

            if (count($email) > 0) {
                if($email->state == true){  
                    return response()->json(['err' => 'email existe'], 200);
                }
                else{
                    return response()->json(['err' => 'desactivado'], 200);
                }
            } else {
                return response()->json(['msg' => 'email valido'], 200);
            }


        } else {
            return response()->json(['err' => 'Falta el parametro email'], 200);
        }

    }

    public function verified_phone(Request $request)
    {
        if ($request->has('phone')) {

            $p = '';

            if (strlen($request->phone) == 13) {
                $p .= ltrim( $request->phone , '+57' );
            }else {
                $p .= $request->phone;
            }

            $phone = Tercero::where('telefono', '=' , '' . $p .'')->first();

            if (count($phone) > 0) {
                if($phone->state == true){   
                    return response()->json(['err' => 'telefono existe'], 200);
                }
                else{
                    return response()->json(['err' => 'desactivado'], 200);
                }
            } else {
                return response()->json(['msg' => 'telefono valido'], 200);
            }

        } else {
            return response()->json(['err' => 'Falta el parametro phone'], 200);
        }


    }

    public function verified_dni(Request $request)
    {
        if ($request->has('dni')) {

            $dni = Tercero::where('identificacion', strtolower($request->dni))->first();

            if (count($dni) > 0 ) {
                if($dni->state == true){     
                    return response()->json(['err' => 'dni no valido'], 200);
                }
                else{
                    return response()->json(['err' => 'desactivado'], 200);
                }
            } else {
                return response()->json(['msg' => 'dni valido'], 200);
            }

        } else {
            return response()->json(['err' => 'Falta el parametro dni'], 200);
        }
    }

    public function verified_code(Request $request)
    {
        if ($request->has('code')) {

            $code = Tercero::where(DB::raw('UPPER(identificacion)'), strtoupper($request->code))->first();

            if (count($code) > 0 && $code->tipo_cliente_id == 83 && $code->state == true) {
                return response()->json(['msg' => 'código valido'], 200);
            } else {
                return response()->json(['err' => 'código no valido'], 200);
            }


        } else {
            return response()->json(['err' => 'Falta el parametro code'], 200);
        }
    }

    public function index()
    {
        return $permisos = Permission::lists('name', 'id')->get();
        return view('admin.usuarios.index', compact('permisos'));
    }

    public function anyData()
    {
        $usuarios = Tercero::select('terceros.id', 'terceros.avatar', 'terceros.identificacion', 'terceros.nombres', 'terceros.apellidos', 'terceros.direccion', 'ciudades.nombre as ciudad', 'terceros.email', 'roles.name as rol', 'tipos.nombre as tipo')
            ->leftjoin('ciudades', 'terceros.ciudad_id', '=', 'ciudades.id')
            ->leftjoin('roles', 'terceros.rol_id', '=', 'roles.id')
            ->leftjoin('tipos','tipos.id','=','terceros.tipo_id')
            ->orderby('terceros.id');
        return Datatables::of($usuarios)
            ->addColumn('identificacion', function ($usuarios) {
                return '<div align=left>'.$usuarios->identificacion.'</div>';
            })
            ->addColumn('nombres', function ($usuarios) {
                return '<div align=left>'.$usuarios->nombres.'</div>';
            })
            ->addColumn('apellidos', function ($usuarios) {
                return '<div align=left>'.$usuarios->apellidos.'</div>';
            })
            ->addColumn('direccion', function ($usuarios) {
                return '<div align=left>'.$usuarios->direccion.'</div>';
            })
            ->addColumn('ciudad', function ($usuarios) {
                return '<div align=left>'.$usuarios->ciudad.'</div>';
            })
            ->addColumn('rol', function ($usuarios) {
                return '<div align=left>'.$usuarios->rol.'</div>';
            })
            ->addColumn('tipo', function ($usuarios) {
                return '<div align=left>'.$usuarios->tipo.'</div>';
            })
            ->addColumn('permisos', function ($usuarios) {
                return '
                    <div align=left>
                        <a data-toggle="modal" tercero_id="' . $usuarios->id . '" data-target="#permisos" class="btn btn-primary btn-xs get-permisos" OnClick="get_permisos(' . $usuarios->id . ');">Permisos</a>
                    </div>
                ';
            })
            ->addColumn('action', function ($usuarios) {
                return '
                    <div align=left>
                        <a  href="' . route('admin.usuarios.edit', $usuarios->id) . '"  class="btn btn-warning btn-xs">
                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                        </a>
                        <a href="' . route('admin.usuarios.destroy', $usuarios->id) . '"  onclick="return confirm(\'¿ Desea eliminar el registro seleccionado ?\')" class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                        </a>
                    </div>
                ';
            })
            ->editColumn('avatar', function ($usuarios) {
                if ($usuarios->avatar !== NULL) {
                    return '<div align=left><img src="' . asset($usuarios->avatar) . '" class="img-circle avatar_table" /></div> ';
                } else {
                    return '<div align=left><img src="' . asset('img/avatar-bg.png') . '" class="img-circle avatar_table"/></div>';
                }
            })
            ->make(true);
    }
    public function hijos($id)
    {
        $usuarios = Tercero::tipoUsuario(2)->with('ciudad')->orderby('id')->paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ciudades   = Ciudad::get()->lists('nombre_completo', 'id');
        $tipos = Tipo::get()->lists('nombre','id');//->toarray();
        $oficinas =  Oficina::lists('nombre', 'id');
        $roles      = Role::lists('name', 'id');
        $clientes   = Tercero::tipoUsuario(3)->get()->lists('nombre_completo', 'id')->toArray();
        $red = Network::lists('name','id');
        return view('admin.usuarios.create', compact('ciudades','tipos', 'oficinas', 'roles', 'clientes','red'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @return \Illuminate\Http\Response
     */
    public function store(NuevoUsuario $request)
    {
        $avatar = $request->file('avatar');
        $query = Tercero::select('id')->where('email', '=', ($request->email_Patrocinador))->first();
        if ($avatar) {
            $avatar_nombre = str_random(30) . "." . $avatar->getClientOriginalExtension();
            $path = public_path() . "/uploads/avatar/";
            $avatar->move($path, $avatar_nombre);
        }
        $usuario = new Tercero($request->all());
        $usuario->save();
        if ($avatar) {
            $usuario->avatar = "uploads/avatar/" . $avatar_nombre;
        }
        $usuario->contraseña = bcrypt($request->contraseña);
        $usuario->tipo_id = $request->tipo_id;
        if ($request->cliente) {
            $usuario->padre_id = $request->cliente;
        }
        $usuario->rol_id = $request->rol_id;
        $usuario->usuario_id = currentUser()->id;
        $padre = Tercero::find($query->id);
        $padre->numero_referidos = $padre->numero_referidos + 1;
        $usuario->ip = $request->ip();
        $usuario->networks()->attach(1, ['padre_id' => $padre->id]);
        $padre->save();
        $usuario->save();
        $permisos = Role::findOrFail($request->rol_id)->permissions;
        foreach ($permisos as $per) {
            $permiso = Permission::findOrFail($per->id);
            $usuario = Tercero::findOrFail($usuario->id);
            $usuario->attachPermission($permiso);
        }
        Alert::message("! Usuario registrado con éxito !  ", 'success');
        return redirect()->route('admin.usuarios.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $usuario    = Tercero::findOrFail($id);
        $ciudades   = Ciudad::get()->lists('nombre_completo', 'id');
        $tipos = Tipo::get()->lists('nombre','id');//TipoIdenti(tipo_id)->
        $oficinas = Oficina::lists('nombre', 'id');
        $roles      = Role::lists('name', 'id');
        $clientes   = Tercero::tipoUsuario(3)->get()->lists('nombre_completo', 'id')->toArray();
        return view('admin.usuarios.edit', compact('usuario', 'tipos','ciudades', 'oficinas', 'roles', 'clientes'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditaUsuario $request, $id)
    {
        $usuario = Tercero::findOrFail($id);
        $usuario->fill($request->all());
        if ($request->file("avatar")) {
            $avatar        = $request->file('avatar');
            $avatar_nombre = str_random(30) . "." . $avatar->getClientOriginalExtension();
            $path          = public_path() . "/uploads/avatar/";
            $avatar->move($path, $avatar_nombre);
            $usuario->avatar = "uploads/avatar/" . $avatar_nombre;
        }

        $usuario->rol_id      = $request->rol_id;
        $usuario->usuario_id  = currentUser()->id;
        $usuario->ip          = $request->ip();
        $usuario->tipo_id    = $request->tipo_id;


        $usuario->save();
        Alert::message('! Usuario ' . $usuario->nombres . " " . $usuario->apellidos . " Actualizado con éxito ! ", 'success');
        return redirect()->route('admin.usuarios.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = Tercero::findOrFail($id);
        $usuario->delete();
        Alert::message('! Usuario ' . $usuario->nombres . " " . $usuario->apellidos . " eliminado con éxito! ", 'success');
        return redirect()->route('admin.usuarios.index');
    }

    protected function getusuario(Request $request)
    {

        $cities = Ciudad::orderBy('nombre', 'asc')->get();

        $tipos= Tipo::with('tipos')->find(76);

        $cuentas = Tipo::with('tipos')->find(75);

        $documentos = Tipo::with('tipos')->find(74);

        $bancos = Banco::get();

        if ($request->has('q')) {

            $code = '' . $request->q;

            $patrocinador = Tercero::where('identificacion', '=' ,'' . strtolower($code) .'')->first();

            if (count($patrocinador) > 0 && $patrocinador->tipo_cliente_id == 83) {

                return view('admin.usuarios.createusua')->with(['tipos' => $tipos, 'code' => $code, 'cities' => $cities, 'documentos' => $documentos, 'bancos' => $bancos, 'cuentas' => $cuentas, 'patrocinador' => $patrocinador]);
                //return view('admin.usuarios.createusua')->with(['tipos' => $tipos, 'email' => $email, 'cities' => $cities, 'documentos' => $documentos,  'cuentas' => $cuentas]);
            } else {
                return view('admin.usuarios.createusua')->with(['tipos' => $tipos, 'cities' => $cities, 'documentos' => $documentos, 'bancos' => $bancos, 'cuentas' => $cuentas]);
            }

        }

        return view('admin.usuarios.createusua')->with(['tipos' => $tipos, 'cities' => $cities, 'documentos' => $documentos, 'bancos' => $bancos, 'cuentas' => $cuentas]);
        //return view('admin.usuarios.createusua')->with(['tipos' => $tipos, 'cities' => $cities, 'documentos' => $documentos,  'cuentas' => $cuentas]);
    }

    public function storeNuevo(Request $request)
    {

        $messages = [
            'code.exists' => 'El código referido es invalido, por favor verifiquelo!',
            'first-name.required' => 'Los nombre es requerido.',
            'last-name.required' => 'Los apellidos son requeridos.',
            'type_client.required' => 'Tipo de cliente requerido.',
            'type_dni.required' => 'Tipo de documento requerido.',
            'dni.required' => 'Número de documento requerido.',
            'city.required' => 'Ciudad requerida.',
            'sex.required' => 'Sexo requerido.',
            'birthday.required' => 'Fecha de nacimiento requerida.',
            'address.required' => 'Direccion requerida.',
            'phone.required' => 'Telefono requerido.',
	        'phone.unique:terceros,telefono' => 'El número de telefono ya existe.',
            'password.confirmed' => 'Se requiere confirmar las contraseñas.',
        ];

        $p = '';

        if (strlen($request->phone) == 13) {
            $p .= ltrim( $request->phone , '+57' );
        }else {
            $p .= $request->phone;
        }

        $terceros = DB::table('terceros')->where('identificacion', strtolower($request->dni))->select('id', 'state')->first();       



        if(count($terceros) == 0){

 
        $validator = Validator::make($request->all(), [
            'first-name' => 'required',
            'last-name' => 'required',
            'type_client' => 'required',
            'type_dni' => 'required',
            'dni' => 'required|unique:terceros,identificacion',
            'city' => 'required',
            'sex' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:terceros,telefono',
            'code' => 'required|string|exists:terceros,identificacion',
            'email' => 'required|email|unique:terceros,email',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        ], $messages);

        if ($validator->fails()) {

            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

           $usuario = new Tercero();
        }
        else{


        $validator = Validator::make($request->all(), [
            'first-name' => 'required',
            'last-name' => 'required',
            'type_client' => 'required',
            'type_dni' => 'required',
            'dni' => 'required',
            'city' => 'required',
            'sex' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'code' => 'required|string|exists:terceros,identificacion',
            'email' => 'required|email',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        ], $messages);

        if ($validator->fails()) {

            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

            $usuario = Tercero::find($terceros->id);
            $usuario->state = true;
        }


        $usuario->nombres = strtolower($request['first-name']);
        $usuario->apellidos = strtolower($request['last-name']);
        $usuario->direccion = strtolower($request->address);
        $usuario->telefono = strtolower($p);
        $usuario->email = strtolower($request->email);
        $usuario->usuario = strtolower($request->email);
        $usuario->contraseña = bcrypt($request->password);
        $usuario->tipo_id = 2;
        $usuario->ciudad_id = strtolower($request->city);
        $usuario->celular = strtolower($p);
        $usuario->network_id = 1;
        $usuario->tipo_cliente_id = $request->type_client;
        $usuario->documento_id = $request->type_dni;
        $usuario->identificacion = strtolower($request->dni);
        $usuario->sexo = strtolower($request->sex);
        $usuario->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->birthday);
 
            if (strlen($request->type_acount_bank) >= 1) {  $usuario->tipocuenta_id = $request->type_acount_bank;   }else{  $usuario->tipocuenta_id = null;  }
            if (strlen($request->bank) >= 1) {  $usuario->banco_id = $request->bank;   }else{  $usuario->banco_id = null;  }
            if (strlen($request->acount) >= 1) {  $usuario->numero_cuenta = $request->acount;   }else{  $usuario->numero_cuenta = null;  }

        if ($request->has('contract')) {

            ($request->contract == 'on') ? $usuario->contrato = true : false;
        }

        if ($request->has('terms')) {
            ($request->terms == 'on') ? $usuario->condiciones = true : false;
        }
        if ($request->has('acount')) {
            (isset($request->acount)) ? $usuario->acount = $request->acount: null;
        }


        if ($request->file('banco')) {
            $cuenta        = $request->file('banco');
            $cuenta_nombre = str_random(30) . "." . $cuenta->getClientOriginalExtension();
            $path          = public_path() . "/uploads";
            $cuenta->move($path, $cuenta_nombre);
            $usuario->cuenta = "uploads/" . $cuenta_nombre;
        }

        if ($request->file('cedula')) {

            $cuenta        = $request->file('cedula');
            $cuenta_nombre = str_random(30) . "." . $cuenta->getClientOriginalExtension();
            $path          = public_path() . "/uploads";
            $cuenta->move($path, $cuenta_nombre);
            $usuario->cedula = "uploads/" . $cuenta_nombre;

        }

        if ($request->file('rut')) {

            $cuenta        = $request->file('rut');
            $cuenta_nombre = str_random(30) . "." . $cuenta->getClientOriginalExtension();
            $path          = public_path() . "/uploads";
            $cuenta->move($path, $cuenta_nombre);
            $usuario->rut = "uploads/" . $cuenta_nombre;

        }

        // Usuario creado
        $usuario->save();

        $padre = Tercero::with('networks')->where('identificacion', '=', '' .$request->code. '')->first();

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

        $city = Ciudad::find($request->city);

        $good_id  = '';
        $mercando_id = '';


        $data = array('nombre' => $request['first-name'].' '.$request['last-name'], 'email' => $request->email, 'usario' => $request->email, 'password' => $request->password);
        $this->envio_registro($request->code, $data);

    if(count($terceros) == 0){

        echo 'hola, entré';

        if (count($padre) > 0 ) {

            if ($padre->tipo_cliente_id == 83 && $padre->state == true) {

                echo 'hola, padre activo';

                $result = DB::table('terceros_networks')
                    ->where('customer_id', $usuario->id)
                    ->where('network_id', 1)
                    ->where('padre_id', $padre->id)
                    ->get();

                if (count($result) == 0) {

                    DB::table('terceros_networks')->insert([
                        'customer_id' => $usuario->id,
                        'network_id' => 1,
                        'padre_id' => $padre->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }

            } else {

                echo 'hola, padre inactivo';

                $result = DB::table('terceros_networks')
                    ->where('customer_id', $usuario->id)
                    ->where('network_id', 1)
                    ->where('padre_id', 1)
                    ->get();

                if (count($result) == 0) {

                    DB::table('terceros_networks')->insert([
                        'customer_id' => $usuario->id,
                        'network_id' => 1,
                        'padre_id' => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                }
            }

        } else {

            echo 'hola, padre no existe';

            $result = DB::table('terceros_networks')
                ->where('customer_id', $usuario->id)
                ->where('network_id', 1)
                ->where('padre_id', 1)
                ->get();

            if (count($result) == 0) {

                DB::table('terceros_networks')->insert([
                    'customer_id' => $usuario->id,
                    'network_id' => 1,
                    'padre_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        if (count($usuario) > 0) {

            $api_url = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
            $clienta = new \GuzzleHttp\Client();

            try {

                $resa = $clienta->request('post', $api_url . '/admin/customers.json', array(
                        'form_params' => array(
                            'customer' => array(
                                'first_name' => strtolower($request['first-name']),
                                'last_name' => strtolower($request['last-name']),
                                'email' => strtolower($request->email),
                                'verified_email' => true,
                                'phone' => $p,
                                'addresses' => [

                                    [
                                        'address1' => strtolower($request->address),
                                        'city' => strtolower($city->nombre),
                                        'province' => '',
                                        "zip" => '',
                                        'first_name' => strtolower($request['first-name']),
                                        'last_name' => strtolower($request['last-name']),
                                        'country' => 'CO'
                                    ],

                                ],
                                "password" => $request->password,
                                "password_confirmation" => $request->password_confirmation,
                                'send_email_invite' => false,
                                'send_email_welcome' => false
                            )
                        )
                    )
                );

                $customer = json_decode($resa->getBody(), true);

                $good_id = '' . $customer['customer']['id'];


            } catch (ClientException $e) {

                $err = json_decode(($e->getResponse()->getBody()), true);

                foreach ($err['errors'] as $key => $value) {

                    echo $key . ' ' . $value[0] . "\n";
                }
            }

            $api_url_mercando = 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
            $clientb = new \GuzzleHttp\Client();

            try {

                $resb = $clientb->request('post', $api_url_mercando . '/admin/customers.json', array(
                        'form_params' => array(
                            'customer' => array(
                                'first_name' => strtolower($request['first-name']),
                                'last_name' => strtolower($request['last-name']),
                                'email' => strtolower($request->email),
                                'verified_email' => true,
                                'phone' => $p,
                                'addresses' => [

                                    [
                                        'address1' => strtolower($request->address),
                                        'city' => strtolower($city->nombre),
                                        'province' => '',

                                        "zip" => '',
                                        'first_name' => strtolower($request['first-name']),
                                        'last_name' => strtolower($request['last-name']),
                                        'country' => 'CO'
                                    ],

                                ],
                                "password" => $request->password,
                                "password_confirmation" => $request->password_confirmation,
                                'send_email_invite' => false,
                                'send_email_welcome' => false
                            )
                        )
                    )
                );

                $customer = json_decode($resb->getBody(), true);

                $mercando_id = '' . $customer['customer']['id'];

            } catch (ClientException $e) {

                $err = json_decode(($e->getResponse()->getBody()), true);

                foreach ($err['errors'] as $key => $value) {

                    echo $key . ' ' . $value[0] . "\n";
                }
            }
        }

        DB::table('terceros_tiendas')->insertGetId(
            [
                'tercero_id' => $usuario->id,
                'customer_id_good' =>  $good_id,
                'customer_id_mercando' =>  $mercando_id,
            ]
        );
    }
    else{
 
        DB::table('terceros_networks')->where('customer_id', $usuario->id)->update(['padre_id' => $padre->id]);

        $data = array( 'first_name' => strtolower($request['first-name']),
                                'last_name' => strtolower($request['last-name']),
                                'email' => strtolower($request->email),
                                'verified_email' => true,
                                'phone' => $p,
                                'addresses' => [

                                    [
                                        'address1' => strtolower($request->address),
                                        'city' => strtolower($city->nombre),
                                        'province' => '',

                                        "zip" => '',
                                        'first_name' => strtolower($request['first-name']),
                                        'last_name' => strtolower($request['last-name']),
                                        'country' => 'CO'
                                    ],

                                ],
                                "password" => $request->password,
                                "password_confirmation" => $request->password_confirmation,
                                'send_email_invite' => false,
                                'send_email_welcome' => false,
                                'state' => 'enable'
                            );

        GuzzleHttp::api_usuarios('good', $usuario->email, $data, 'actualizar'); 
        GuzzleHttp::api_usuarios('mercando', $usuario->email, $data, 'actualizar'); 
              
    }

        return redirect()->route('login')->with(['message' => 'Felicitaciones, has sido registrado correctamente.']);
    }

    public function mail($data, $info, $tipo, $nivel)
    {  
        if ($tipo == 1) {
            $email =  $data['email'];
        }elseif ($tipo == 2) {
            $email =  $data->email;
        }

        Mail::send($info['view'], ['usuario' => $data, 'info' => $info, 'nivel' => $nivel], function ($m)  use ($data, $info, $email) {
        $m->from('info@tiendagood.com', 'Tienda good');           
        $m->to($email, 'steben')->subject($info['asunto']);
        });          
    }

    public function envio_registro($code, $data)
    {
        $info = array('view' => 'admin.send.welcome_2', 'asunto' => 'Bienvenido a tienda good');
        $this->mail($data, $info, 1, 0);  

        $info = array('view' => 'admin.send.welcome', 'asunto' => 'Se inscribieron con tu código');
        $level = Tercero::with('networks')->where('identificacion', strtolower($code))->first();
        
        if (count($level->networks) > 0) {
            $this->mail($level, $info, 2, 1);  

          if ($level->networks['0']['pivot']['customer_id'] >= 2) { 
            $level_1 = Tercero::with('networks')->where('id', strtolower($level->networks['0']['pivot']['padre_id']))->first();
            if (count($level_1->networks) > 0) {
                if ($level_1->networks['0']['pivot']['customer_id'] > 0) {
                    $this->mail($level_1, $info, 2, 2);  

                    $level_2 = Tercero::with('networks')->where('id', strtolower($level_1->networks['0']['pivot']['padre_id']))->first(); 
                    if (count($level_2->networks) > 0) {
                        if ($level_2->networks['0']['pivot']['customer_id'] >= 2) {
                            $this->mail($level_2, $info, 2, 3);  
                        }
                    }  
                } 
            }       
          }
        }  
    }

}
