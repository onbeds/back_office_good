@extends('templates.dash')

@section('titulo', 'Good')

@section('content')
<style type="text/css">
    .tdizquierdo{
        text-align: left; 
        padding: 0px 0px; 
    }
    .tdderecho{
        text-align: right; 
        padding: 0px 0px; 
    }
</style>
    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Extracto de comisiones del mes de {{$mes}}</div>
            <div class="panel-body">
                {!! Alert::render() !!}
                {{--<input type="button" class="btn btn-danger" id="update" value="Actualizar">--}}
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th style="text-align: left">Nombres</th>
                            <th style="text-align: left">Apellidos</th>
                            <th style="text-align: left">Fecha</th>
                            <th style="text-align: left">Orden</th>
                            <th style="text-align: right">Puntos</th>
                            <th style="text-align: right">Valor de comisión</th> 
                        </tr>
                        </thead>
                        <tbody>

                        {{--*/  $TOTAL_COMISION = 0 /*--}}

                        @foreach ($liquidaciones_detalles as $value)
                        <tr>
                            <td style="text-align: left">{{ucwords($value->nombres)}}</td>
                            <td style="text-align: left">{{ucwords($value->apellidos)}}</td>
                            <td style="text-align: left">{{$value->created_at}}</td>
                            <td style="text-align: left">{{$value->name}}</td>
                            <td style="text-align: right;">{{$value->puntos}}</td>
                            <td style="text-align: right">{{number_format($value->valor_comision)}}</td> 
                        </tr>  

                        {{--*/  $TOTAL_COMISION += $value->valor_comision /*--}}

                        @endforeach
                        <tr>
                            <td style="text-align: left"></td>
                            <td style="text-align: left"></td>
                            <td style="text-align: left"></td>
                            <td style="text-align: left"></td>
                            <td style="text-align: left"></td>
                            <td style="font-weight: bold;"><br>
                                <table style="width: 100%;">

                                    <tr>
                              <td class="tdizquierdo" style="padding: 1px 1px;">Total:</td> 
                              <td class="tdderecho" style="padding: 1px 1px;">{{number_format($TOTAL_COMISION)}}</td>
                                   </tr>
                              @if ($liquidaciones_terceros->estado_id == 87)     
                                    <tr>
                              <td class="tdizquierdo" style="padding: 1px 1px;">Retefuente:</td> 
                              <td class="tdderecho" style="padding: 1px 1px;">{{number_format($liquidaciones_terceros->rete_fuente)}}</td> 
                                   </tr>
                                    <tr>
                              <td class="tdizquierdo" style="padding: 1px 1px;">Rete ICA:</td> 
                              <td class="tdderecho" style="padding: 1px 1px;">{{number_format($liquidaciones_terceros->rete_ica)}}</td> 
                                   </tr>
                                    <tr>
                              <td class="tdizquierdo" style="padding: 1px 1px;">Prime:</td> 
                              <td class="tdderecho" style="padding: 1px 1px;">{{number_format($liquidaciones_terceros->prime)}} </td> 
                                   </tr>
                                    <tr>
                              <td class="tdizquierdo" style="padding: 1px 1px;">IVA Prime:</td> 
                              <td class="tdderecho" style="padding: 1px 1px;">{{number_format($liquidaciones_terceros->prime_iva)}}</td> 
                                   </tr>
                                    <tr>
                              <td class="tdizquierdo" style="padding: 1px 1px;">Adminsitrativo y transferencia:</td>
                              <td class="tdderecho" style="padding: 1px 1px;">{{number_format($liquidaciones_terceros->transferencia + $liquidaciones_terceros->extracto + $liquidaciones_terceros->administrativo)}}</td>
                                   </tr>
                                    <tr>
                              <td class="tdizquierdo" style="padding: 1px 1px;">Comisión con descuentos:</td> 
                              <td class="tdderecho" style="padding: 1px 1px;">{{number_format($liquidaciones_terceros->valor_comision_paga)}} </td> 
                                    </tr>
                             @endif

                                 </table>
                            </td> 
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script>
/*
        $(function() {
            var table = $('#tabla_liquidaciones_general').DataTable({
 
                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pagingType: "full_numbers",
                ajax: '{{route('liquidacion.liquidaciones_extracto_comisiones_datos', ['id' => $id])}}',
                columns: [
                    { data: 'nombres', name: 'nombres', orderable: true, searchable: true },
                    { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true },
                    { data: 'name', name: 'name', orderable: true, searchable: true },
                    { data: 'puntos', name: 'puntos', orderable: true, searchable: true },
                    { data: 'valor_comision', name: 'valor_comision', orderable: true, searchable: true },
                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
            });

        });
*/
    </script>
@endpush