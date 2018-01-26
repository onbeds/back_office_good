@extends('templates.dash')

@section('titulo', 'Listado de Tipos')

@section('content')
    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Listado de Tipos</div>
            <div class="panel-body">
                {!! Alert::render() !!}
                @if (session('message'))
                    <div class="alert alert-success fade in col-sm-12">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session('message') }}
                    </div>
                @endif

                <a class="btn btn-danger" href="{{route('admin.tipos.create')}}">Crear</a>
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "asc" ]]' id="tipos" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Puntos Minimos</th>
                            <th class="text-center">Puntos Maximos</th>
                            <th class="text-center">Comision Maxima</th>
                            <th class="text-center">Acción</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
   <script>
        $(function() {
            var table = $('#tipos').DataTable({

                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                responsive: true,
                processing: true,
                serverSide: false,
                deferRender: true,
                pagingType: "full_numbers",
                ajax: '{{route('admin.tipos.data')}}',
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'nombre', name: 'nombre', orderable: true, searchable: true },
                    { data: 'puntos_minimos', name: 'puntos_minimos', orderable: true, searchable: true },
                    { data: 'puntos_maximos', name: 'puntos_maximos', orderable: true, searchable: true},
                    { data: 'comision_maxima', name: 'comision_maxima', orderable: true, searchable: true},
                    { data: 'action', name: 'action', orderable: true, searchable: true}

                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
            });

        });

    </script>
@endpush