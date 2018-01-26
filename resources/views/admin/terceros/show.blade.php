@extends('templates.dash')

@section('titulo', 'Listado de Terceros Hijos')

@section('content')

        <div class="box">
            <div class="panel panel-default">
                <div class="panel-heading font-header">Referidos Good</div>
                <div class="panel-body">

                    <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <table data-order='[[ 0, "asc" ]]' id="good" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombres</th>
                                <th>Email</th>
                                <th>Codigo Referido</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        
                        <div class="col-md-12">
                            <a class="btn btn-danger" href="{{route('admin.terceros.index')}}" role="button">Atras</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@stop
@push('scripts')
<script>
    $(function() {
        $('#good' ).DataTable({
            //dom: 'Bfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "post",
                "url": "{{route('admin.terceros.anyshow')}}",
                "data": function ( d ) {
                    d.id = "{{$id}}";
                }
            },
            columns: [
                { data: 'id', name: 'id', orderable: true, searchable: false },
                { data: 'nombres', name: 'nombres', orderable: true, searchable: true  },
                { data: 'email', name: 'email', orderable: true },
                { data: 'apellidos', name: 'apellidos', orderable: true }
            ],
            "language": {
                "url": "{{ asset('css/Spanish.json') }}"
            }
        });
    });
</script>
@endpush
