@extends('templates.dash')

@section('titulo','Tercero - Buscar')

@section('content')

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <a href="#step-1"  class="btn btn-primary btn-circle">1</a>
                        <p>Buscar</p>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-1">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3 class="text-center">Buscar Información de usuario</h3>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <label for="email">Ingresar correo o el numero de documento de la persona</label>
                                <input id="search" name="search" type="text" class="form-control" required>
                                <br>
                                <button id="searching" class="btn btn-danger">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="stepwizard">
                <div class="setup-panel text-left">

                    <h3 class="text-center">Información de usuario</h3>
                    <input type="hidden" id="padre_id">
                    <div class="container">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Identificacion</th>
                                    <th>Email</th>
                                    <th>Telefono</th>
                                    <th>Fecha Inscripción</th>
                                    <th>Mis Puntos</th>
                                    <th>Tienda Good</th>
                                    <th>Mercando</th>
                                    <th>Liquidación</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><span id="first_name"></span></td>
                                    <td><span id="last_name"></span></td>
                                    <td><span id="dni"></span></td>
                                    <td><span id="email"></span></td>
                                    <td><span id="phone"></span></td>
                                    <td><span id="d"></span></td>
                                    <td><span id="puntos"></span></td>
                                    <td><span id="good"></span></td>
                                    <td><span id="mercando"></span></td>
                                    <td><span id="liquidacion"></span></td>
                                    <td><span id="tipo"></span></td>
                                    <td><span id="state"></span></td>


                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row setup-content" id="step-1">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Información del padre</div>
            <div class="panel-body">
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th>Nombres</th>
                            <th>Identificacion</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><span id="nombre_padre"></span></td>
                            <td><span id="identidicacion_padre"></span></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

   <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Nivel 1</div>
            <div class="panel-body">
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "desc" ]]' id="nivel_1" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Referidos</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Nivel 2</div>
            <div class="panel-body">
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "desc" ]]' id="nivel_2" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Referidos</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="box oculto">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Nivel 3</div>
            <div class="panel-body">
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "desc" ]]' id="nivel_3" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Referidos</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')

    <script type="text/javascript">


        $(function() {

            $("#padre_id").val('');

            var uno = $('#nivel_1').DataTable({

                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pagingType: "full_numbers",

                ajax: {
                    url:'{{route('admin.terceros.index.searching.levels')}}',
                    type: 'post',
                    dataType: 'json',
                    data :function ( d ) {
                        d.padre_id = Number($("#padre_id").val());
                        d.level = 1;
                    },
                    /*success: function (data) {
                        if(data) {
                            console.log('Se ha buscado bien.');
                        }
                    }*/
                },
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'nombres', name: 'nombres', orderable: true, searchable: true },
                    { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true },
                    { data: 'email', name: 'email', orderable: true, searchable: true },
                    { data: 'level_1', name: 'level_1', orderable: true, searchable: true }

                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
            });

            var dos = $('#nivel_2').DataTable({

                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pagingType: "full_numbers",

                ajax: {
                    url:'{{route('admin.terceros.index.searching.levels')}}',
                    type: 'post',
                    dataType: 'json',
                    data :function ( d ) {
                        d.padre_id = Number($("#padre_id").val());
                        d.level = 2;
                    },
                    /*success: function (data) {
                        if(data) {
                            console.log('Se ha buscado bien.');
                        }
                    }*/
                },
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'nombres', name: 'nombres', orderable: true, searchable: true },
                    { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true },
                    { data: 'email', name: 'email', orderable: true, searchable: true },
                    { data: 'level_1', name: 'level_1', orderable: true, searchable: true }

                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
            });

            var tres = $('#nivel_3').DataTable({

                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pagingType: "full_numbers",

                ajax: {
                    url:'{{route('admin.terceros.index.searching.levels')}}',
                    type: 'post',
                    dataType: 'json',
                    data :function ( d ) {
                            d.padre_id = Number($("#padre_id").val());
                            d.level = 3;
                    },
                    /*success: function (data) {
                        if(data) {
                            console.log('Se ha buscado bien.');
                        }
                    }*/
                },
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'nombres', name: 'nombres', orderable: true, searchable: true },
                    { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true },
                    { data: 'email', name: 'email', orderable: true, searchable: true },
                    { data: 'level_1', name: 'level_1', orderable: true, searchable: true }

                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
            });


            function isEmpty(value) {

                if (value.length == 0) {

                    return false
                }

                return true;
            }

            function number_format(amount, decimals) {

                amount += ''; // por si pasan un numero en vez de un string
                amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

                decimals = decimals || 0; // por si la variable no fue fue pasada

                // si no es un numero o es igual a cero retorno el mismo cero
                if (isNaN(amount) || amount === 0)
                    return parseFloat(0).toFixed(decimals);

                // si es mayor o menor que cero retorno el valor formateado como numero
                amount = '' + amount.toFixed(decimals);

                var amount_parts = amount.split('.'),
                    regexp = /(\d+)(\d{3})/;

                while (regexp.test(amount_parts[0]))
                    amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

                return amount_parts.join('.');
            }

            function search() {

                var value = $('#search').val();

                if(isEmpty(value)) {

                    console.log(value);

                    var date = JSON.parse( $.ajax({
                        url: '{{route('admin.terceros.index.searching.data')}}',
                        type: 'post',
                        data: {search: value},
                        dataType: 'json',
                        async:false,
                        success: function (json) {
                            console.log(json);
                            return json;
                        },

                        error : function(xhr, status) {
                            console.log('Disculpe, existió un problema');
                        },

                        complete : function(xhr, status) {
                            console.log('Petición realizada');
                        }
                    }).responseText);


                    if (date.msg) {

                        $("#padre_id").val('');
                        $('#first_name').html('');
                        $('#last_name').html('');
                        $('#email').html('');
                        $('#dni').html('');
                        $('#phone').html('');
                        $('#d').html('');
                        $('#tipo').html('');
                        $('#state').html('');
                        $('#nombre_padre').html('');
                        $('#identidicacion_padre').html('');
                        $('#puntos').html('');
                        $('#good').html('');
                        $('#mercando').html('');
                        $('#liquidacion').html('');

                        uno.ajax.reload();
                        dos.ajax.reload();
                        tres.ajax.reload();


                        swal(
                            'Lo sentimos, no se encontró información. Verifique los datos.',
                            '¡Ok!',
                            'success'
                        );
                    }

                    if (date.info) {


                        $("#padre_id").val(date.info.id);
                        $('#first_name').html('' + date.info.nombres.toUpperCase());
                        $('#last_name').html('' + date.info.apellidos.toUpperCase());
                        $('#email').html('' + date.info.email.toUpperCase());
                        $('#dni').html('' + date.info.identificacion);
                        $('#phone').html('' + date.info.telefono);
                        $('#d').html('' + date.info.created_at);
                        $('#tipo').html('' + date.tipo.toUpperCase());
                        $('#nombre_padre').html('' + date.padre.nombres.toUpperCase());
                        $('#identidicacion_padre').html('' + date.padre.identificacion.toUpperCase());
                        $('#puntos').html('' + date.puntos);

                        uno.ajax.reload();
                        dos.ajax.reload();
                        tres.ajax.reload();

                        if (date.good != 0) {
                            $('#good').html('<input type="checkbox" id="good-tienda" checked> ');
                        } else {
                            $('#good').html('<input type="checkbox" id="good-tienda"> ');
                        }

                        if (date.mercando != 0) {
                            $('#mercando').html('<input type="checkbox" id="mercando-tienda" checked> ');
                        } else {
                            $('#mercando').html('<input type="checkbox" id="mercando-tienda"> ');
                        }

                        if (date.info.state == true) {
                            $('#state').html('ACTIVO');
                        }else {
                            $('#state').html('INACTIVO');
                        }

                        $( "#imagen" ).attr('src', "{{url()}}/" + date.info.avatar);



                        $('#liquidacion').html('<!-- Trigger the modal with a button -->\n' +
                            '<a class="btn" data-toggle="modal" data-target="#myModal">Ver</a>\n' +
                            '\n' +
                            '<!-- Modal -->\n' +
                            '<div id="myModal" class="modal fade" role="dialog">\n' +
                            '  <div class="modal-dialog" style="width: 100%;">\n' +
                            '\n' +
                            '    <!-- Modal content-->\n' +
                            '    <div class="modal-content" width="100%">\n' +
                            '      <div class="modal-header" width="100%">\n' +
                            '        <button type="button" class="close" data-dismiss="modal">&times;</button>\n' +
                            '        <h4 style="color: #ed7d01" class="modal-title">Última Liquidación</h4>\n' +
                            '      </div>\n' +
                            '      <div class="modal-body" width="100%">\n' +
                            '        <div id="example-2" ></div><br><hr><br>\n' +
                            '        <h4 style="color: #ed7d01" class="modal-title">Detalle</h4>\n' +
                            '        <div id="example"></div>\n' +
                            '      </div>\n' +
                            '      <div class="modal-footer" width="100%">\n' +
                            '        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>\n' +
                            '      </div>\n' +
                            '    </div>\n' +
                            '\n' +
                            '  </div>\n' +
                            '</div>');



                        var tabla = '<table class="display" cellspacing="0" width="100%" id="lista">';
                        tabla += '<thead>';
                        tabla += '<tr>';
                        tabla += '<th>Nombres</th><th>Apellidos</th><th>Nivel</th><th>Orden</th><th>Puntos</th><th align="left" style="text-align: left">Comision</th><th>Total</th>';
                        tabla += '</tr>';
                        tabla += '</thead>';
                        tabla += '<tbody>';
                        var tr = '';

                        var tabla1 = '<table class="display" cellspacing="0" width="100%" id="lista1">';
                        tabla1 += '<thead>';
                        tabla1 += '<tr>';
                        tabla1 += '<th style="left: auto">Fecha</th><th>Valor sin descuentos</th><th>Rete Fuente</th><th>Rete Ica</th><th>Prime</th><th>Iva Prime</th><th>Descuentos Administrativos</th><th>Valor Total</th>';
                        tabla1 += '</tr>';
                        tabla1 += '</thead>';
                        tabla1 += '<tbody>';
                        var tr1 = '';

                        if (date.liquidacion.length > 0) {
                            jQuery.each(date.liquidacion, function( i, val ) {

                                tr += '<tr>';
                                tr += '<td class="text-left">' + val.nombres.toUpperCase() + '</td> <td class="text-left">' + val.apellidos.toUpperCase() + '</td> <td class="text-left">' + val.nivel + '</td> <td class="text-left">' + val.name + '</td> <td class="text-left">' + val.puntos + '</td> <td class="text-left">$' + number_format(val.comision_puntos, 0) + '</td> <td class="text-left">$' + number_format(val.valor_comision, 0) + '</td>';
                                tr += '</tr>';

                            });
                        }


                        if (date.descuentos.length > 0) {

                            var descuentos = parseFloat(date.descuentos[0].transferencia) + parseFloat(date.descuentos[0].extracto) + parseFloat(date.descuentos[0].administrativo);
                            tr1 += '<tr>';
                            tr1 += '<td  class="text-left">' + date.descuentos[0].created_at + '</td><td class="text-left">' + number_format(parseFloat(date.descuentos[0].valor_comision), 0) + '</td><td class="text-left">' + number_format(parseFloat(date.descuentos[0].rete_fuente), 0) + '</td> <td class="text-left">' + number_format(parseFloat(date.descuentos[0].rete_ica), 0) + '</td> <td class="text-left">' + number_format(parseFloat(date.descuentos[0].prime), 0) + '</td> <td class="text-left">' + number_format(parseFloat(date.descuentos[0].prime_iva), 0) + '</td> <td class="text-left">' + number_format(descuentos, 0) + '</td> <td class="text-left">' + number_format(parseFloat(date.descuentos[0].valor_comision_paga), 0) + '</td> ';
                            tr1 += '</tr>';

                        };


                        tabla += tr;
                        tabla += '</tbody></table>';

                        tabla1 += tr1;
                        tabla1 += '</tbody></table>';


                        $('#example').html( tabla );
                        $('#example-2').html( tabla1 );
                        $('#lista').DataTable();
                        $('#lista1').DataTable();



                    }

                } else {

                    uno.ajax.reload();
                    dos.ajax.reload();
                    tres.ajax.reload();

                    swal(
                        'Por favor, ingrese un correo o el número de documento a buscar...',
                        '¡Error!',
                        'error'
                    );
                }
            }

            $( "#searching" ).click(function() {
                search();
            });
        });








    </script>
@stop
