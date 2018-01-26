@extends('templates.dash')

@section('titulo','Buscar Referido')

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
                            <h3 class="text-center">Buscar Referidos</h3>
                            <hr>
                            <input type="hidden" id="_token" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" id="id" name="id" value="{{currentUser()->id}}">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6">
                                    <label for="email">Ingresar correo o el nombre de la persona</label>
                                    <input id="tercero" name="email" type="text" class="form-control" required>
                                </div>
                            </div><br><br><br><br><br><br>

                         <table id="referidos" class="table table-bordered font-12">
                            <thead>
                            <tr>
                                <th>Nombres</th>
                                <th>Email</th>
                                <th>Nivel</th>
                            </tr>
                            </thead>
                            <tbody class="tercero">
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
    var html = '';
    $("#tercero").autocomplete({
    source: function(request, response) {
    $.ajax({ url: '{{route("admin.finder")}}', 
        dataType: "json",  type:"POST",  
        data: { email : $("#tercero").val(), id : $("#id").val(),  _token : $("#_token").val()
        },
        success: function(data) {  $(".tercero").html('');  html = '';  response(data);  }     
    });
    },   
    minLength: 1,   
    select: function( event, ui ) {  
    if(ui.item.nivel > 0){
        html += '<tr>';
        html += '<td class="text-left">'+ui.item.nombre+'</td>';
        html += '<td class="text-left">'+ui.item.correo+'</td>';
        html += '<td class="text-left">'+ui.item.nivel+'</td>';  
        html += '</tr>';
    }
    else{
        html += '<tr>';
        html += '<td style="text-align:center" colspan="3">No está en su lista de referidos</td>';
        html += '</tr>';
    }
        $(".tercero").html(html); 
    }
    });   
</script>

@endsection

