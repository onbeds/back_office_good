@extends('templates.dash')

@section('titulo','Good')

@section('content')

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="text-center title-actualizar  col-xs-12">Generar liquidación</h3><hr>
                </div>
            </div><br>
            {!! Form::open(['route' => 'liquidacion.liquidar.envio', 'method' => 'POST', 'class' => 'form-access submit']) !!} 
                    <input type="hidden" name="liquidar" value="1">
            <div class="row"> 
                <div class="col-sm-6"><input type="text" class="form-control" name="fecha_inicio" id="datepicker_1" readonly  placeholder="Fecha Inicio"></div>
                <div class="col-sm-6"><input type="text" class="form-control" name="fecha_final"  id="datepicker_2" readonly  placeholder="Fecha Final"> </div>                
            </div><br> 
            <div class="row"> 
                <div class="col-sm-12">
                    <button class="btn btn-warning" type="submit" data-toggle="modal" data-target="#myModal" id="enviar">Generar</button>
                </div>
            </div><br> 
            </form>
        </div>
    </div>
<div id="divLoading" style="display: none; margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(44, 104, 140); z-index: 30001; opacity: 0.8;"> <p style="position: absolute; color: White; top: 50%; left: 35%; font-size: 30px;"> Generando liquidación, espere por favor...  <img src="http://loadinggif.com/images/image-selection/3.gif" width="20px">  </p>  </div>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">

        $('.submit').on('submit',function(event){

            $('#enviar').prop('disabled', true);

            if($('#datepicker_1').val() == ''){   
                swal("Vuelva a intentarlo", "El campo fecha de inicio no puede ir vacio.", "error");
                event.preventDefault(); 
                $('#enviar').prop('disabled', false);  
            } else if($('#datepicker_2').val() == ''){
                swal("Vuelva a intentarlo", "El campo fecha de final no puede ir vacio.", "error");  
                event.preventDefault();  
                $('#enviar').prop('disabled', false);  
            }  else if($('#datepicker_1').val() > $('#datepicker_2').val()){
                swal("Vuelva a intentarlo", "La fecha de inicio no puede ser mayor que la fecha final.", "error");     
                event.preventDefault();  
                $('#enviar').prop('disabled', false);  
            } else if('{{$fecha_final}}' >= $('#datepicker_1').val()){
                swal("Vuelva a intentarlo", "El rango de fecha que puso ya se liquido.", "error");     
                event.preventDefault();  
                $('#enviar').prop('disabled', false);  
            }else{   
            $("#g").prop("disabled",true); 
            $("div#divLoading").show();
            }

        });

        $( "#datepicker_1, #datepicker_2" ).datepicker({ dateFormat: 'yy-mm-dd' });

        @if(Session::has('flash_msg'))




        @endif

    </script>

    </section>

@endsection