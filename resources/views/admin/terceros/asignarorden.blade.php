@extends('templates.dash')

@section('titulo','Tercero - Asignar Orden')

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-md-6 col-md-offset-3"   >
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Tercero - Asignar Orden</h3></div>
                <div class="panel-body">
                    <div class="form-group text-left">
                        <label for="email">Ingrese el numero de orden</label>
                        <input id="noorden" name="noorden" class="form-control" type="text">
                        <input id="orden_id" name="noorden" class="form-control" type="hidden">
                    </div>
                    <div class="form-group text-right">
                        <a class="btn btn-danger" id="btnBuscarOrden" onclick="buscarOrden()" style="bottom: 47.7px; position: relative">Buscar Orden</a>
                    </div>
                    <div class="div_orden">
                        <table id="tbl_orden" class="table table-bordered font-12">
                            <thead>
                                <tr><th class="text-center" colspan="5">Datos Orden</th></tr>
                            </thead>
                            <tbody class="tbody_orden">
                            </tbody>
                        </table>
                        <div class="form-group text-left">
                            <label for="tercero">Ingresar correo o el numero de documento de la persona</label>
                            <input id="tercero" name="tercero" class="form-control" type="text">
                            <input id="tercero_id" name="noorden" class="form-control" type="hidden">
                        </div>
                        <div class="form-group text-right">
                            <a class="btn btn-danger" id="btnBuscarTercero" onclick="buscarTercero()" style="bottom: 47.7px; position: relative">Buscar Cliente</a>
                        </div>
                    </div>
                    <div class="div_tercero">
                        <table id="tbl_cliente" class="table table-bordered font-12">
                            <thead>
                                <tr><th class="text-center" colspan="5">Datos Cliente</th></tr>
                            </thead>
                            <tbody class="tbody_tercero">
                            </tbody>
                        </table>
                        <div class="btnAsignar form-group">
                            <button class="btn btn-danger" id="btnAsignar" onclick="asignarOrden()" type="button">Asignar Orden</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

                                $(document).ready(function () {
                                    $(".div_orden").hide();
                                    $(".div_tercero").hide();
                                });

                                function buscarOrden() {
                                    var body_orden = '';
                                    var no_orden = $("#noorden").val()
                                    $.ajax({url: '{{route("admin.terceros.getorden")}}',
                                        dataType: "json", type: "POST",
                                        data: {no_orden: no_orden},
                                        success: function (data) {
                                            if (!data.error) {
                                                body_orden += '<tr><th class="text-left">Nombres:</th><td class="text-left">' + data.tienda + '</td></tr>';
                                                body_orden += '<tr><th class="text-left">Estado:</th><td class="text-left">' + data.estado + '</td></tr>';
                                                $(".tbody_orden").html(body_orden);
                                                $("#orden_id").val(data.id);
                                                $(".div_orden").show();
                                            } else {
                                                $("#btnCambiarPadre").hide();
                                                $(".div_orden").hide();
                                                $(".div_tercero").hide();
                                                alert(data.error);
                                            }
                                        }
                                    });
                                }

                                function buscarTercero() {
                                    var body_tercero = '';
                                    $.ajax({url: '{{route("admin.terceros.getdata")}}',
                                        dataType: "json", type: "POST",
                                        data: {textbuscar: $("#tercero").val(), id: '#', _token: '#'},
                                        success: function (data) {
                                            if (!data.error) {
                                                body_tercero += '<tr><th class="text-left">Nombres:</th><td class="text-left">' + data.tercero.nombre + '</td></tr>';
                                                body_tercero += '<tr><th class="text-left">Numero Intificación:</th><td class="text-left">' + data.tercero.identificacion + '</td></tr>';
                                                body_tercero += '<tr><th class="text-left">Email:</th><td class="text-left">' + data.tercero.email + '</td></tr>';
                                                body_tercero += '<tr><th class="text-left">Tipo Cliente:</th><td class="text-left">' + data.tercero.tipo_cliente + '</td></tr>';
                                                $("#tercero_id").val(data.tercero.id);
                                                $(".div_tercero").show();
                                            } else {
                                                alert(data.error);
                                            }
                                            $(".tbody_tercero").html(body_tercero);
                                        }
                                    });
                                }


                                function asignarOrden() {
                                    var msn = "";
                                    var tercero_id = $("#tercero_id").val();
                                    var orden_id = $("#orden_id").val();
                                    $("#btnAsignar").hide();
                                    $("#btnBuscarTercero").hide();
                                    $("#btnBuscarOrden").hide();
                                    $.ajax({url: '{{route("admin.terceros.setorden")}}',
                                        dataType: "json", type: "POST",
                                        data: {tercero_id: tercero_id, orden_id: orden_id},
                                        success: function (response) {
                                            if (response == true) {
                                                alert('Se asignado la orden de venta correctamente');
                                            } else {
                                                var msn = response;
                                                $("#msn_padre").css("color", "red");
                                                $("#msn_padre").css("font-size", "15px");
                                                $("#msn_padre").text(msn);
                                            }
                                            $("#btnAsignar").show();
                                            $("#btnBuscarTercero").show();
                                            $("#btnBuscarOrden").show();
                                        }
                                    });
                                }

</script>
@endsection

