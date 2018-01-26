<aside class="side-navigation-wrap">
    <div class="sidenav-inner">
        <ul class="side-nav magic-nav">


            <li class="has-submenu">
                <a href="{{ route('admin.index') }}" class="animsition-link text-left">
                    <i class="fa fa-area-chart">
                    </i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="has-submenu">
                <a href="{{ route('terceros.actualizar_mis_datos') }}" class="animsition-link text-left">
                    <i class="glyphicon glyphicon-pencil">
                    </i>
                    <span class="nav-text">Actualizar mis datos</span>
                </a>
            </li>

           <li class="has-submenu">

                <a href="{{ route('admin.liquidaciones.index') }}" class="animsition-link text-left">
                    <i class="fa fa-money">
                    </i>
                    <span class="nav-text">Mis Liquidaciones</span>
                </a>
            </li>

            @role('dirsac|administrador|servicio.al.cliente')

            <li class="has-submenu">
                <a href="{{ route('admin.search') }}" class="animsition-link text-left">
                    <i class="fa fa-search">
                    </i>
                    <span class="nav-text">Buscar Referidos</span>
                </a>
            </li>

            @endrole

            @role('dirsac|administrador|servicio.al.cliente|asistente.administrativa')

            <li class="has-submenu">
                <a href="#terceros" data-toggle="collapse" aria-expanded="false" class="text-left">
                    <i class="fa fa-user">
                    </i>
                    <span class="nav-text">Terceros</span>
                </a>
                <div class="sub-menu collapse secondary list-style-circle" id="terceros">
                    <ul>
                        <li>@role('dirsac|servicio.al.cliente')
                            <a href="{{ route('admin.terceros.editardatos') }}" class="text-left">
                                <i class="fa fa-edit">
                                </i>
                                Editar Datos
                            </a>
                        </li> @endrole

                        <li>@role('dirsac|servicio.al.cliente|administrador')
                            <a href="{{ route('admin.terceros.index.searching') }}" class="text-left">
                                <i class="fa fa-edit">
                                </i>
                                Buscar usuario
                            </a>
                        </li> @endrole

                        @role('dirsac')
                        <li>
                            <a href="{{ route('admin.terceros.cambiarpadre') }}" class="text-left">
                                <i class="fa fa-edit">
                                </i>
                                Cambiar Padre
                            </a>
                        </li>@endrole
                        <li>@role('dirsac|servicio.al.cliente')
                        <li>
                            <a href="{{ route('admin.terceros.asignarorden') }}" class="text-left">
                                <i class="fa fa-edit">
                                </i>
                                Asignar Orden de Venta
                            </a>
                        </li>@endrole
                        @role('dirsac|servicio.al.cliente|asistente.administrativa')
                        <li>
                            <a href="{{ route('admin.terceros.lista_documentos') }}" class="text-left">
                                <i class="fa fa-edit">
                                </i>
                                Documentación
                            </a>
                        </li>@endrole

                    </ul>
                </div>
            </li>

            @endrole
            
            <li class="has-submenu">
                <a href="#send" data-toggle="collapse" aria-expanded="false" class="text-left">
                    <i class="fa fa-folder-open">
                    </i>
                    <span class="nav-text">Invitaciones</span>
                </a>
                <div class="sub-menu collapse secondary list-style-circle" id="send">
                    <ul>
                        <li>
                            <a href="{{ route('admin.send.mail') }}" class="text-left">
                                <i class="fa fa-envelope-o">
                                </i>
                                Enviar Email
                            </a>
                        </li>

                    </ul>
                </div>
            </li>


            @role('contabilidad|administrador')
            <li class="has-submenu">
                <a href="#contabilidad" data-toggle="collapse" aria-expanded="false" class="text-left">
                    <i class="fa fa-pie-chart">
                    </i>
                    <span class="nav-text">Contabilidad</span>
                </a>
                <div class="sub-menu collapse secondary list-style-circle" id="contabilidad">
                    <ul>
                        <li>
                            <a href="{{ route('liquidacion.liquidar') }}" class="text-left">
                                <i class="fa fa-chevron-right">
                                </i>
                                Generar liquidación
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('liquidacion.liquidaciones_general') }}" class="text-left">
                                <i class="fa fa-chevron-right">
                                </i>
                                <span class="nav-text">Liquidaciones</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders.news') }}" class="text-left">
                                <i class="fa fa-slack">
                                </i>
                                <span class="nav-text">Novedades</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders.list-paid') }}" class="text-left">
                                <i class="fa fa-chevron-right">
                                </i>
                                <span class="nav-text">Ordenes Pagas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders.list-pending') }}" class="text-left">
                                <i class="fa fa-chevron-right">
                                </i>
                                <span class="nav-text">Ordenes Pendientes</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            @endrole

            @role('logistica|dirsac|servicio.al.cliente|administrador')
            <li class="has-submenu">
                <a href="#logistica" data-toggle="collapse" aria-expanded="false" class="text-left">
                    <i class="fa fa-skyatlas">
                    </i>
                    <span class="nav-text">Logistica</span>
                </a>
                <div class="sub-menu collapse secondary list-style-circle" id="logistica">
                    <ul>
                        <li>
                            <a href="{{ route('admin.orders.home') }}" class="text-left">
                                <i class="fa fa-slack">
                                </i>
                                <span class="nav-text">Ordenes</span>
                            </a>
                        </li>


                    </ul>
                </div>
            </li>
            @endrole

            @permission('configuracion')
            <li class="has-submenu">
                <a href="#reportes" data-toggle="collapse" aria-expanded="false" class="text-left">
                    <i class="fa fa-bar-chart">
                    </i>
                    <span class="nav-text">Informers</span>
                </a>
                <div class="sub-menu collapse secondary list-style-circle" id="reportes">
                    <ul>
                        <li>
                            <a href="{{ route('admin.gifts.home') }}" class="text-left">
                                <i class="fa fa-money">
                                </i>
                                <span class="nav-text">Bonos Digitales</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.reportes.codes') }}" class="text-left">
                                <i class="fa fa-money">
                                </i>
                                Código
                            </a>
                        </li>

                        <li>
                            <a href="{{route('admin.reportes.order')}}" class="text-left">
                                <i class="fa fa-money">
                                </i>
                                Estado Ordenes
                            </a>
                        </li>

                        <li>
                            <a href="{{route('admin.reportes.product')}}" class="text-left">
                                <i class="fa fa-money">
                                </i>
                                Productos sin foto
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.reportes.index') }}" class="text-left">
                                <i class="fa fa-money">
                                </i>
                                Referidos
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @endpermission('configuracion')

            @permission('configuracion')
            <li class="has-submenu">
                <a href="#submenu1" data-toggle="collapse" aria-expanded="false" class="text-left">
                    <i class="fa fa-gears">
                    </i>
                    <span class="nav-text">
                        Configuración
                    </span>
                </a>
                <div class="sub-menu collapse secondary list-style-circle" id="submenu1">
                    <ul>
                        <li>
                            <a href="{{ route('admin.ciudades.index') }}" class="text-left">
                                <i class="fa fa-bank">
                                </i>
                                Ciudades
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dominios.index') }}" class="text-left">
                                <i class="fa fa-share-alt">
                                </i>
                                Dominios
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.oficinas.index') }}" class="text-left">
                                <i class="fa fa-building">
                                </i>
                                Oficinas
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.proveedores.index') }}" class="text-left">
                                <i class="fa fa-user">
                                </i>
                                Proveedores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.networks.index') }}" class="text-left">
                                <i class="fa fa-share-alt">
                                </i>
                                <span class="nav-text">Redes</span>
                            </a>
                        </li>
                        <li class="has-submenu">
                            <a href="{{ route('admin.rules.index') }}" class="text-left">
                                <i class="fa fa-gavel">
                                </i>
                                <span class="nav-text">Reglas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.resoluciones.index') }}" class="text-left">
                                <i class="fa fa-sort-numeric-asc">
                                </i>
                                Resoluciones
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.perfiles.index') }}" class="text-left">
                                <i class="fa fa-unlock">
                                </i>
                                Roles
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.terceros.index') }}" class="text-left">
                                <i class="fa fa-user">
                                </i>
                                Terceros
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.tipos.index') }}" class="text-left">
                                <i class="fa fa-money">
                                </i>
                                Tipos
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.users.index') }}" class="text-left">
                                <i class="fa fa-user">
                                </i>
                                Usuarios
                            </a>
                        </li>



                    </ul>
                </div>
            </li>
            @endpermission('configuracion')

            @role('good')
            <li class="has-submenu">
                <a href="#submenu10" data-toggle="collapse" aria-expanded="false" class="text-left">
                    <i class="fa fa-plus-square-o">
                    </i>
                    <span class="nav-text">
                        Puntos Good
                    </span>
                </a>
                <div class="sub-menu collapse secondary list-style-circle" id="submenu10">
                    <ul>
                        <li>
                            <a href="{{route('admin.products.index.good')}}" class="text-left">
                                <i class="fa fa-bookmark">
                                </i>
                                Productos Good
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            @endrole

            @role('mercando')
            <li class="has-submenu">
                <a href="#submenu11" data-toggle="collapse" aria-expanded="false" class="text-left">
                    <i class="fa fa-plus-square-o">
                    </i>
                    <span class="nav-text">
                        Puntos Mercando
                    </span>
                </a>
                <div class="sub-menu collapse secondary list-style-circle" id="submenu11">
                    <ul>

                        <li>
                            <a href="{{route('admin.products.index.mercando')}}" class="text-left">
                                <i class="fa fa-bookmark">
                                </i>
                                Productos Mercando
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            @endrole
        </ul>
    </div>
</aside>
<aside class="right-sidebar-wrap">
    <ul class="sidebar-tab list-unstyled clearfix font-header font-11 bg-main">
        <li><a href="#sideTaskTab" data-toggle="tab" class="text-muted">Tareas</a></li>
        <li><a href="#sideAlertTab" data-toggle="tab" class="text-muted">Alertas</a></li>
    </ul>
    <div class="sidenav-inner">
        <div class="tab-content">
            <!-- Task Tab -->
            <div class="tab-pane fade" id="sideTaskTab">
                <div class="list-group font-12">
                    <a href="task.html" class="list-group-item">
                        Alerta
                        <div class="progress progress-striped progress-sm active m-t-5 no-m">
                            <div class="progress-bar progress-bar-success" style="width: 60%;"></div>
                        </div>
                    </a>
                    <a href="task.html" class="list-group-item">
                        Alerta
                        <span class="badge badge-info">31</span>
                    </a>
                </div>
            </div><!-- /.tab-pane -->

            <!-- Alert Tab -->
            <div class="tab-pane fade" id="sideAlertTab">
                <div class="content-wrap">
                    <div class="alert alert-warning alert-dismissible font-12 m-b-10" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        Reunion at 10:00 AM
                    </div>
                </div>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
    </div>
</aside>
