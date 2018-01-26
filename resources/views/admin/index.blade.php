@extends('templates.dash')

@section('titulo','Good')

@section('content')


        {!! Html::style('css/material-dashboard.css?act=4') !!}

         <link href="https://fonts.googleapis.com/css?family=Bungee|Roboto+Slab:100,300,400,700" rel="stylesheet">



            <section class="invoice">
                <div class="col-lg-12">
    

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <img src="https://cdn.shopify.com/s/files/1/2256/3751/files/logo-good2.png?4867312163605074192" alt="" style="width: 300px; padding: 20px 0">
    </div>
</div>

<div class="col-lg-4 col-md-6 col-sm-6">
   <div class="card card-stats">
      <div class="card-header" data-background-color="green">
         <i class="fa fa-money" aria-hidden="true"></i>
      </div>
      <div class="card-content">
         <p class="category">Estos son tus puntos</p>
         <h3 class="title">{{ number_format($my_points) }}
            <small>Pts</small>
         </h3>
      </div>
      <div class="card-footer" style="display: block;">
         <div class="stats">
            <i class="material-icons text-danger"></i>
            <a href="#pablo"></a>
         </div>
      </div>
   </div>
</div>
<div class="col-lg-4 col-md-6 col-sm-6">
   <div class="card card-stats">
      <div class="card-header" data-background-color="blue">
         <i class="fa fa-money" aria-hidden="true"></i>
      </div>
      <div class="card-content">
         <p class="category">Estos son tus puntos Vendidos</p>
         <h3 class="title">{{ number_format($points_level_1 + $points_level_2 + $points_level_3) }}
            <small>Pts</small>
         </h3>
      </div>
      <div class="card-footer" style="display: block;">
         <div class="stats">
            <i class="material-icons text-danger"></i>
            <a href="#pablo"></a>
         </div>
      </div>
   </div>
</div>
<div class="col-lg-4 col-md-6 col-sm-6">
   <div class="card card-stats">
      <div class="card-content">
         <p class="category">Tu patrocinador es</p> <!--<h1 class="hidden-xs" style="text-transform: uppercase; color: gray; padding: 20px; font-size: 1.8em;">  <br>-->
         <h3 class="title">{{ ucwords($nombre_completo) }}</h3>
         <p class="category">{{$email}} - {{$telefono}}</p>
      </div>
      <div class="card-footer" style="display: block;">
         <div class="stats">
            <i class="material-icons text-danger"></i>
            
         </div>
      </div>
   </div>
</div>

 <div class="col-sm-12">
<div class="card card-stats" style="margin: 5px 0;">
      <div class="row">
         <div class="col-sm-3">
            <div class="card-content" style="text-align: left;">
            <div style="padding-top: 16px;"><span class="category"> Tipo de cliente:  </span><br>  <span class="title" style="padding-top: 11px; font-size: 20px">{{ucwords($tipo_nombre)}}</span></div>
            </div>
         </div>
         <div class="col-sm-6">
             <div class="card-content" style="text-align: center;">
               @if (date("Y-m-d") >= $fecha_inicio  && date("Y-m-d") <= $fecha_final)                   
               <h3 class="title" style="padding-top: 11px;">Plan prime activado</h3>
               @else
                 <span class="plan_prime_texto"> <button class="btn btn-primary" data-background-color="orange" type="button"  id="actualizar_plan_prime" onclick="plan()">Activar plan prime</button> </span>
               @endif  <br><br>
              <!-- <span style="font-weight: bold; font-size: 20px">Estamos preparando tu liquidación </span> -->
          </div>
         </div>
         <div class="col-sm-3">
             <div class="card-content" style="text-align: center; margin-top: 20px; text-align: right;">
              <span class="category">Fecha del proximo corte:</span><br> <span style="font-family: 'Bungee', cursive; color: black;font-size: 16px;"> 15 de Febrero 2018</span> 
          </div>
         </div>
      </div>
   </div>
</div>


<div class="col-lg-12 col-md-12">
   <div class="card">
      <div class="card-content table-responsive">
         <table class="table table-hover">
            <thead class="text-warning">
            </thead>
            <tbody>
               <tr onclick="link(1)">
                  <td>
                     <div class="card-header" data-background-color="orange" style="width: 80px; height: 80px; margin-top: 10px; border-radius: 50%; position: relative;">
                        <i class="fa" style="font-size: 25px; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); font-family: 'Roboto Slab', serif; font-weight: 800;">1</i>
                     </div>
                  </td>

                  <td>#{{$uno}} <span class="fa fa-user" aria-hidden="true"></span></td> 
                  <td>{{ number_format($points_level_1) }}  Pts</td> 
                  <td style="transform: translateY(10px)">$ {{ number_format($saldo_uno) }}
                  </td>
                  </a>
               </tr>
               <tr onclick="link(2)">
                  <td>
                     <div class="card-header" data-background-color="orange" style="width: 80px; height: 80px; margin-top: 10px; border-radius: 50%; position: relative;">
                        <i class="fa" style="font-size: 25px; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); font-family: 'Roboto Slab', serif; font-weight: 800;">2</i>
                     </div>
                  </td>
                  <td>#{{$dos}} <span class="fa fa-user" aria-hidden="true"></span></td>
                  <td>{{ number_format($points_level_2) }}  Pts</td>
                  <td style="transform: translateY(10px)">$ {{ number_format($saldo_dos) }}
                  </td>
               </tr>
               <tr onclick="link(3)">
                  <td>
                     <div class="card-header" data-background-color="orange" style="width: 80px; height: 80px; margin-top: 10px; border-radius: 50%; position: relative;">
                        <i class="fa" style="font-size: 25px; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); font-family: 'Roboto Slab', serif; font-weight: 800;">3</i>
                     </div>
                  </td>
                  <td>#{{$tres}} <span class="fa fa-user" aria-hidden="true"></span></td>
                  <td>{{ number_format($points_level_3) }}  Pts</td>
                  <td style="transform: translateY(10px)">$ {{ number_format($saldo_tres) }}
                  </td>
               </tr>

            </tbody>
         </table><br>

         <span style="font-size: 15px; display: block; color: #fd950c; font-weight: bold" >¡Los valores corresponden a las compras totales de tus referidos, pueden variar en la liquidación!</span>
         <br><br><br>

      </div>
   </div>
</div>
<script type="text/javascript"> function link(nivel){ location.href = "/nivel/"+nivel; } 
function plan(){
swal({
  title: '¿Deseas activar prime?',
  html:
    'Bonificación 10% adicional mensual. <br>' +
    'Canal preferencial de servicio al cliente. <br>' +
    'Garantía extendida de productos (6 meses) y más. <br> <br>' +
    '<span style="font-size: 14px">Al dar click en “Aceptar”, confirmas estos <a href="{{route('terms_prime')}}" target="_blank" style="color: #ed7d01;"> términos y condiciones.</a></span> <br>',
  type: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  cancelButtonText: 'No',
  confirmButtonText: 'Aceptar'
}).then((result) => {
  if (result.value) {

               $.ajax({
                    url: '../terceros/activarplanprime',
                    type: 'post',
                    data: { cambio: '' },
                    dataType: 'json',
                    async:false
               });

    swal(
      'Felicitaciones tu plan prime ha sido activado.',
      ''
    );

   $('.plan_prime_texto').html('<h3 class="title" style="padding-top: 11px;">Plan prime activado</h3>');

  }
});
}
</script>

</section> 

@endsection

@section('scripts')

@endsection