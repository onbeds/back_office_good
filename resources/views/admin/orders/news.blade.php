@extends('templates.dash')

@section('titulo','Ordenes - Novedades')

@section('content')

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <a href="#step-1"  class="btn btn-primary btn-circle">1</a>
                        <p>Novedades</p>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-1">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3 class="text-center">Buscar Información de orden</h3>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <label for="search">Ingresar número de orden</label>
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

    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Orden</div>
            <div class="panel-body">
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "desc" ]]' id="orders" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tienda</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Puntos</th>
                            <th style="text-align: center;">Acciones</th>
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

            var uno = $('#orders').DataTable({

                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pagingType: "full_numbers",


                ajax: {
                    url:'{{route('admin.orders.news_data')}}',
                    type: 'post',
                    dataType: 'json',
                    data :function ( d ) {

                        var value = $("#search").val();
                        if(isEmpty(value)) {
                            d.name = value;
                        }


                    }
                    /*success: function (data) {
                        if(data) {
                            console.log('Se ha buscado bien.');
                        }
                    }*/
                },
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'shop', name: 'shop', orderable: true, searchable: true },
                    { data: 'nombres', name: 'nombres', orderable: true, searchable: true },
                    { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true },
                    { data: 'email', name: 'email', orderable: true, searchable: true },
                    { data: 'estado', name: 'estado', orderable: true, searchable: true },
                    { data: 'total', name: 'total', orderable: true, searchable: true },
                    { data: 'puntos', name: 'puntos', orderable: true, searchable: true },
                    { data: 'acciones', name: 'acciones', orderable: true, searchable: true, className: "centrar" }

                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                },
                drawCallback: function (settings) {

                    $("#update").click(function() {
                        swal({
                            title: "¿Estás seguro?",
                            text: "¡Una vez hagas los cambios estos no se podrán deshacer!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                            showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', cancelButtonText: 'Cancelar',  confirmButtonText: 'Guardar'
                        }).then(function (result){

                            if (result.value) {

                                var name = $("#name").text();

                                var date = JSON.parse( $.ajax({
                                    url: '{{route('admin.orders.news_update')}}',
                                    type: 'post',
                                    data: {name: name},
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

                                if (date.info) {

                                    uno.ajax.reload();

                                    swal(
                                        '¡Cambios realizados con exito!',
                                        '¡Ok!',
                                        'success'
                                    );
                                }

                                if (date.double) {
                                    swal(
                                        '¡La orden ya ha sido revertida!',
                                        '¡Error!',
                                        'error'
                                    );
                                }

                                if (date.no_order) {
                                    swal(
                                        '¡La orden no se encontró, comuniquese con sistemas.!',
                                        '¡Error!',
                                        'error'
                                    );
                                }


                            } else {
                                swal(
                                    '¡No se han hecho cambios!',
                                    '!Sin cambios¡',
                                    'error'
                                );
                            }
                        });
                    });
                }
            });


            function isEmpty(value) {

                if (value.length == 0) {

                    return false
                }

                return true;
            }

            function search() {

                var value = $('#search').val();

                if(isEmpty(value)) {

                    uno.ajax.reload();

                } else {

                    uno.ajax.reload();

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
