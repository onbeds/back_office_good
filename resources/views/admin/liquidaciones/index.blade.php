@extends('templates.dash')

@section('titulo', 'Listado de liquidaciones')

@section('content')
    <div class="box">
        <div class="panel panel-default">
            <div class="panel-heading font-header">Listado de liquidaciones</div>
            <br>
            <div class="col-md-3" style="text-align: center"></div>
            <div class="col-md-6" style="text-align: left; font-family: 'Times New Roman', Times, serif; font-size: 15px">
                <p align="justify" style="color: #FF5733;">
                    Queremos recordarles que, de acuerdo a los términos establecidos en el contrato de vendedor independiente, es un requisito para el pago de las comisiones causadas en cada periodo el haber subido a la plataforma los documentos requeridos. A saber, RUT que contenga la actividad identificada con el código 8299, Copia simple del documento de identificación y Certificación bancaria que acredite la titularidad de la cuenta donde se realizarán los pagos.
                </p>
                <p align="justify" style="color: #FF5733;">
                    Adicionalmente, hacemos énfasis en que al monto de las comisiones brutas se le realizarán los descuentos contractuales y retenciones legales a los que haya lugar, de acuerdo con el contrato de vendedor independiente y demás normativa aplicable.
                </p >
                <p align="justify" style="color: #FF5733;">
                    En consecuencia, a las personas que no hayan completado los requisitos para el pago y/o cuya comisión sea igual o inferior a 0 pesos después de descuentos y retenciones, Tienda Good acumulará los saldos brutos y los pagará en el siguiente corte, haciendo los descuentos contractuales y retenciones legales a los que haya lugar sobre los saldos acumulados.
                </p>

            </div>
            <div class="col-md-3" style="text-align: center"></div>
            <div class="panel-body" style="text-align: left;"> 
                <br>
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <table data-order='[[ 0, "desc" ]]' id="tabla_liquidaciones" class="table table-striped font-12 dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th style="text-align: center">Fecha de inicio y final de corte</th> 
                            <th style="text-align: center">Comisión antes de descuentos</th>
                            <th style="text-align: center">Descuentos</th>
                            <th style="text-align: center">Comisión total</th> 
                            <th style="text-align: center">Transferencia</th>
                            <th style="text-align: center">Bono 30%</th>
                            <th style="text-align: center">Estado</th>
                            <th style="text-align: center">Extractos</th>
                           <!-- <th style="text-align: center">Acción</th> -->
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
            var table = $('#tabla_liquidaciones').DataTable({
 
                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pagingType: "full_numbers",
                ajax: '{{route('admin.liquidaciones.data')}}',
                columns: [  
                    { data: 'date', name: 'date', orderable: true, searchable: true }, 
                    { data: 'total', name: 'total', orderable: true, searchable: true },
                    { data: 'rete_fuente', name: 'rete_fuente', orderable: true, searchable: true },
                    { data: 'total_paga', name: 'total_paga', orderable: true, searchable: true },     
                    { data: 'consignacion', name: 'consignacion', orderable: true, searchable: true },
                    { data: 'bono', name: 'bono', orderable: true, searchable: true },
                    { data: 'estado', name: 'estado', orderable: true, searchable: true },
                    { data: 'extractos', name: 'extracto', orderable: true, searchable: true },
                   // { data: 'edit', name: 'edit', orderable: true, searchable: false}
                ],
                language: {
                    url: "{{ asset('css/Spanish.json') }}"
                }
            });

        });

    </script>
@endpush