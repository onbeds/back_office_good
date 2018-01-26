@extends('templates.dash')

@section('titulo','Good')

@section('content')

    <section class="invoice">

@if ($nivel == 1)
        <div class="box">
            <div class="panel panel-default">
                <div class="panel-heading font-header">Nivel 1</div>
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
                        <table data-order='[[ 0, "asc" ]]' id="terceros1" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Puntos</th>
                                <th>Referidos</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endif
@if ($nivel == 2)
        <div class="box">
            <div class="panel panel-default">
                <div class="panel-heading font-header">Nivel 2</div>
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
                        <table data-order='[[ 0, "asc" ]]' id="terceros2" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Puntos</th>
                                <th>Referidos</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endif
@if ($nivel == 3)
        <div class="box">
            <div class="panel panel-default">
                <div class="panel-heading font-header">Nivel 3</div>
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
                        <table data-order='[[ 0, "asc" ]]' id="terceros3" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Puntos</th>
                                <th>Referidos</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endif 
</section>


@endsection

@push('scripts')
    <script>
        $(document).ready(function(){

@if ($nivel == 1)
                $('#terceros1').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    pagingType: "full_numbers",
                    ajax: {
                        url: '{{route('admin.one')}}',
                        type: 'post',
                        data: function ( d ) {
                            d.level = 1;
                            d.id = '{{currentUser()->id}}'
                        }
                    },
                   columns: [
                        { data: 'id', name: 'id', orderable: true, searchable: false },
                        { data: 'nombres', name: 'nombres', orderable: true, searchable: true  },
                        { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true  },
                        { data: 'puntos', name: 'puntos', orderable: true },
                        { data: 'referidos', name: 'referidos', orderable: true }
                    ],
                    language: {
                        url: "{{ asset('css/Spanish.json') }}"
                    },

                });
@endif
@if ($nivel == 2)
            $('#terceros2').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pagingType: "full_numbers",
                ajax: {
                    url: '{{route('admin.two')}}',
                    type: 'post',
                    data: function ( d ) {
                        d.level = 2;
                        d.id = '{{currentUser()->id}}'
                    }
                },
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: false },
                    { data: 'nombres', name: 'nombres', orderable: true, searchable: true  },
                    { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true  },
                    { data: 'puntos', name: 'puntos', orderable: true },
                    { data: 'referidos', name: 'referidos', orderable: true }
                    
                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                },

            });
@endif
@if ($nivel == 3)
            $('#terceros3').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pagingType: "full_numbers",
                ajax: {
                    url: '{{route('admin.tree')}}',
                    type: 'post',
                    data: function ( d ) {
                        d.level = 3;
                        d.id = '{{currentUser()->id}}'
                    }
                },
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: false },
                    { data: 'nombres', name: 'nombres', orderable: true, searchable: true  },
                    { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true  },
                    { data: 'puntos', name: 'puntos', orderable: true },
                    { data: 'referidos', name: 'referidos', orderable: true }
                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                },

            });
@endif
        });
    </script>
@endpush

