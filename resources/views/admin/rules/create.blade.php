@@extends('templates.dash')

@section('titulo','Crear Regla')

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
            <form action="{{route('admin.rules.store')}}" method="post" class="form-horizontal">
                <div class="row setup-content" id="step-1">
                    <div class="col-xs-12">
                        <div class="col-md-12">
                            <h3 class="text-center">Crear Regla</h3>
                            <hr>
                            <input type="hidden" id="_token" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="red">Red</label>
                                    <select class="form-control" id="red" name="red" required>
                                        <option></option>
                                        @foreach($q['redes'] as $red)
                                            <option value="{{$red->id}}">{{$red->name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="tipo">Tipo</label>
                                    <select class="form-control" id="tipo" name="tipo" required>
                                        <option></option>
                                        @foreach($q['tipos'] as $tipo)
                                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="nivel">Nivel</label>
                                    <input id="nivel" min="0" max="3" name="nivel" type="number" class="form-control"  required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="comision_puntos">Comision por pesos</label>
                                    <input id="comision_puntos" name="comision_puntos" type="number" class="form-control"  required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <label for="vendedores_directos">Vendedores directos</label>
                                    <input id="vendedores_directos" name="vendedores_directos" type="number" class="form-control" required>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-6 text-left">
                                    <button class="btn btn-primary" type="submit" >Crear</button>
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





