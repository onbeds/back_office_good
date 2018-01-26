@extends('templates.dash')

@section('titulo','Busquedas Referidos')

@section('content')

    <section class="invoice">
        <div class="page-header no-breadcrumb font-header">Resultados:</div>
        <div class="panel panel-default">
        

        <div class="box">
            <div class="panel panel-default">

                @if(isset($level))
                    <div class="panel-heading font-header">Su referido se encuentra en el nivel {{ $level }} </div>
                @else
                    <div class="panel-heading font-header">Listado</div>
                @endif
                <div class="panel-body">

                    <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        @if (isset($results))

                        <table data-order='[[ 0, "asc" ]]' id="referidos" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombres</th>
                                <th>Email</th>
                            </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="text-left">{{$results['id']}}</td>
                                    <td class="text-left">{{$results['nombres']}}</td>
                                    <td class="text-left">{{$results['email']}}</td>
                                </tr>

                            </tbody>
                        </table>
                        @endif
                            @if(isset($err))
                                <br>
                                {{$err}}
                                <br><br>
                            @endif
                            <br> 
                    </div>
                </div>
            </div>
        </div>
</section>


@endsection

@section('scripts')
    <script>

        $(function() {
            $('#referidos').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                //buttons: [
                  //  'copy', 'csv', 'excel', 'pdf', 'print'
                //],
                
                "language": {
                    "url": "{{ asset('css/Spanish.json') }}"
                }
            });

        });

    </script>
@stop
