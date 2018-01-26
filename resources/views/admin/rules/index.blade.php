@extends('templates.dash')

@section('titulo', 'Listado de Reglas')

@section('content')
    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Listado de Reglas</div>
            <div class="panel-body">
                {!! Alert::render() !!}
                @if (session('message'))
                    <div class="alert alert-success fade in col-sm-12">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session('message') }}
                    </div>
                @endif

                <a class="btn btn-danger" href="{{route('admin.rules.create')}}">Crear</a>
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "asc" ]]' id="rules" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Red</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Nivel</th>
                            <th class="text-center">Comisión por puntos</th>
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
            var table = $('#rules').DataTable({

                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                responsive: true,
                processing: true,
                serverSide: false,
                deferRender: true,
                pagingType: "full_numbers",
                ajax: '{{route('admin.rules.data')}}',
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'red', name: 'red', orderable: true, searchable: true },
                    { data: 'tipo', name: 'tipo', orderable: true, searchable: true},
                    { data: 'nivel', name: 'nivel', orderable: true, searchable: true},
                    { data: 'puntos', name: 'puntos', orderable: true, searchable: true},
                    { data: 'action', name: 'action', orderable: false, searchable: false}

                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
            });

        });

    </script>
@endpush