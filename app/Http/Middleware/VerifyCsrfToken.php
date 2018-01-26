<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/datos_recogida',
        'admin/permisos/datos',
        'admin/permisos/asignar',
        'admin/permisos/desasignar',
        'admin/tarifas/valor',
        'admin/files/index',
        'admin/uploads_init',
        'admin/reportes/datos',
        'admin/clientes/servicios',
        'digitacion/cuenta/',
        'tarifas/valor/',
        'admin/recogidas/calendario/',
        'admin/firmar',
        'admin/uploads',
        'admin/terceros/*',
        'customers/create',
        'orders/*',
        'customers/meta',
        'products/*',
        'products/good/*',
        'products/mercando/*',
        'variants/*',
        'admin/orders/*',
        'admin/tipos/*',
        'api/*',
        'cities',
        'clients/types',
        'validate/email',
        'validate/code',
        'validate/phone',
        'validate/dni',
        'admin/level/one',
        'admin/level/two',
        'admin/level/tree',
        'admin/terceros/getdata',
        'admin/terceros/setdata',
        'admin/terceros/getpadre',
        'admin/terceros/setpadre',
        'admin/terceros/getorden',
        'admin/terceros/setorden',
        'admin/terceros/setstate',
        'terceros/actualizar-datos',
        'terceros/activarplanprime',
        'admin/liquidacion/cambiar_estado',
        'admin/terceros/searching/data',
        'admin/terceros/searching/levels'
    ];

}
