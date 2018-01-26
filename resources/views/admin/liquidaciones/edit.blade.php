@extends('templates.dash')



@section('titulo','Good')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/round-slider@1.3/dist/roundslider.min.css" rel="stylesheet" />
    <style>

        #handle3 .rs-range-color  {
            background-color: #FA9300;
        }
        #handle3 .rs-path-color  {
            background-color: #22B573;
        }
        #handle3 .rs-handle  {
            background-color: #D8D8D8;
            padding: 7px;
        }

        #handle3 .rs-handle.rs-focus  {
        }

        #handle3 .rs-handle:after  {
            background-color: #575757;
        }
        #handle3 .rs-border  {
            border-color: transparent;
        }



        .rs-control{
            margin: 0 auto;
        }


        #handle1 .rs-handle  {
            background-color: transparent;
            border: 8px solid transparent;
            border-right-color: black;
            margin: -6px 0px 0px 14px !important;
            border-width: 6px 104px 6px 4px;
        }
        #handle1 .rs-handle:before  {
            display: block;
            content: " ";
            position: absolute;
            height: 22px;
            width: 22px;
            background: black;
            right: -11px;
            bottom: -11px;
            border-radius: 100px;
        }
        #handle1 .rs-tooltip  {
            top: 75%;
            font-size: 11px;
        }
        #handle1 .rs-tooltip div  {
            text-align: center;
            background: orange;
            color: white;
            border-radius: 4px;
            padding: 1px 5px 2px;
            margin-top: 4px;
        }
        #handle1 .rs-range-color  {
            background-color: #FA9300;
        }
        #handle1 .rs-path-color  {
            background-color: #22B573;
        }

        /* Estilos modificados*/

        .rs-control{
            margin: 0 auto;
        }

        .good{
            border-radius: 20px;
            border: none;
            background-color: orange;
            padding: 10px 20px;
            text-align: center;
            text-transform: uppercase;
            font-weight: bolder;
            color: white;
            font-size: 1.5em;
            box-shadow: inset 0px 0px 2px 2px rgba(0,0,0,.1);
        }

        .mercando{
            border-radius: 20px;
            border: none;
            background-color: #22B573;
            padding: 10px 20px;
            text-align: center;
            text-transform: uppercase;
            font-weight: bolder;
            color: white;
            font-size: 1.5em;
            box-shadow: inset 0px 0px 2px 2px rgba(0,0,0,.1);
        }

    </style>
@stop

@section('content')

    {!! Html::style('css/material-dashboard.css?act=4') !!}

    <link href="https://fonts.googleapis.com/css?family=Bungee|Roboto+Slab:100,300,400,700" rel="stylesheet">

    <section class="invoice">



        <div class="row">

            <form id="bono" name="bono" action="{{route('admin.liquidaciones.gift_card')}}" method="post">
                <input type="hidden" id="_token" name="_token" value="{{csrf_token()}}">
                    @if ($bono < 0)
                    <input type="hidden" id="bono" name="bono" value="{{ - 1 * $bono}}">
                    @else
                    <input type="hidden" id="bono" name="bono" value="{{$bono}}">
                    @endif
                <input type="hidden" id="liquidacion" name="liquidacion" value="{{$id}}">
                <input type="hidden" id="good" name="good" readonly class="good-hidden">
                <input type="hidden" id="mercando" name="mercando" readonly class="mercando-hidden">

                <div class="col-xs-12">
                    <div class="card card-stats" style="margin: 5px 0; background: white">
                        <div class="container">
                            <div class="col-sm-3 col-sm-3">

                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="card card-stats">
                                    <div class="card-content" style="background: #FFBC42; margin-bottom: 20px; color: white; border-bottom: 2px solid #F3AC23;  border-top: 2px solid #F3AC23; text-shadow: 0px 1px 1px  rgba(0,0,0,.2); border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                                        <h2 style="text-align: center"> Valor total de su bono: </h2>
                                    </div>
                                    <div class="card-content">
                                        <h3 class="title" style="text-align: center;">
                                            @if($total < 0)
                                                <span style="color: orange; font-size: 2.2em; font-weight: bolder; text-transform: uppercase;">${{ number_format(-1 * $total) }}</span></small>
                                            @else
                                                <span style="color: orange; font-size: 2.2em; font-weight: bolder; text-transform: uppercase;">${{ number_format($total) }}</span></small>
                                            @endif

                                        </h3>
                                    </div>
                                    <div class="card-footer" style="display: block;">
                                        <div class="stats">
                                            <h4 style="color: gray;">¡Este es el valor total de tu bono, moviendo el circulo que esta en la parte de abajo podrás distribuirlo como prefieras en nuestras tiendas Tienda Good y Mercando, a disfrutar con Good!</h4>
                                            <i class="material-icons text-danger"></i>
                                            <a href="http://a4469bba.ngrok.io/admin/liquidaciones/1345/edit#pablo"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3 col-sm-3">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="card card-stats" style="margin: 5px 0; background: white; padding: 50px 0;">
                        <!-- Comienza el contenedor del medidor -->
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-3 col-xs-12">
                                    <div class="row">
                                        <img src="https://cdn.shopify.com/s/files/1/2256/3751/files/logo-good.png?7720396070787645882" alt="Tienda Good">
                                    </div>
                                    <input id="good-1" name="good-1" readonly class="good">
                                </div>

                                <div class="col-sm-6 col-xs-12" style="margin: 20px 0;">
                                    <div id="handle3" class="rs-control rs-animation" style="height: 210px; width: 210px;">
                                        <div class="rs-container full pie" style="height: 210px; width: 210px;">
                                            <div class="rs-inner-container">
                                                <div class="rs-block rs-outer rs-border rs-split">
                                                    <div class="rs-path rs-transition rs-range-color" style="transform: rotate(315deg);"></div>
                                                    <div class="rs-path rs-transition rs-range-color" style="opacity: 1; transform: rotate(386.1deg);"></div>
                                                    <div class="rs-path rs-transition rs-path-color" style="transform: rotate(566.1deg); opacity: 0;"></div>
                                                    <div class="rs-path rs-transition rs-path-color" style="opacity: 1; z-index: 1; transform: rotate(135deg);"></div>
                                                    <span class="rs-block" style="padding: 16px;"><div class="rs-inner rs-bg-color rs-border"></div></span>
                                                </div>
                                            </div>

                                            <div class="rs-bar rs-transition rs-first" style="z-index: 8; transform: rotate(566.1deg);">
                                                <div class="rs-handle rs-handle-square rs-move" index="2" tabindex="0" role="slider" aria-label="handle1_handle" style="height: 0px; width: 0px; margin: 0px 0px 0px 9px;" aria-valuenow="93" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="rs-bar rs-transition rs-start" style="transform: rotate(315deg);">
                                                <span class="rs-seperator rs-border" style="width: 18px; margin-top: -0.5px; border-color: rgba(0,0,0,0) !important;"></span>
                                            </span>
                                            <span class="rs-bar rs-transition rs-end" style="transform: rotate(585deg);">
                                                <span class="rs-seperator rs-border" style="width: 18px; margin-top: -0.5px; border-color: rgba(0,0,0,0) !important;"></span>
                                            </span>
                                            <div class="rs-overlay rs-transition rs-bg-color" style="transform: rotate(585deg);"></div>
                                            <span class="rs-tooltip rs-tooltip-text" style="margin-top: -10.5px; margin-left: -19.5px;">G  %  M</span>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 col-xs-12">
                                    <img src="//cdn.shopify.com/s/files/1/2560/2330/files/Logo-Mercando_x96.png?v=1511966864" alt="Mercando">
                                    <input id="mercando-1" name="mercando-1" readonly class="mercando">
                                </div>

                            </div>
                        </div>

                        <!-- Finaliza el contenedor del medidor -->

                        <br>
                        @if (session('m') || session('g') || session('gm') || $state == false)
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="card-content" style="text-align: center;">
                                        <a href="{{route('admin.liquidaciones.index')}}" class="btn btn-primary text-center" data-background-color="orange" type="submit" value="Crear Bonos">Atrás</a>
                                    </div>
                                </div>
                            </div>
                        @else

                        <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary text-center" data-background-color="orange" data-toggle="modal" data-target="#exampleModal">
                                Generar Bonos Digitales Ahora
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Generar Bonos</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Está realmente seguro?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button id="enviar" class="btn btn-primary text-center" data-background-color="orange" type="submit" value="Crear Bonos">Generar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </form>
        </div>

    </section>


@endsection

@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/jquery@1.11.3/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/round-slider@1.3/dist/roundslider.min.js"></script>

    <script type="text/javascript">

        $("#handle3").roundSlider({

            editableTooltip: false,
            radius: 80,
            width: 10,
            min: 0,
            @if($bono < 0)
            max: parseFloat('{{-1 * $bono}}'),
            @else
            max: parseFloat('{{$bono}}'),
            @endif
            step: 1000,
            handleSize: "+16",
            handleShape: "dot",
            sliderType: "min-range",
            @if($bono < 0)
            value: parseFloat('{{(-1 * $bono)}}')/2,
            @else
            value: parseFloat('{{$bono}}')/2,
            @endif
            circleShape: "pie",
            startAngle: 315,
            tooltipFormat: "changeTooltip"
        });

        /*$("#handle1").roundSlider({

            sliderType: "min-range",
            editableTooltip: false,
            radius: 105,
            width: 16,
            value: 50,
            handleSize: 0,
            handleShape: "square",
            circleShape: "pie",
            startAngle: 315,
            tooltipFormat: "changeTooltip"
        });*/



        function changeTooltip(e) {

            var val = e.value, speed;

                    @if($bono < 0)
            var M = -1 * (val - parseFloat('{{ -1 * $bono}}'));
                    @else
            var M = -1 * (val - parseFloat('{{$bono}}'));
                    @endif

            var G = val;

            $(".good-hidden").val('' + G + '');

            $(".mercando-hidden").val('' +  M + '');

            $(".good").val('$ ' + number_format(G, 0) + '');

            $(".mercando").val('$ ' +  number_format(M, 0) + '');


            return "G " + " % " + " M";
        }



        $("form").submit(function(e){

            $('#enviar').prop('disabled', true);


                    @if($bono < 0)
            var total = parseFloat('{{-1 * $bono}}');
                    @else
            var total = parseFloat('{{$bono}}');
                    @endif


            var good = parseFloat($('#good').val());
            var mercando = parseFloat($('#mercando').val());
            var suma = (good + mercando);

            if (suma > total) {

                swal(
                    '¡Por favor, verifique que la suma de los bonos para Tienda Good y Mercando no superen el total del Bono!',
                    'Clicked para corregir!',
                    'error'
                );
                $('#enviar').prop('disabled', false);
                e.preventDefault();
            }

        });

        function number_format(amount, decimals) {

            amount += ''; // por si pasan un numero en vez de un string
            amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

            decimals = decimals || 0; // por si la variable no fue fue pasada

            // si no es un numero o es igual a cero retorno el mismo cero
            if (isNaN(amount) || amount === 0)
                return parseFloat(0).toFixed(decimals);

            // si es mayor o menor que cero retorno el valor formateado como numero
            amount = '' + amount.toFixed(decimals);

            var amount_parts = amount.split('.'),
                regexp = /(\d+)(\d{3})/;

            while (regexp.test(amount_parts[0]))
                amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

            return amount_parts.join('.');
        }

    </script>

    @if($errors->any())
        <script>
            swal(
                '{{$errors->first()}}',
                '¡Error!',
                'error'
            );
        </script>
    @endif

    @if (session('m'))
        <script>
            swal(
                '{{session('m')}}',
                '¡Disfrutalo!',
                'success'
            );
        </script>
    @endif

    @if (session('gm'))
        <script>
            swal(
                '{{session('gm')}}',
                '¡Disfrutalo!',
                'success'
            );
        </script>
    @endif
    @if (session('g'))
        <script>
            swal(
                '{{session('g')}}',
                '¡Disfrutalo!',
                'success'
            );
        </script>
    @endif



@stop