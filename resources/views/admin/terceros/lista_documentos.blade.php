@extends('templates.dash')

@section('titulo', 'Listado de Terceros')

@section('content')
    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Documentación </div>
            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-info fade in col-sm-12 col-md-12 col-lg-12">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <ul>
                            <li>{{ session('status') }}</li>  
                        </ul>
                    </div>
                @endif
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "asc" ]]' id="terceros_cambio" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Identificacón</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
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
    $(document).ready(function(){
        $(function() {
            $('#terceros_cambio').DataTable({
               //dom: 'Bfrtip',
               //buttons: [
                //    'copy', 'csv', 'excel', 'pdf', 'print'
               // ],
               responsive: true,
               processing: true,
               serverSide: true,
               deferRender: true,
               pagingType: "full_numbers",
               ajax: '{{route('admin.terceros.data')}}',
               columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: false },
                    { data: 'identificacion', name: 'identificacion', orderable: true, searchable: true },
                    { data: 'nombres', name: 'nombres', orderable: true, searchable: true, searchable: true },
                    { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true },
                    { data: 'email', name: 'nivel_1', orderable: true, searchable: true },
                    { data: 'rut', name: 'rut', orderable: false, searchable: false, className: "centrar"},
                    { data: 'CC', name: 'CC', orderable: false, searchable: false, className: "centrar"},
                    { data: 'BANK', name: 'rut', orderable: false, searchable: false, className: "centrar"}
                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                },
         
            });

        });
    });
</script>
@endpush