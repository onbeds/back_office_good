@extends('templates.dash')

@section('titulo','Envio confirmado')

@section('content')
<div class="container">
   <div class="row">
       <div class="col col-md-6 col-md-offset-3"   >
           <div class="panel panel-default">
             <div class="panel-heading"><h3 class="panel-title">¡Atención!</h3></div>
             <div class="panel-body">
                 <h4 class="success">Tu mensaje ha sido enviado.</h4>
             </div>
             <div class="panel-footer">
                 <a href="{{ route('admin.send.mail') }}" class="btn btn-danger btn-xs">Volver</a>
               </div>
           </div>
        </div>
   </div>
</div>
@endsection