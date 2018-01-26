@extends('templates.dash')

@section('titulo','Tercero - Cambiar Padre')

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step">
                    <a href="#step-1"  class="btn btn-primary btn-circle">1</a>
                    <p>Buscar</p>
                </div>
            </div>
        </div>
        <div class="row setup-content" id="step-1">
            <div class="col-xs-12">
                <div class="col-md-12">
                    <h3 class="text-center">Tercero - Cambiar Padre</h3>
                    <hr>
                    <input type="hidden" id="_token" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="id" name="id" value="{{currentUser()->id}}">
                    <input type="hidden" id="terceroid" name="terceroid" value="">
                    <input type="hidden" id="padreid" name="padreid" value="">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <label for="email">Ingresar correo o el numero de documento de la persona</label>
                            <input id="textbuscar" name="textbuscar" type="text" class="form-control" required>
                            <a class="btn btn-danger" onclick="buscarTercero()" role="button">Buscar</a>
                        </div>
                    </div>

                    <table id="referidos" class="table table-bordered font-12">
                        <thead>
                            <tr><th class="text-center" colspan="5">Datos Tercero</th></tr>
                            <tr>
                                <th>Nombres</th>
                                <th>Numero de identificación</th>
                                <th>Email</th>
                                <th>Tipo Cliente</th>
                            </tr>
                        </thead>
                        <tbody class="tercero">
                            <tr class="primer">
                                <td class="text-left" colspan="3"></td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr><th class="text-center" colspan="3">Datos Padre</th></tr>
                            <tr>
                                <th>Nombres</th>
                                <th>Email</th>
                                <th>Tipo Cliente</th>
                                <th>Numero de identificación</th>
                            </tr>
                            <tr>
                                <th style="text-align:center" colspan="5"><span id="msn_padre"></span></th>
                            </tr>
                        </thead>
                        <tbody class="padre">
                            <tr class="primer">
                                <td class="text-left" colspan="3"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

                                function buscarTercero() {
                                    var padre = '';
                                    var tercero = '';
                                    $.ajax({url: '{{route("admin.terceros.getdata")}}',
                                        dataType: "json", type: "POST",
                                        data: {textbuscar: $("#textbuscar").val(), id: $("#id").val(), _token: $("#_token").val()},
                                        success: function (data) {
                                            if (!data.error) {
                                                tercero += '<tr>';
                                                tercero += '<td class="text-left">' + data.tercero.nombre + '</td>';
                                                tercero += '<td class="text-left">' + data.tercero.identificacion + '</td>';
                                                tercero += '<td class="text-left">' + data.tercero.email + '</td>';
                                                tercero += '<td class="text-left">' + data.tercero.tipo_cliente + '</td>';
                                                tercero += '</tr>';
                                                if (!data.tercero.error) {
                                                    padre += '<tr>';
                                                    padre += '<td class="text-left">' + data.padre.nombre + '</td>';
                                                    padre += '<td class="text-left">' + data.padre.email + '</td>';
                                                    padre += '<td class="text-left">' + data.padre.tipo_cliente + '</td>';
                                                    padre += '<td class="text-left"><input id="pdr_identificacion" name="pdr_identificacion" type="text" class="form-control">';
                                                    padre += '<td class="text-left"><a class="btn btn-danger" id="btnBuscarPadre" onclick="buscarPadre()" role="button">Buscar Padre</a>';
                                                    padre += '<a class="btn btn-danger" id="btnCambiarPadre" onclick="cambiarPadre()" role="button" style="margin-left: 5px;">Cambiar Padre</a></td>';
                                                    padre += '</tr>';
                                                } else {
                                                    alert(data.tercero.error);
                                                }
                                                $("#terceroid").val(data.tercero.id);
                                            } else {
                                                alert(data.error);
                                            }
                                            $(".tercero").html(tercero);
                                            $(".padre").html(padre);
                                            $("#btnCambiarPadre").hide();
                                            $("#tipo_cliente").val(data.tercero.tipo_cliente_id);
                                        }
                                    });
                                }

                                function buscarPadre() {
                                    var identificacion = $("#pdr_identificacion").val()
                                    var padre = '';
                                    $.ajax({url: '{{route("admin.terceros.getpadre")}}',
                                        dataType: "json", type: "POST",
                                        data: {identificacion: identificacion, tercero: $("#identificacion").val(), _token: $("#_token").val()},
                                        success: function (data) {
                                            if (!data.error) {
                                                padre += '<tr>';
                                                padre += '<td class="text-left">' + data.nombre + '</td>';
                                                padre += '<td class="text-left">' + data.email + '</td>';
                                                padre += '<td class="text-left">' + data.tipo_cliente + '</td>';
                                                padre += '<td class="text-left"><input id="pdr_identificacion" name="pdr_identificacion" type="text" class="form-control" value="' + identificacion + '">';
                                                padre += '<td class="text-left"><a class="btn btn-danger" id="btnBuscarPadre" onclick="buscarPadre()" role="button">Buscar Padre</a>';
                                                padre += '<a class="btn btn-danger" id="btnCambiarPadre" onclick="cambiarPadre()" role="button" style="margin-left: 5px;">Cambiar Padre</a>';
                                                padre += '<a class="btn btn-danger" id="btnCancelar" onclick="buscarTercero()" role="button" style="margin-left: 5px;">Cancelar</a></td>';
                                                padre += '</tr>';
                                                $(".padre").html(padre);
                                                $("#pdr_identificacion").prop("readOnly", true);
                                                $("#padreid").val(data.id);
                                                $("#btnBuscarPadre").hide();
                                                $("#btnCambiarPadre").show();
                                            } else {
                                                $("#btnCambiarPadre").hide();
                                                alert(data.error);
                                            }
                                        }
                                    });
                                }

                                function cambiarPadre() {
                                    var msn = "";
                                    var tercero_id = $("#terceroid").val();
                                    var padre_id = $("#padreid").val();
                                    var _token = $("#_token").val();

                                    $("#btnCambiarPadre").hide();
                                    $("#btnCancelar").hide();
                                    $.ajax({url: '{{route("admin.terceros.setpadre")}}',
                                        dataType: "json", type: "POST",
                                        data: {tercero_id: tercero_id, padre_id: padre_id, _token: _token},
                                        success: function (response) {
                                            if (response == true) {
                                                $("#msn_padre").css("color", "green");
                                                $("#msn_padre").css("font-size", "22px");
                                                $("#msn_padre").text('Se han hecho los cambios correctamente');
                                                setTimeout(function () {
                                                    $("#msn_padre").text("");
                                                    buscarTercero();
                                                }, 2000);
                                            } else {
                                                var msn = response;
                                                $("#msn_padre").css("color", "red");
                                                $("#msn_padre").css("font-size", "15px");
                                                $("#msn_padre").text(msn);
                                                $("#btnCambiarPadre").show();
                                                $("#btnCancelar").show();
                                            }
                                        }
                                    });
                                }

</script>
@endsection

