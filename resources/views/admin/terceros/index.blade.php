@extends('templates.dash')

@section('titulo', 'Listado de Terceros')

@section('content')
<div class="box">
    <div class="panel panel-default">
        <div class="panel-heading font-header">Listado Referidos</div>
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
                <table data-order='[[ 0, "asc" ]]' id="terceros_data" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Identificacón</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Mis Puntos</th>
                            <th style="text-align: center;">Estado</th>
                            <th style="text-align: center;">Referidos</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script type="text/javascript">
    
 @if(Session::has('flash_msg'))
                  swal({ 
                  html: $('<div>').text('{{Session::get('flash_msg')}}'),
                  animation: false,
                  customClass: 'animated tada'
                  });
@endif     
    
    $(document).ready(function () {
        $(function () {
            $('#terceros_data').DataTable({
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
                    {data: 'id', name: 'id', orderable: true, searchable: false},
                    {data: 'identificacion', name: 'identificacion', orderable: true, searchable: true},
                    {data: 'nombres', name: 'nombres', orderable: true, searchable: true},
                    {data: 'apellidos', name: 'apellidos', orderable: true},
                    {data: 'totalpuntos', name: 'totalpuntos', orderable: true},
                    {data: 'state', name: 'state', orderable: true, searchable: false, className: "centrar"},
                    {data: 'action', name: 'red', orderable: false, searchable: false, className: "centrar"},
                    {data: 'edit', name: 'editar', orderable: false, searchable: false, className: "centrar"}
                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                },

            });

        });
    });
    /*
     *  Se cambia el estado de un escenario específico
     */
    function cambiarEstado(id, state) {
        if (state == true) {
            if (!confirm('Recuerde que al inactivar el tercero se perdera la red y se le asignara al padre correspondiente, ¿Desea continuar?'))
            {
                return false;
            }
        }
        $.ajax({
            url: '{{route("admin.terceros.setstate")}}',
            dataType: "json", type: "POST",
            data: {id: id},
            success: function (response) {
                if (response == true) {
                    $('#terceros_data').DataTable().ajax.reload(null,false);
                }
            }
        });
    }
</script>
@endpush