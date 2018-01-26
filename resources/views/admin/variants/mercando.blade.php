@extends('templates.dash')

@section('titulo', 'Listado de variantes')

@section('content')
    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Variantes de Mercando</div>
            <div class="panel-body">
                {!! Alert::render() !!}
                <input type="button" class="btn btn-danger" id="update" value="Actualizar">
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "asc" ]]' id="variants" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Variante</th>
                            <th>Precio Unitario</th>
                            <th>Unidades Vendidas</th>
                            <th>Puntaje</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="divLoading" style="display: none; margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(44, 104, 140); z-index: 30001; opacity: 0.8;"> <p style="position: absolute; color: White; top: 50%; left: 35%; font-size: 30px;"> Cargando, espere por favor...  <img src="https://loading.io/spinners/balls/index.circle-slack-loading-icon.gif" width="100px">  </p>  </div>

@stop
@push('scripts')
    <script>

        $(function() {



            var table = $('#variants').DataTable({

                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                responsive: true,
                processing: true,
                serverSide: false,
                deferRender: true,
                pagingType: "full_numbers",
                ajax: '{{route('admin.variants.search_mercando')}}',
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'title', name: 'title', orderable: true, searchable: true },
                    { data: 'price', name: 'price', orderable: true, searchable: true },
                    { data: 'sold_units', name: 'sold_units', orderable: true, searchable: true},
                    { data: 'percentage', name: 'percentage', orderable: true, searchable: true}

                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
            });


            $('#update').click( function() {

                $("#g").prop("disabled",true);
                $("div#divLoading").show();

                var data = table.$('input, select').serialize();

                $.ajax({
                    url: "{{route('admin.variants.update_mercando')}}",
                    data: { value: data, _token: '{{ csrf_token() }}'},
                    type: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        $("div#divLoading").hide();


                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    }

                });

                table._fnAjaxUpdate();
                return false;
            } );
        });

    </script>
@endpush