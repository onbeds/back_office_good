@extends('templates.dash')

@section('titulo', 'Listado de productos')

@section('content')
    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Listado de productos de Good</div>
            <div class="panel-body">
                {!! Alert::render() !!}
                {{--<input type="button" class="btn btn-danger" id="update" value="Actualizar">--}}
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "asc" ]]' id="tabla_productos" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Tienda</th>
                            <th>Tipo Producto</th>
                            <th>Acción</th>
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
        var table = $('#tabla_productos').DataTable({
      
            dom: 'Bfrtip',
            buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
               responsive: true,
               processing: true,
               serverSide: true,
               deferRender: true,
               pagingType: "full_numbers",
               ajax: '{{route('admin.products.good')}}',
               columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'title', name: 'title', orderable: true, searchable: true },
                    { data: 'shop', name: 'shop', orderable: true, searchable: true },
                    { data: 'tipo_producto', name: 'tipo_producto', orderable: true, searchable: true},
                    { data: 'edit', name: 'edit', orderable: true, searchable: false}
                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
        });

    });

</script>
@endpush