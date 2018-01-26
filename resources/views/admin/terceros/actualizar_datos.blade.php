@extends('templates.dash')

@section('titulo','Editar Tercero')

@section('content')

<style type="text/css">
.btn-file {
    position: relative; 
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: black;
    cursor: inherit;
    display: block;
    color: white;
}  

input[type="file"] {display: none;}

.boton-upload { font-family: 'Montserrat',sans-serif; font-size: 12px; outline: none !important; box-shadow: inset 0 1px 0 rgba(255,255,255,0.3); -webkit-box-shadow: inset 0 1px rgba(255,255,255,0.3); display: inline-block; padding: 10px; cursor: pointer;background-color: #949494; color: white;  border-radius: 10px; }

.boton-descargar { font-family: 'Montserrat',sans-serif; font-size: 12px; outline: none !important; box-shadow: inset 0 1px 0 rgba(255,255,255,0.3); -webkit-box-shadow: inset 0 1px rgba(255,255,255,0.3); display: inline-block; padding: 10px; cursor: pointer;background-color: #949494; color: white; border-radius: 10px;  }

.boton-upload:hover{ transition: .5s ease-in; background-color: gray; color: white;  }

.btn-primary{
    border: none;
    padding: 20px;
    text-transform: uppercase;
    border-radius: 30px;
}

.title-actualizar{
    padding: 30px;
    background-color: orange;
    color: white;
    text-transform: uppercase;
    font-weight: bold;
}

</style>
 
    <link rel="stylesheet" href="http://cdn.jtsage.com/jtsage-datebox/4.2.3/jtsage-datebox-4.2.3.bootstrap.min.css" />
    <link rel="stylesheet" href="http://dev.jtsage.com/DateBox/css/syntax.css" />

    <div class="panel panel-default">
        <div class="panel-body">
          <!--  <form action="{{route('terceros.actualizar_mis_datos')}}" method="post" class="form-horizontal" enctype="multipart/form-data"> -->
                {!! Form::open(['route' => 'terceros.actualizar_mis_datos', 'method' => 'POST', 'class' => 'form-access submit', 'enctype' => 'multipart/form-data']) !!}
                <div class="row">
                    <div class="col-xs-12">
                         <h3 class="text-center title-actualizar  col-xs-12">Actualizar mis datos</h3><hr>
                    </div>
                    <div class="col-sm-3">
                            <div class="row">
                            @if (currentUser()->avatar !== NULL)
                            <output id="list"><img src="{{ asset(currentUser()->avatar) }}" class="hidden-sm" alt="{{ currentUser()->nombre_completo }}" width="70%" /></output>  
                            @else
                            <output id="list"><img src="{{ asset("img/avatar-bg.png") }}" class="hidden-sm" alt="{{ currentUser()->nombre_completo }}" width="70%" /></output>  
                            @endif
                             </div> <br>  
                                        <label for="foto" class="boton-upload" style="width: 200px;"> 
                                            <input type="file" id="foto" name="foto"> 
                                            Cambiar foto
                                        </label>
                             <hr>
                            <div class="row">
                            @if ($tercero['tipo_cliente_id'] == 83)
                            <!--  <a href="https://cdn.shopify.com/s/files/1/2256/3751/files/Terminos_y_condiciones_Good_prime.docx?3575541982139248614">
                                          <input class="btn btn-default" style="background: #3783F9; color: white" type="button" id="d" name="d" value="Descargar contrato">
                            </a>  -->
                            @endif
                            
                            </div>                        
                    </div>

                    <div class="col-sm-9">
                            
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" id="_token" name="_token" value="{{csrf_token()}}">

                            <div class="row">
                                  <label  class="col-sm-1">Nombres </label> 
                                  <div class="col-sm-5"> <input type="text" name="first-name" placeholder="Nombres" class="f1-first-name form-control campo input-error" id="first-name" required="" value="{{$tercero['nombres']}}"> </div>
                                  <label  class="col-sm-1">Apellidos </label> 
                                  <div class="col-sm-5"> <input type="text" name="last-name" placeholder="Apellidos" class="f1-first-name form-control campo" id="last-name" required=""value="{{$tercero['apellidos']}}"> </div>
                            </div> <br>
                            <div class="row">
                                  <label  class="col-sm-1">Teléfono </label> 
                                  <div class="col-sm-5"> <input type="text" name="phone" placeholder="Teléfono" class="f1-first-name form-control campo input-error" id="phone" required=""value="{{$tercero['telefono']}}"> </div>
                                  <label  class="col-sm-1">Dirección </label> 
                                  <div class="col-sm-5"> <input type="text" name="address" placeholder="Dirección" class="f1-first-name form-control campo" id="address" required=""value="{{$tercero['direccion']}}"> </div>
                            </div> <br>
                            <div class="row">
                                  <label  class="col-sm-1">Email </label> 
                                  <div class="col-sm-5"> <input type="text" name="email" placeholder="Email" class="f1-first-name form-control campo input-error" id="email" required=""value="{{$tercero['email']}}">
                                    <input type="hidden" name="email_old" id="email_old" value="{{$tercero['email']}}"> </div>
                                  <label  class="col-sm-1">Fecha de nacimiento </label> 
                                  <div class="col-sm-5"> <span class="fech"><input type="text" id="birthday" style="background-color: white; border-top-left-radius: 20px; border-bottom-left-radius: 20px;" name="birthday"  placeholder="Fecha de nacimiento..." class="f1-last-name form-control input-group-addon" data-role="datebox" data-options='{"mode":"datebox", "overrideDateFormat": "%d/%m/%Y", "useFocus": true }' readonly="readonly"
                                   value="{{ $fecha_nacimiento }}"/></span>
                                  </div>
                            </div> <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-check">
                                       <label class="form-check-label">
                                        @if (date("Y-m-d") >= $fecha_inicio  && date("Y-m-d") <= $fecha_final )  
                                          <input type="checkbox"  checked disabled  class="form-check-input campo" checked /> 
                                        @else
                                          <input type="checkbox" name="prime"  class="form-check-input"> 
                                        @endif
                                            Adquirir la Suscripción Prime  <a href="https://cdn.shopify.com/s/files/1/2256/3751/files/3._TERMINOS_Y_CONDICIONES_ESPECIALES_PARA_USUARIOS_CON_SUSCRIPCION_PRIME.pdf?9422701949243987383" target="_blank">Terminos y condiciones</a>
                                        </label>
                                    </div>
                                </div>  
                                <div class="col-sm-6">
                                    <div class="form-check">
                                       <label class="form-check-label">
                                          <input class="form-check-input campo"  @if ($tercero['contrato'] == true)  checked disabled  @endif   type="checkbox" />
                                          Contrato <a href="{{route('terms')}}" target="_blank">Terminos</a>
                                        </label>
                                    </div>
                                </div>    
                            </div> <br>

                             <h3 class="text-center">Documentos</h3>
                             <hr>
<br>
                        <div class="form-group">
                            <label for="bank">Seleccionar Entidad Bancaria</label>
                            <select id="banco_id" name="banco_id" class="form-control campo" style="width: 100% !important;">
                                <option value=""></option>
                                @foreach($bancos as $tipo)
                                  {{--*/  $selected = '' /*--}}   
                                      @if ($tipo->id == $tercero['banco_id'])  
                                               {{--*/  $selected = 'selected' /*--}}   
                                      @endif 
                                    <option value="{{$tipo->id}}" {{$selected}}>{{ucwords($tipo->nombre)}}</option>
                                @endforeach
                            </select> 
                        </div>

                        <div class="form-group">
                            <label for="type_acount_bank">Tipo de cuenta</label>
                            <select id="tipocuenta_id" name="tipocuenta_id" class="form-control campo" style="width: 100% !important;">
                                <option value=""></option>
                                @foreach($cuentas->tipos as $tipo)
                                  {{--*/  $selected = '' /*--}}   
                                      @if ($tipo->id == $tercero['tipocuenta_id'])  
                                               {{--*/  $selected = 'selected' /*--}}   
                                      @endif 
                                    <option value="{{$tipo->id}}" {{$selected}}>{{ucwords($tipo->nombre)}}</option>
                                @endforeach
                            </select> 
                        </div>

                        <div class="form-group">
                            <label for="acount">Número de cuenta</label>
                            <input type="text" name="numero_cuenta" value="{{$tercero['numero_cuenta']}}" placeholder="Documento..." class="f1-first-name form-control campo" id="numero_cuenta">
                        </div>
<br>
                             <div class="row">
                                <div class="col-xs-12 col-sm-4" style="border-right: 1px solid #C3C3C3">
                                    <label class="col-sm-12 text-center">RUT <span style="color: #F00707"> Recuerde que su RUT debe tener código de actividad 8299 </span> </label>
                                    <div class="col-xs-12">
                                      @if ($tercero['rut'] != '')  
                                        <a href="{{route('admin.terceros.descargar_documentos', ['id' => currentUser()->id, 'tipo' => 'rut'])}}"> 
                                          <input class="btn btn-default boton-descargar" style="background: #3783F9; color: white" type="button" id="d" name="d" value="Descargar"> 
                                        </a>
                                      @endif 
                                         <label for="rut" class="boton-upload">
                                            <input type="file" id="rut" name="rut">
                                      @if ($tercero['rut'] != '')  
                                        Cambiar 
                                      @endif 
                                      @if ($tercero['rut'] == '')  
                                        Agregar 
                                      @endif  
                                        </label> <br>
                                        <p id="texto" class="col-xs-12" style="color: gray; font-style: italic; font-size: .8em;"></p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <label class="col-xs-12 col-sm-12 text-center"> Cédula o Documento </label>
                                    <div class="col-xs-10 col-xs-offset-1">
                                      @if ($tercero['cedula'] != '')  
                                        <a href="{{route('admin.terceros.descargar_documentos', ['id' => currentUser()->id, 'tipo' => 'cedula'])}}">
                                          <input class="btn btn-default boton-descargar" style="background: #3783F9; color: white" type="button" id="d" name="d" value="Descargar"> 
                                        </a>
                                      @endif 
                                        <label for="cedula" class="boton-upload"> 
                                            <input type="file" id="cedula" name="cedula">
                                      @if ($tercero['cedula'] != '')  
                                        Cambiar 
                                      @endif 
                                      @if ($tercero['cedula'] == '')  
                                        Agregar 
                                      @endif 
                                        </label>  <br>
                                         <p id="texto-cedula" class="col-xs-12" style="color: gray; font-style: italic; font-size: .8em;"></p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4" style="border-left: 1px solid #C3C3C3">
                                    <label class="col-xs-12 col-sm-12 text-center"> Certificación bancaria (No obligatorio para registro)  </label>
                                    <div class=" col-xs-10 col-xs-offset-1">
                                      @if ($tercero['cuenta'] != '')   
                                       <a href="{{route('admin.terceros.descargar_documentos', ['id' => currentUser()->id, 'tipo' => 'cuenta'])}}">
                                          <input class="btn btn-default boton-descargar" style="background: #3783F9; color: white" type="button" id="d" name="d" value="Descargar"> 
                                        </a>
                                      @endif 
                                         <label for="banco" class="boton-upload">
                                            <input type="file" id="banco" name="banco">
                                      @if ($tercero['cuenta'] != '')  
                                        Cambiar 
                                      @endif 
                                      @if ($tercero['cuenta'] == '')  
                                        Agregar 
                                      @endif 
                                        </label><br>
                                         <p id="texto-certificacion" class="col-xs-12" style="color: gray; font-style: italic; font-size: .8em;"></p> 
                                    </div>
                                </div>
                             </div>

                             <input type="hidden" name="rut_old" value="{{$tercero['rut']}}"> 
                             <input type="hidden" name="cedula_old" value="{{$tercero['cedula']}}"> 
                             <input type="hidden" name="cuenta_old" value="{{$tercero['cuenta']}}"> 
                             <input type="hidden" name="foto_old" value="{{currentUser()->avatar}}"> 
                           

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
                    </div>
                </div><br><br>
                 <div class="row"> 
                    <div class="col-sm-12">
                        <button class="btn btn-primary" type="submit" data-toggle="modal" data-target="#myModal" id="enviar">Actualizar mis datos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script src="http://backoffice.tiendagood.com/assets/js/jquery-1.11.1.min.js"></script> 
<script src="http://backoffice.tiendagood.com/assets/js/jquery.backstretch.min.js"></script>
<script src="http://backoffice.tiendagood.com/assets/js/retina-1.1.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> 
<script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://cdn.jtsage.com/external/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="http://dev.jtsage.com/DateBox/js/doc.js"></script>
<script type="text/javascript" src="http://cdn.jtsage.com/jtsage-datebox/4.2.3/jtsage-datebox-4.2.3.bootstrap.min.js"></script>
<script type="text/javascript" src="http://cdn.jtsage.com/jtsage-datebox/i18n/jtsage-datebox.lang.utf8.js"></script>

<script type="text/javascript">
 @if(Session::has('flash_msg'))
                  swal({ 
                  html: $('<div>').text('{{Session::get('flash_msg')}}'),
                  animation: false,
                  customClass: 'animated tada'
                  });
@endif   

    var exp_number = /^[a-zA-Z0-9]{6,15}$/;
    var exp_acount = /^[0-9\-]{7,}$/;
    var exp_names =/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/;
    var exp_date = /^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/;
    var exp_address = /^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ#.\-_\s]+$/;
    var exp_phone = /^[0-9-()+]{3,20}$/;
    var exp_email = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.]+$/; 


 function archivo(evt) { var files = evt.target.files; for (var i = 0, f; f = files[i]; i++) { 
  if (!f.type.match('image.*') && f.size > '7000000') { 
                swal({ 
                  html: $('<div>').text('Este archivo no puede pesar mas de 7 mb y solo se admite formato jpg.'),
                  animation: false,
                  customClass: 'animated tada'
                  });   
  }else{ var reader = new FileReader(); reader.onload = (function(theFile) { return function(e) { document.getElementById("list").innerHTML = ['<img  width="70%"  class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join(''); }; })(f); reader.readAsDataURL(f); }  }
  } 
 document.getElementById('foto').addEventListener('change', archivo, false);

/* Funcion rut span*/

 function rut(evt) { var files = evt.target.files; for (var i = 0, f; f = files[i]; i++) {  
   if(f.size <= '7000000'){
    if (f.type.match('image.*') || f.type.match('application/pdf')) {
        var reader = new FileReader(); 
        reader.onload = (function(theFile) { 
        return function(e) { 
            document.getElementById("texto").innerHTML = [ escape(theFile.name) ].join(''); 
        }; 
        })(f); 
        reader.readAsDataURL(f); 
      }
    }
    else{
                swal({ 
                  html: $('<div>').text('Este archivo no puede pesar mas de 7 mb y solo se admite formato pdf y jpg.'),
                  animation: false,
                  customClass: 'animated tada'
                  });   
    }
  }
} 

document.getElementById('rut').addEventListener('change', rut, false);

function doc(evt) { var files = evt.target.files; for (var i = 0, f; f = files[i]; i++) { 
   if(f.size <= '7000000'){
    if (f.type.match('image.*') || f.type.match('application/pdf')) {
    var reader = new FileReader(); 
    reader.onload = (function(theFile) { 
        return function(e) { 
            document.getElementById("texto-cedula").innerHTML = [ escape(theFile.name) ].join(''); 
        }; 
    })(f); 
    reader.readAsDataURL(f); 
    } 
    }
    else{
                swal({ 
                  html: $('<div>').text('Este archivo no puede pesar mas de 7 mb y solo se admite formato pdf y jpg.'),
                  animation: false,
                  customClass: 'animated tada'
                  });   
    }
  }
} 

document.getElementById('cedula').addEventListener('change', doc, false);
 
function cer(evt) { var files = evt.target.files; for (var i = 0, f; f = files[i]; i++) { 
   if(f.size <= '7000000'){
    if (f.type.match('image.*') || f.type.match('application/pdf')) {
    var reader = new FileReader(); 
    reader.onload = (function(theFile) { 
        return function(e) { 
            document.getElementById("texto-certificacion").innerHTML = [ escape(theFile.name) ].join(''); 
        }; 
    })(f); 
    reader.readAsDataURL(f); 
    }
    } 
    else{
                swal({ 
                  html: $('<div>').text('Este archivo no puede pesar mas de 7 mb y solo se admite formato pdf y jpg.'),
                  animation: false,
                  customClass: 'animated tada'
                  });   
    }
  }
} 

document.getElementById('banco').addEventListener('change', cer, false);


$('.submit').on('submit',function(event){
 

            var first_name = $('#first-name').val();
            var last_name = $('#last-name').val();
            var phone = $('#phone').val();
            var address = $('#address').val();
            var address = $('#address').val();

            if (first_name.length < 2) {
                swal({ 
                  html: $('<div>').text('El campo nombres es demasiado corto (usa mínimo 4 caracteres).'),
                  animation: false,
                  customClass: 'animated tada'
                  });    
                event.preventDefault() ;
            }
           else if (last_name.length < 2 ) {
                swal({ 
                  html: $('<div>').text('El campo apellidos es demasiado corto (usa mínimo 4 caracteres).'),
                  animation: false,
                  customClass: 'animated tada'
                  });    
                event.preventDefault() ;
            } 

            else if (phone.length != 10 ) {
                swal({ 
                  html: $('<div>').text('El Teléfono demasiado corto (se usa 10 dígitos).'),
                  animation: false,
                  customClass: 'animated tada'
                  });    
                event.preventDefault() ;
            } 

            else if (!exp_address.test(address)) {
                swal({ 
                  html: $('<div>').text('La dirección no puede estar en blanco y no se puede usar caracteres especiales.'),
                  animation: false,
                  customClass: 'animated tada'
                  });    
                event.preventDefault() ;
            } 

            else if (!exp_address.test(address)) {
                swal({ 
                  html: $('<div>').text('La dirección no puede estar en blanco y no se puede usar caracteres especiales.'),
                  animation: false,
                  customClass: 'animated tada'
                  });    
                event.preventDefault() ;
            } 


    if($('#email').val() != $('#email_old').val()){
        var email = $('#email').val();
        if (exp_email.test(email) && email.length > 0) {
                result_email = JSON.parse( $.ajax({
                    url: '../validate/email',
                    type: 'post',
                    data: {email: email, _token: $('#_token').val() },
                    dataType: 'json',
                    async:false
                }).responseText);

                if (result_email.msg == 'email valido') { 
                    return true;
                }
                else if(result_email.err == 'email existe') { 
                  swal({ 
                  html: $('<div>').text('El email que ingresó existe, ingrese otro por favor.'),
                  animation: false,
                  customClass: 'animated tada'
                  });                    
                    event.preventDefault() ;
                } 
        }
        else{
                swal({ 
                  html: $('<div>').text('Ingresa un correo electrónico válido.'),
                  animation: false,
                  customClass: 'animated tada'
                  });                     
                    event.preventDefault() ;
        }
    }
 
// event.preventDefault() ;
        //$('#enviar').prop('disabled', true);        
});    

</script>

@endsection




