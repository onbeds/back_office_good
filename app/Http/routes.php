<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */


Route::get('pdf', 'OrdersController@hola');
Route::get('hola', 'OrdersController@hola');
Route::get('otra', 'OrdersController@otra');
/**
 * Routes Dingo API
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {

        $api->post('oauth/verify_code', 'UsersController@verify_code');

        $api->post('oauth/access_token', 'UsersController@authorization');

        $api->group(['middleware' => 'api.auth'], function ($api) {

            $api->get('users', ['uses' => 'UsersController@index', 'as' => 'api.users.index']);
        });

        $api->get('oauth/authorize', ['uses' => 'UsersController@authorizeGet', 'as' => 'oauth.authorize.get', 'middleware' => ['check-authorization-params', 'auth']]);

        $api->post('oauth/authorize', ['uses' => 'UsersController@authorizePost', 'as' => 'oauth.authorize.post', 'middleware' => ['csrf', 'check-authorization-params', 'auth']]);
    });
});

Route::get('/carga', ['uses' => 'AdminController@carga', 'as' => 'admin.index']);

Route::get('orders/list/paid', ['uses' => 'OrdersController@contador', 'as' => 'admin.orders.list.paid']);
Route::get('orders/list/paid/new', ['uses' => 'OrdersController@contador_uno', 'as' => 'admin.orders.list.paid.uno']);


//Pdfs
Route::any('reportes/datos/products', ['uses' => 'ReportesController@products', 'as' => 'admin.reportes.datos.products']);
Route::get('pdf', 'PdfController@invoice');
Route::get('terceros/data', 'TercerosController@anyData');
Route::get('orders', 'OrdersController@orders');
Route::get('customers/meta', 'CustomersController@meta');
Route::get('customers/metadelete', 'CustomersController@metadelete');
Route::get('customers/mercado', 'CustomersController@mercado');
Route::get('gifts', 'CustomersController@gifts');

/*
 * web hooks
 */

Route::post('customers/create', 'CustomersController@create');


Route::post('orders/good/create', 'OrdersController@create');
Route::post('orders/good/update', 'OrdersController@update');
Route::post('orders/good/cancelled', 'OrdersController@cancelled');

Route::post('orders/mercando/create', 'OrdersController@create_mercando');
Route::post('orders/mercando/update', 'OrdersController@update_mercando');
Route::post('orders/mercando/cancelled', 'OrdersController@cancelled_mercando');


Route::post('products/good/create', 'ProductsController@create');
Route::post('products/good/update', 'ProductsController@update');
Route::post('products/good/update_listing', 'ProductsController@update_listing');


Route::post('products/mercando/create', 'ProductsController@create_mercando');
Route::post('products/mercando/update', 'ProductsController@update_mercando');


/*
 * Final web hooks
 *
 * */

Route::post('validate/email', 'UsuariosController@verified_email');
Route::post('validate/code', 'UsuariosController@verified_code');
Route::post('validate/phone', 'UsuariosController@verified_phone');
Route::post('validate/dni', 'UsuariosController@verified_dni');
Route::get('/terms', ['as' => 'terms', 'uses' => 'UsuariosController@terms']);
Route::get('/terms_prime', ['as' => 'terms_prime', 'uses' => 'UsuariosController@termsprime']);



Route::post('/cities', 'CitiesController@cities');
Route::post('/clients/types', 'TiposClientesController@getTypesClients');

Route::get('/nivel/{id}', 'AdminController@nivel');

// Authentication routes...
Route::get('/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

// Password reset link
Route::get('olvido-contraseña', ['as' => 'reset', 'uses' => 'Auth\PasswordController@getEmail']);
Route::post('olvido-contraseña', ['as' => 'reset', 'uses' => 'Auth\PasswordController@postEmail']);
Route::get('recuperar-contraseña/{token}', ['as' => 'recuperar', 'uses' => 'Auth\PasswordController@getReset']);
Route::post('recuperar-contraseña', ['as' => 'recuperar', 'uses' => 'Auth\PasswordController@postReset']);

//Registrar nuevo usuario
Route::get('/', ['uses' => 'UsuariosController@getusuario', 'as' => 'admin.usuarios.registerGet']);
route::post('register', ['uses' => 'UsuariosController@storenuevo', 'as' => 'admin.usuarios.registerPost']);
Route::get('/envio_registro/{id}', 'UsuariosController@envio_registro');

// Password reset

Route::get('recuperar-contraseña/{token}', ['as' => 'recuperar', 'uses' => 'Auth\PasswordController@getReset']);
Route::post('recuperar-contraseña', ['as' => 'recuperar', 'uses' => 'Auth\PasswordController@postReset']);
Route::get('registro/payu', ['as' => 'PayuController@paybefore', 'as' => 'admin.payu.payu']);

Route::any('terceros/actualizar-datos', ['uses' => 'TercerosController@actualizar_mis_datos', 'as' => 'terceros.actualizar_mis_datos']);
Route::any('terceros/activarplanprime', ['uses' => 'TercerosController@activar_plan_prime', 'as' => 'terceros.activar_plan_prime']);
//Route::post('terceros/actualizar-datos/cambio', ['uses' => 'TercerosController@post_actualizar_mis_datos', 'as' => 'terceros.actualizar_mis_datos']);

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    /*
     * Liquidaciones
     */

    Route::get('/liquidaciones', ['uses' => 'LiquidacionesController@liquidaciones', 'as' => 'admin.liquidaciones.index']);
    Route::get('/liquidaciones/data', ['uses' => 'LiquidacionesController@data_liquidaciones', 'as' => 'admin.liquidaciones.data']);
    Route::get('/liquidaciones/{id}/edit', ['uses' => 'LiquidacionesController@editar_liquidaciones', 'as' => 'admin.liquidaciones.edit']);
    Route::post('/liquidaciones/create/gift_card', ['uses' => 'AdminController@gift_card', 'as' => 'admin.liquidaciones.gift_card']);
    Route::get('liquidacion/liquidaciones_extracto_comisiones/{id}', ['uses' => 'LiquidacionesController@liquidaciones_extracto_comisiones', 'as' => 'liquidacion.liquidaciones_extracto_comisiones']);
    Route::get('liquidacion/liquidaciones_extracto_comisiones_datos/{id}', ['uses' => 'LiquidacionesController@liquidaciones_extracto_comisiones_datos', 'as' => 'liquidacion.liquidaciones_extracto_comisiones_datos']);

    /*
     * Rules
     */
    Route::get('/rules', ['uses' => 'RulesController@index', 'as' => 'admin.rules.index']);
    Route::get('/rules/create', ['uses' => 'RulesController@create', 'as' => 'admin.rules.create']);
    Route::post('/rules', ['uses' => 'RulesController@store', 'as' => 'admin.rules.store']);
    Route::post('/rules/update/detail', ['uses' => 'RulesController@updateDetail', 'as' => 'admin.rules.update_detail']);
    Route::get('/rules/{id}/edit', ['uses' => 'RulesController@edit', 'as' => 'admin.rules.edit']);
    Route::any('/rules/data', ['uses' => 'RulesController@data', 'as' => 'admin.rules.data']);
    Route::get('/rules/types', ['uses' => 'RulesController@tipos', 'as' => 'admin.rules.tipos']);
    Route::get('/rules/networks', ['uses' => 'RulesController@redes', 'as' => 'admin.rules.redes']);

    /*
     * Tipos
     */
    Route::get('/tipos', ['uses' => 'TiposController@index', 'as' => 'admin.tipos.index']);
    Route::post('/tipos', ['uses' => 'TiposController@store', 'as' => 'admin.tipos.store']);
    Route::get('/tipos/create', ['uses' => 'TiposController@create', 'as' => 'admin.tipos.create']);
    Route::get('/tipos/{id}/edit', ['uses' => 'TiposController@edit', 'as' => 'admin.tipos.edit']);
    Route::put('/tipos/{id}', ['uses' => 'TiposController@update', 'as' => 'admin.tipos.update']);
    Route::any('/tipos/data', ['uses' => 'TiposController@data', 'as' => 'admin.tipos.data']);

    /*
     * Variantes
     */
    Route::get('variants/good', ['uses' => 'VariantsController@index', 'as' => 'admin.variants.index']);
    Route::get('variants/mercando', ['uses' => 'VariantsController@mercando', 'as' => 'admin.variants.mercando']);




    Route::any('variants/good/update', ['uses' => 'VariantsController@update', 'as' => 'admin.variants.update']);
    Route::any('variants/mercando/update', ['uses' => 'VariantsController@update_mercando', 'as' => 'admin.variants.update_mercando']);

    /*
     *   gifts
     *
     * */
    Route::get('/gifts', ['uses' => 'CommissionsController@index', 'as' => 'admin.gifts.home']);
    Route::any('/gifts/data', ['uses' => 'CommissionsController@anyData', 'as' => 'admin.gifts.data']);
    Route::get('/', ['uses' => 'AdminController@index', 'as' => 'admin.index']);
    Route::get('/network', ['uses' => 'AdminController@network', 'as' => 'admin.network']);
    Route::get('/search', ['uses' => 'AdminController@search', 'as' => 'admin.search']);
    Route::post('/finder', ['uses' => 'AdminController@finder', 'as' => 'admin.finder']);

    Route::post('/level/one', ['uses' => 'AdminController@level_one', 'as' => 'admin.one']);
    Route::post('/level/two', ['uses' => 'AdminController@level_two', 'as' => 'admin.two']);
    Route::post('/level/tree', ['uses' => 'AdminController@level_tree', 'as' => 'admin.tree']);


    // email
    Route::get('/send/email', ['uses' => 'AdminController@email', 'as' => 'admin.send.mail']);
    Route::get('/send/msm', ['uses' => 'AdminController@msm', 'as' => 'admin.send.msm']);
    Route::post('/send', ['uses' => 'AdminController@send', 'as' => 'admin.send']);
    Route::post('/buscar', ['uses' => 'AdminController@buscar', 'as' => 'admin.buscar']);
    Route::get('/feredidos', ['uses' => 'AdminController@anyData', 'as' => 'admin.referidos']);

    // Usuarios
    Route::get('usuarios/data', ['uses' => 'UsersController@anyData', 'as' => 'usuarios.data']);
    Route::resource('users', 'UsersController');
    Route::get('usuarios/{id}/hijos', ['uses' => 'UsersController@hijos', 'as' => 'admin.usuarios.hijos']);

    //Proveedores
    Route::get('proveedores', ['uses' => 'AdminController@indexprovedores', 'as' => 'admin.proveedores.index']);
    Route::get('proveedores/data', ['uses' => 'ProveedoresController@anyData', 'as' => 'Proveedores.data']);
    Route::get('proveedores', ['uses' => 'ProveedoresController@create', 'as' => 'admin.proveedores.create']);

    //Route::get('proveedores/update', ['uses' => 'ProveedoresController@update', 'as' => 'admin.Proveedores.update']);
    Route::resource('proveedores', 'ProveedoresController');
    Route::get('Proveedores/{id}/destroy', ['uses' => 'ProveedoresController@destroy', 'as' => 'admin.proveedores.destroy']);
    Route::get('Proveedores/{id}/hijos', ['uses' => 'ProveedoresController@hijos', 'as' => 'admin.proveedores.hijos']);

    //Redes
    Route::get('networks', ['uses' => 'NetworksController@index', 'as' => 'admin.networks.index']);

    Route::get('networks/data', ['uses' => 'NetworksController@anyData', 'as' => 'admin.networks.data']);
    Route::get('networks/create', ['uses' => 'NetworksController@create', 'as' => 'admin.networks.create']);
    Route::resource('networks', 'NetworksController');

    //Reglas
    Route::get('reglas', ['uses' => 'ReglasController@index', 'as' => 'admin.reglas.index']);
    Route::get('reglas/data', ['uses' => 'ReglasController@anyData', 'as' => 'reglas.data']);
    Route::get('reglas/create', ['uses' => 'ReglasController@create', 'as' => 'admin.reglas.create']);
    Route::resource('reglas', 'ReglasController');


    //comisiones
    Route::get('comisiones', ['uses' => 'ComisionesController@index', 'as' => 'admin.comisiones.index']);
    Route::get('comisiones/data', ['uses' => 'ComisionesController@anyData', 'as' => 'comisiones.data']);
    Route::get('comisiones/create', ['uses' => 'ComisionesController@create', 'as' => 'admin.comisiones.create']);
    Route::resource('comisiones', 'ComisionesController');



    // Roles
    Route::get('roles/data', ['uses' => 'RolesController@anyData', 'as' => 'roles.data']);
    Route::resource('roles', 'RolesController');
    Route::get('roles/{id}/destroy', ['uses' => 'RolesController@destroy', 'as' => 'admin.roles.destroy']);

    //Perfiles
    Route::resource('perfiles', 'PerfilesController');
    Route::get('perfiles/{id}/destroy', ['uses' => 'PerfilesController@destroy', 'as' => 'admin.perfiles.destroy']);

    //Permisos
    Route::post('permisos/datos', ['as' => 'get.permisos', 'uses' => 'PermisosController@datos']);
    Route::post('permisos/asignar', ['as' => 'asignar.permisos', 'uses' => 'PermisosController@asignar']);
    Route::post('permisos/desasignar', ['as' => 'desasignar.permisos', 'uses' => 'PermisosController@desasignar']);

    // Ciudades
    Route::get('ciudades/data', ['uses' => 'CiudadesController@anyData', 'as' => 'ciudades.data']);
    Route::resource('ciudades', 'CiudadesController');
    Route::get('ciudades/{id}/destroy', ['uses' => 'CiudadesController@destroy', 'as' => 'admin.ciudades.destroy']);

    // Productos
    Route::get('productos/data', ['uses' => 'ProductosController@anyData', 'as' => 'productos.data']);
    Route::resource('productos', 'ProductosController');
    Route::get('productos/{id}/destroy', ['uses' => 'ProductosController@destroy', 'as' => 'admin.productos.destroy']);

    // Dominios
    Route::get('dominios/data', ['uses' => 'DominiosController@anyData', 'as' => 'dominios.data']);
    Route::resource('dominios', 'DominiosController');
    Route::get('dominios/{id}/destroy', ['uses' => 'DominiosController@destroy', 'as' => 'admin.dominios.destroy']);


    // Sucursales
    Route::get('oficinas/data', ['uses' => 'OficinasController@anyData', 'as' => 'oficinas.data']);
    Route::resource('oficinas', 'OficinasController');
    Route::get('oficinas/{id}/destroy', ['uses' => 'OficinasController@destroy', 'as' => 'admin.oficinas.destroy']);

    //Resoluciones
    Route::get('resoluciones/data', ['uses' => 'ResolucionesController@anyData', 'as' => 'resoluciones.data']);
    Route::resource('resoluciones', 'ResolucionesController');
    Route::get('resoluciones/{id}/destroy', ['uses' => 'ResolucionesController@destroy', 'as' => 'admin.resoluciones.destroy']);

    //Log
    Route::resource('logs', 'LogsController');

    //Para procesar los planos
    Route::post('uploads', ['as' => 'uploads', 'uses' => 'FilesController@Uploads_init']);
    Route::post('uploads_init', ['as' => 'uploads_init', 'uses' => 'FilesController@postUploads']);
    Route::resource('files', 'FilesController');
    //Para las envios
    //Para los reportes
    Route::any('reportes/product', ['uses' => 'ProductsController@welcome', 'as' => 'admin.reportes.product']);

    // orders
    Route::group(['middleware' => 'role:contabilidad'], function () {
        Route::get('orders/list-paid', ['uses' => 'OrdersController@listpaid', 'as' => 'admin.orders.list-paid']);
        Route::get('orders/list-pending', ['uses' => 'OrdersController@listpending', 'as' => 'admin.orders.list-pending']);
        Route::any('orders/list/paid', ['uses' => 'OrdersController@paid', 'as' => 'admin.orders.list.paid']);
        Route::any('orders/list/pending', ['uses' => 'OrdersController@pending', 'as' => 'admin.orders.list.pending']);
    });

    Route::group(['middleware' => 'role:administridador|contabilidad'], function () {
    //liquidaciones
        Route::get('liquidacion/liquidar', ['uses' => 'LiquidacionesController@get_liquidar', 'as' => 'liquidacion.liquidar']);
        Route::post('liquidacion/liquidar', ['uses' => 'LiquidacionesController@post_liquidar', 'as' => 'liquidacion.liquidar.envio']);
        Route::get('liquidacion/liquidaciones_general', ['uses' => 'LiquidacionesController@liquidaciones_general', 'as' => 'liquidacion.liquidaciones_general']);
        Route::get('liquidacion/liquidaciones_general_datos', ['uses' => 'LiquidacionesController@liquidaciones_datos', 'as' => 'liquidacion.liquidaciones_general_datos']);
        Route::get('liquidacion/liquidaciones_detalles_excel/{id}', ['uses' => 'LiquidacionesController@liquidaciones_detalles_excel', 'as' => 'liquidacion.detalles_excel']);
        Route::get('liquidacion/liquidaciones_terceros_estados/{id}', ['uses' => 'LiquidacionesController@liquidaciones_terceros_estados', 'as' => 'liquidacion.liquidaciones_terceros_estados']);
        Route::get('liquidacion/liquidaciones_terceros_estados_datos/{id}', ['uses' => 'LiquidacionesController@liquidaciones_terceros_estados_datos', 'as' => 'liquidacion.liquidaciones_terceros_estados_datos']);
        Route::post('liquidacion/liquidaciones_terceros_estados', ['uses' => 'LiquidacionesController@liquidaciones_terceros_estados', 'as' => 'liquidacion.liquidaciones_terceros_estado_cambio']);
        Route::post('liquidacion/cambiar_estado', ['uses' => 'LiquidacionesController@liquidaciones_cambiar_estado', 'as' => 'liquidacion.cambiar_estado']);
    });

    Route::group(['middleware' => 'role:logistica|administrador|servicio.al.cliente|dirsac'], function () {
        Route::get('orders', ['uses' => 'OrdersController@home', 'as' => 'admin.orders.home']);
        Route::post('orders/{id}', ['uses' => 'OrdersController@up', 'as' => 'admin.orders.up']);
        Route::get('orders/{id}/edit', ['uses' => 'OrdersController@edit', 'as' => 'admin.orders.edit']);
        Route::any('orders/paid', ['uses' => 'OrdersController@anyData', 'as' => 'admin.orders.paid']);
        Route::get('orders/news', ['uses' => 'OrdersController@news', 'as' => 'admin.orders.news']);
        Route::post('orders/news/data', ['uses' => 'OrdersController@news_data', 'as' => 'admin.orders.news_data']);
        Route::post('orders/news/update', ['uses' => 'OrdersController@news_update', 'as' => 'admin.orders.news_update']);
    });

    Route::any('reportes/order', ['uses' => 'OrdersController@index', 'as' => 'admin.reportes.order']);
    Route::any('reportes/orders', ['uses' => 'OrdersController@orders', 'as' => 'admin.reportes.orders']);
    Route::any('reportes/orders/status', ['uses' => 'OrdersController@status_orders', 'as' => 'admin.reportes.orders.status']);
    Route::any('reportes/codes', ['uses' => 'ReportesController@code', 'as' => 'admin.reportes.codes']);
    Route::any('reportes/code', ['uses' => 'ReportesController@anyCode', 'as' => 'admin.reportes.code']);
    Route::any('reportes/datos', ['uses' => 'ReportesController@anyData', 'as' => 'admin.reportes.datos']);
    Route::any('reportes/datos/products', ['uses' => 'ReportesController@products', 'as' => 'admin.reportes.datos.products']);
    Route::any('reportes/descargar', ['uses' => 'ReportesController@descargar', 'as' => 'admin.reportes.descargar']);
    Route::resource('reportes', 'ReportesController', ['only' => ['index']]);

    //Facturacion
    Route::any('factura_imprimir/{id}/email/{email}', ['uses' => 'FacturacionController@imprimir', 'as' => 'admin.facturacion.imprimir']);
    Route::any('facturacion/buscar', ['uses' => 'FacturacionController@buscar', 'as' => 'admin.facturacion.buscar']);
    Route::any('facturacion/data', ['uses' => 'FacturacionController@anyData', 'as' => 'facturas.data']);
    Route::resource('facturacion', 'FacturacionController', ['only' => ['index', 'store', 'create']]);

    //Terceros
    Route::get('terceros', ['uses' => 'TercerosController@index', 'as' => 'admin.terceros.index']);
    Route::get('terceros/data', ['uses' => 'TercerosController@anyData', 'as' => 'admin.terceros.data']);
    Route::get('terceros/{id}', ['uses' => 'TercerosController@show', 'as' => 'admin.terceros.show']);
    Route::get('terceros/{id}/edit', ['uses' => 'TercerosController@edit', 'as' => 'admin.terceros.edit']);
    Route::put('terceros/{id}', ['uses' => 'TercerosController@update', 'as' => 'admin.terceros.update']);
    Route::any('terceros/show/data', ['uses' => 'TercerosController@anyShow', 'as' => 'admin.terceros.anyshow']);
    Route::get('terceros/editar/datos', ['uses' => 'TercerosController@editarDatos', 'as' => 'admin.terceros.editardatos']);
    Route::get('terceros/cambiar/padre', ['uses' => 'TercerosController@cambiarPadre', 'as' => 'admin.terceros.cambiarpadre']);
    Route::get('terceros/set/tansaction', ['uses' => 'TercerosController@setTransactions', 'as' => 'admin.terceros.setransactions']);
    Route::get('terceros/asignar/orden', ['uses' => 'TercerosController@asignarOrden', 'as' => 'admin.terceros.asignarorden']);
    Route::post('terceros/getdata', ['uses' => 'TercerosController@getData', 'as' => 'admin.terceros.getdata']);
    Route::post('terceros/setdata', ['uses' => 'TercerosController@setData', 'as' => 'admin.terceros.setdata']);
    Route::post('terceros/getpadre', ['uses' => 'TercerosController@getPadre', 'as' => 'admin.terceros.getpadre']);
    Route::post('terceros/setpadre', ['uses' => 'TercerosController@setPadre', 'as' => 'admin.terceros.setpadre']);
    Route::post('terceros/setstate', ['uses' => 'TercerosController@setState', 'as' => 'admin.terceros.setstate']);
    Route::post('terceros/getorden', ['uses' => 'TercerosController@getOrden', 'as' => 'admin.terceros.getorden']);
    Route::post('terceros/setorden', ['uses' => 'TercerosController@setOrden', 'as' => 'admin.terceros.setorden']);
    Route::get('terceros/documentos/lista', ['uses' => 'TercerosController@lista_documentos', 'as' => 'admin.terceros.lista_documentos']);
    Route::get('terceros/documentos/descargar/{id}/{tipo}', ['uses' => 'TercerosController@descargar_documentos', 'as' => 'admin.terceros.descargar_documentos']);

    Route::get('terceros/searching/index', ['uses' => 'TercerosController@index_search', 'as' => 'admin.terceros.index.searching']);
    Route::post('terceros/searching/data', ['uses' => 'TercerosController@searching', 'as' => 'admin.terceros.index.searching.data']);
    Route::post('terceros/searching/levels', ['uses' => 'TercerosController@levels', 'as' => 'admin.terceros.index.searching.levels']);

    //productos
    Route::get('products/good', ['uses' => 'ProductsController@indexGood', 'as' => 'admin.products.index.good']);
    Route::get('products/mercando', ['uses' => 'ProductsController@indexMercando', 'as' => 'admin.products.index.mercando']);
    Route::get('products/good/data', ['uses' => 'ProductsController@anyDataGood', 'as' => 'admin.products.good']);
    Route::get('products/mercando/data', ['uses' => 'ProductsController@anyDataMercando', 'as' => 'admin.products.mercando']);

    Route::get('products/good/{id}/variants', ['uses' => 'ProductsController@variants_good', 'as' => 'admin.products.good.variants']);
    Route::get('products/mercando/{id}/variants', ['uses' => 'ProductsController@variants_mercando', 'as' => 'admin.products.mercando.variants']);

    Route::any('variants/good/search', ['uses' => 'VariantsController@variants', 'as' => 'admin.variants.search']);
    Route::any('variants/mercando/search', ['uses' => 'VariantsController@variants_mercando', 'as' => 'admin.variants.search_mercando']);


});
