<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Good</title>

    <link rel="icon" href="https://cdn.shopify.com/s/files/1/2256/3751/files/favicon-96x96.png?14986824105996215938" type="image/x-icon">
    <!-- CSS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.0/sweetalert2.min.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.min.css" />
    <!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <!--Font Awesome (added because you use icons in your prepend/append)-->
    <link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />

    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css?act=2">

    <link rel="stylesheet" href="http://cdn.jtsage.com/jtsage-datebox/4.2.3/jtsage-datebox-4.2.3.bootstrap.min.css" />
    <link rel="stylesheet" href="http://dev.jtsage.com/DateBox/css/syntax.css" />

    <!-- Inline CSS based on choices in "Settings" tab -->
    <style>.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form{font-family: Arial, Helvetica, sans-serif; color: black}.bootstrap-iso form button, .bootstrap-iso form button:hover{color: white !important;} .asteriskField{color: red;}</style>

</head>

<body>


<!-- Top content -->
<div class="top-content">
    <div class="container">

        @if ($errors->any())

            <div class="alert alert-danger fade in col-sm-offset-3 col-sm-6">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif


        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 form-box">


                <form enctype="multipart/form-data" role="form" action="/register" method="post" class="f1" >

                    <input type="hidden" id="_token" name="_token" value="{{csrf_token()}}">

                    <img src="assets/img/logo-good.png" alt="" style="width: 50%; margin: 20px auto; display: block">
                    <h3>Registrate en Good</h3>

                    <p style="border-bottom: 2px solid #80808014; padding-bottom: 30px;">En tres simples pasos pertenecerás a nuestro exclusivo club.</p>
                    <div class="f1-steps">


                        <div id="cero" class="f1-step active">
                            <div class="f1-step-icon"><i class="fa fa-user"></i></div>
                            <p>Tus datos</p>
                        </div>

                        <div id="one" class="f1-step">
                            <div class="f1-step-icon"><i class="fa fa-key"></i></div>
                            <p>Tu usuario</p>
                        </div>

                        <div id="two" class="f1-step">
                            <div class="f1-step-icon"><i class="fa fa-check-square-o"></i></div>
                            <p>Tus documentos</p>
                        </div>
                    </div>

                    <fieldset id="tree">

                        <div class="col-xs-12 col-md-12" style="text-align: center">
                            <h4 class="dinos">Dinos quién eres:</h4>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="type_client" class="sr-only">Tipo Cliente</label>
                                <select id="type_client" name="type_client" class="form-control campo" required>
                                    <option value=""></option>
                                    @foreach($tipos->tipos as $tipo)
                                        <option value="{{$tipo->id}}">{{ucwords($tipo->nombre)}}</option>
                                    @endforeach
                                </select>
                                <p class="alert-message text-center">Selecciona que tipo de cliente quieres ser.</p>
                            </div>
                            <div class="form-group">
                                <label for="type_dni" class="sr-only">Tipo Documento</label>
                                <select id="type_dni" name="type_dni" class="form-control campo" required>
                                    <option value=""></option>
                                    @foreach($documentos->tipos as $tipo)
                                        <option value="{{$tipo->id}}">{{ucwords($tipo->nombre)}}</option>
                                    @endforeach
                                </select>
                                <p class="alert-message">Escoge una opción.</p>
                            </div>

                            <div class="form-group">
                                <label for="dni" class="sr-only">Número Documento</label>
                                <input type="text" name="dni" placeholder="Documento..." class="f1-first-name form-control campo" id="dni" required>
                                <p class="alert-message"><span class="dni_val"></span>Es demasiado corto (usa mínimo 6 caracteres).</p>
                            </div>

                            <div class="form-group">
                                <label for="f1-first-name" class="sr-only">Nombres</label>
                                <input type="text" name="first-name" placeholder="Nombres..." class="f1-first-name form-control campo" id="first-name" required>
                                <p class="alert-message">Es demasiado corto (usa mínimo 4 caracteres).</p>
                            </div>

                            <div class="form-group">
                                <label for="f1-last-name" class="sr-only">Apellidos</label>
                                <input type="text" name="last-name" placeholder="Apellidos..." class="f1-last-name form-control campo" id="last-name" required>
                                <p class="alert-message">Es demasiado corto (usa mínimo 4 caracteres).</p>
                            </div>

                        </div>


                        <div class="col-md-6 col-xs-12">

                            <div class="form-group">
                                <label for="sex" class="sr-only">Sexo</label>
                                <select id="sex" name="sex" class="form-control campo" required>
                                    <option value=""></option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                </select>
                                <p class="alert-message">Escoge una opción.</p>
                            </div>

                            <div class="form-group">
                                <label for="city" class="sr-only">Ciudad</label>
                                <select id="city" name="city" class="form-control campo" required>
                                    <option value=""></option>
                                    @foreach($cities as $tipo)

                                        <option value="{{$tipo->id}}">{{ucwords($tipo->nombre)}}</option>
                                    @endforeach
                                </select>
                                <p class="alert-message">Selecciona la ciudad donde vives..</p>
                            </div>

                            <div class="form-group">
                                <label for="address" class="sr-only">Dirección</label>
                                <input type="text" id="address" name="address"  placeholder="Dirección..." class="f1-last-name form-control campo" required/>
                                <p class="alert-message">No puede estar en blanco y no se puede usar caracteres especiales.</p>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="sr-only">Célular</label>
                                <input type="text" id="phone" name="phone"  placeholder="Teléfono..." class="f1-last-name form-control campo" required/>
                                <p class="alert-message">Es demasiado corto o grande (se usa 10 dígitos).</p>
                            </div>


                            <div class="form-group">
                                <label for="birthday" class="sr-only">Fecha de Nacimiento</label>
                                <span class="fech"><input type="text" id="birthday" style="background-color: white; border-top-left-radius: 20px; border-bottom-left-radius: 20px;" name="birthday"  placeholder="Fecha de nacimiento..." class="f1-last-name form-control input-group-addon" data-role="datebox" data-options='{"mode":"datebox", "overrideDateFormat": "%d/%m/%Y", "useFocus": true }' readonly="readonly"/></span>
                                <p class="alert-message">¿Cuándo naciste?</p>
                            </div>

                            <div class="f1-buttons">
                                <button type="button" class="btn btn-next">Siguiente</button>
                            </div>
                        </div>


                    </fieldset>

                    <fieldset id="four">

                        <h4 class="dinos">Configurar tu usuario:</h4>

                        @if(isset($code) && isset($patrocinador))
                            <div class="form-group">
                                <label for="code">Patrocinador: {{ucwords($patrocinador->nombres) . ' ' . ucwords($patrocinador->apellidos)}}</label>
                                <input type="text" name="code" placeholder="Cédula de tu patrocinador..." class="f1-email form-control campo" id="code"  value="{{$code}}" readonly>
                                <p class="alert-message">Ingresa el código de su referido.</p>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="code" class="sr-only">Código de tu referido</label>
                                <input type="text" name="code" placeholder="Cédula de tu patrocinador..." class="f1-email form-control campo" id="code" required>
                                <p class="alert-message">El código de su referido no existe o no se puede hacer red con este código, verifiquelo por favor.</p>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="f1-email" class="sr-only">Email</label>
                            <input type="email" name="email" placeholder="Email..." class="f1-email form-control campo" id="email" required>
                            <p class="alert-message">Ingresa un correo electrónico válido.</p>
                        </div>
                        <div class="form-group">
                            <label for="f1-password" class="sr-only">Contraseña</label>
                            <input type="password" name="password" placeholder="Contraseña..." class="f1-password form-control campo" id="password" required>
                            <p class="alert-message">Escribe una contraseña (minimo 6 digitos).</p>
                        </div>
                        <div class="form-group">
                            <label  for="f1-repeat-password" class="sr-only">Repetir Contraseña</label>
                            <input type="password" name="password_confirmation" placeholder="Repetir Contraseña..."
                                   class="f1-repeat-password form-control campo" id="password_confirmation" required>
                            <p class="alert-message">Repite la contraseña. (minimo 6 digitos) y deben coincidir</p>
                        </div>

                        <div class="f1-buttons">
                            <button type="button" class="btn btn-previous">Anterior</button>
                            <button type="button" class="btn btn-next">Siguiente</button>
                            <button type="submit" class="btn btn-submit">Crear</button>
                        </div>


                    </fieldset>

                    <fieldset id="five">

                        <h4 class="dinos">Documentos y condiciones:</h4>

                        <div class="form-group">
                            <label for="bank">Seleccionar Entidad Bancaria (No obligatorio para registro)</label>
                            <select id="bank" name="bank" class="form-control campo" style="width: 100% !important;">
                                <option value=""></option>
                                @foreach($bancos as $tipo)

                                    <option value="{{$tipo->id}}">{{ucwords($tipo->nombre)}}</option>
                                @endforeach
                            </select>
                            <p class="alert-message">Selecciona una entidad bancaria.</p>
                        </div>

                        <div class="form-group">
                            <label for="type_acount_bank">Tipo de cuenta (No obligatorio para registro)</label>
                            <select id="type_acount_bank" name="type_acount_bank" class="form-control campo" style="width: 100% !important;">
                                <option value=""></option>
                                @foreach($cuentas->tipos as $tipo)
                                    <option value="{{$tipo->id}}">{{ucwords($tipo->nombre)}}</option>
                                @endforeach
                            </select>
                            <p class="alert-message">Selecciona un tipo de cuenta.</p>
                        </div>

                        <div class="form-group">
                            <label for="acount">Número de cuenta (No obligatorio para registro)</label>
                            <input type="text" name="acount" placeholder="Documento..." class="f1-first-name form-control campo" id="acount">
                            <p class="alert-message">Secelcciona un numero de cuenta.</p>
                        </div>

                        <div class="form-group">
                            <label class="custom-file">
                                Certificación bancaria (No obligatorio para registro)
                                <input type="file" id="banco" name="banco" class="custom-file-input campo">
                                <span class="custom-file-control"></span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="custom-file">
                                Cédula o Documento (No obligatorio para registro)
                                <input type="file" id="cedula" name="cedula" class="custom-file-input campo">
                                <span class="custom-file-control"></span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="custom-file">
                                RUT (No obligatorio para registro)
                                <input type="file" id="rut" name="rut" class="custom-file-input campo">
                                <span class="custom-file-control"></span>
                                <label style="color: #F00707"> Recuerde que su RUT debe tener código de actividad 8299 </label>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-check-label">
                                <input id="prime" name="prime" class="form-check-input campo" type="checkbox">
                                ¿Quieres ser  suscriptor  Prime?
                                <a target="_blank" href="https://cdn.shopify.com/s/files/1/2256/3751/files/3._TERMINOS_Y_CONDICIONES_ESPECIALES_PARA_USUARIOS_CON_SUSCRIPCION_PRIME.pdf?9422701949243987383">Términos</a>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="contrato" class="form-check-label">
                                <input class="form-check-input campo"  type="checkbox" id="contract" name="contract" required />
                                Contrato <a href="terms" target="_blank">terminos</a>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="condiciones" class="form-check-label">
                                <input class="form-check-input campo"  type="checkbox" id="terms" name="terms" required />
                                ¿Acepta <a href="terms" target="_blank">terminos</a> y condiciones?
                            </label>
                        </div>

                        <div class="f1-buttons">
                            <button type="button" class="btn btn-previous">Anterior</button>
                            <button type="submit" class="btn btn-submit">Crear</button>
                        </div>

                    </fieldset>

                </form>


            </div>
        </div>

    </div>
</div>


<!-- Javascript -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.backstretch.min.js"></script>
<script src="assets/js/retina-1.1.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.0/sweetalert2.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://cdn.jtsage.com/external/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="http://dev.jtsage.com/DateBox/js/doc.js"></script>
<script type="text/javascript" src="http://cdn.jtsage.com/jtsage-datebox/4.2.3/jtsage-datebox-4.2.3.bootstrap.min.js"></script>
<script type="text/javascript" src="http://cdn.jtsage.com/jtsage-datebox/i18n/jtsage-datebox.lang.utf8.js"></script>
<script src="assets/js/scripts.js?u={{random_int(1, 100)}}"></script>

<![endif]-->


</body>

</html>