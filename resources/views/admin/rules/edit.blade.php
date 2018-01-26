@@extends('templates.dash')

@section('titulo','Editar Regla')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <a href="#step-1"  class="btn btn-primary btn-circle">1</a>
                        <p>Paso 1</p>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.rules.update_detail') }}" method="post" class="form-horizontal">
                <div class="row setup-content" id="step-1">
                    <div class="col-xs-12">
                        <div class="col-md-12">
                            <h3 class="text-center">Editar Regla</h3>
                            <hr>
                            <input type="hidden" id="_token" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" id="id" name="id" value="{{$q['rule']['id']}}">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="red">Red</label>
                                    <input id="red" name="red" type="text" class="form-control" value="{{$q['rule']['rule']['network']['name']}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="tipo">Tipo</label>
                                    <input id="tipo" name="tipo" type="text" class="form-control" value="{{$q['rule']['rule']['tipo']['nombre']}}"  disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="nivel">Nivel</label>
                                    <input id="nivel" min="0" max="3" name="nivel" type="number" class="form-control" value="{{$q['rule']['nivel']}}"  required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="comision_puntos">Comision por pesos</label>
                                    <input id="comision_puntos" name="comision_puntos" type="number" class="form-control" value="{{$q['rule']['comision_puntos']}}"  required>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <button class="btn btn-primary" type="submit" >Editar</button>
                                </div>
                            </div>
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
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $("#red").select2({
                placeholder: 'Escoger red...',
                allowClear: true,
            });






            $("#tipo").select2({
                placeholder: 'Escoger tipo...',
                allowClear: true,
            });

        });

    </script>
@endpush





