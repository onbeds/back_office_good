<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Good</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="icon" href="https://cdn.shopify.com/s/files/1/2256/3751/files/favicon-96x96.png?14986824105996215938" type="image/x-icon">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.0/sweetalert2.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.0/sweetalert2.min.js"></script>
    <style>

        .form-container{
            background: url(https://cdn.shopify.com/s/files/1/1935/1047/files/signos_3x_0238d17e-87ae-472b-a8a5-7df09dbbf1e2.png?16593444349503374341) no-repeat 3% 10px / auto,
            url(https://cdn.shopify.com/s/files/1/1935/1047/files/signos_3x_0238d17e-87ae-472b-a8a5-7df09dbbf1e2.png?16593444349503374341) no-repeat 97% 10px / auto,
            url(https://cdn.shopify.com/s/files/1/1935/1047/files/signos_3x_0238d17e-87ae-472b-a8a5-7df09dbbf1e2.png?16593444349503374341) no-repeat 3% 97% / auto,
            url(https://cdn.shopify.com/s/files/1/1935/1047/files/signos_3x_0238d17e-87ae-472b-a8a5-7df09dbbf1e2.png?16593444349503374341) no-repeat 97% 97% / auto;
            background-color: rgba(237,28,33,.9);
            box-shadow: 0px 0px 8px 2px rgba(0,0,0,.25);
            border-radius: 3px;
            padding: 30px;
            box-sizing: border-box;
            min-height: 350px;

        }

        .logo-alert{
            position: relative;
            display: block;
            max-width: 300px;
            margin: 0 auto;
        }

        .alert{
            color: white;
            text-align: justify;
        }

        .form-access{
            font-family: 'Quicksand', sans-serif;
        }

        .form-access img{
            width: 300px;
            height: auto;
        }

        .form-access label{

            color: white;
        }

        .form-access input{
            border: none;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            color: gray;
            transition: .3s ease-in;
        }

        .form-access input:checkbox{
            padding: 0px;
        }


        .form-access input:hover{
            padding-left: 20px;
            transition: .3s ease-out;
            box-shadow: 0px 0px 5px 2px rgba(0,0,0,.25);
        }

        .input-group:hover{
            transition: .3s ease-in;

        }

        .input-group-addon{
            border: none;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .form-access .submit-access{
            color: white;
        }

        .form-access .submit-access{
            border-radius: 20px;
            background-color: rgba(0,0,0,0);
            color: white;
            border: 2px solid white;
            padding: 5px 25px;
            transition: .6s;
            font-weight: 700;
            box-shadow: 0px 4px 6px 3px rgba(0,0,0,.15);
        }

        .form-access .submit-access:hover{
            background-color: rgba(255,255,255,1);
            transition: .6s;
            color: gray;
            padding: 5px 35px;
        }

        video {
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            transform: translateX(-50%) translateY(-50%);
            background: url('//demosthenes.info/assets/images/polina.jpg') no-repeat;
            background-size: cover;
            transition: 1s opacity;
        }

        .checkbox label:after,
        .radio label:after {
            content: '';
            display: table;
            clear: both;
        }

        .checkbox .cr,
        .radio .cr {
            position: relative;
            display: inline-block;
            border: 2px solid white;
            border-radius: .25em;
            width: 1.5em;
            height: 1.5em;
            float: left;
            margin-right: .5em;
        }

        .radio .cr {
            border-radius: 50%;
        }

        .checkbox .cr .cr-icon,
        .radio .cr .cr-icon {
            position: absolute;
            font-size: 1em;
            line-height: 0;
            top: 50%;
            left: 8%;
        }

        .radio .cr .cr-icon {
            margin-left: 0.04em;
        }

        .checkbox label input[type="checkbox"],
        .radio label input[type="radio"] {
            display: none;
        }

        .checkbox label input[type="checkbox"] + .cr > .cr-icon,
        .radio label input[type="radio"] + .cr > .cr-icon {
            transform: scale(3) rotateZ(-20deg);
            opacity: 0;
            transition: all .3s ease-in;
        }

        .checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
        .radio label input[type="radio"]:checked + .cr > .cr-icon {
            transform: scale(1) rotateZ(0deg);
            opacity: 1;
        }

        .checkbox label input[type="checkbox"]:disabled + .cr,
        .radio label input[type="radio"]:disabled + .cr {
            opacity: .5;
        }

        a:hover {
            text-decoration: none;
        }


    </style>
</head>

<body style="background: url(https://cdn.shopify.com/s/files/1/2256/3751/files/1.jpg?17275024453047598251) no-repeat center / cover; height: 100vh">

<div class="container" style="height: 100%">

    <div class="row" style="height: 100%; display:flex; justify-content: center; align-items: center">
        <div class="col-xs-10 col-sm-4 col-sm-offset-0">
            <div class="col-xs-12">
                @if (session('message'))
                <div class="alert alert-success fade in col-xs-12" style="background-color: #ed7d01 !important;border: none; color: white;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <ul style=" color: white;">
                        <li>{{ session('message') }}</li>
                    </ul>
                </div>
                @endif

            </div>


            @if ($errors->any())
                <div class="col-xs-12">
                    <div class="alert alert-success fade in col-xs-12" style="background-color: #ed7d01 !important;border: none; color: white;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="col-xs-12 col-sm-12 form-container" id="form-container" style="background-color: #ed7d01 !important;">

  {!! Form::open(['route' => 'recuperar', 'method' => 'POST', 'class' => 'form-access submit']) !!}
 <input value="{{$token}}" name="token" type="hidden">
                <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 text-center">
                    <img src="https://cdn.shopify.com/s/files/1/2256/3751/files/logo.png?17179620079827299362" alt="" style="width: 50%; margin: 20px auto; display: block">
                </div>

                <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 text-center">
                  <p style="color: white; font-weight: bold;"> Recuperar contraseña</p>
                </div>
               
                <div class="col-xs-12">
                     <br>
                    <div class="form-group">
                        <label for="user-id">Correo electronico</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input readonly type="email" id="email" class="form-control campo-access" value="{{$email}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user-id">Escriba su nueva contraseña</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="password" name="password" id="password_1" class="form-control campo-access" placeholder="Escriba su nueva contraseña">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user-id">Vuelva a escribir la contraseña</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="password"  id="password_2" class="form-control campo-access" placeholder="Vuelva a escribir la contraseña">
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <br>
                        <input type="submit" id="enviar" name="submit" value="Recuperar"  class="btn submit-access col-xs-12">

                    </div>
                </div>

 {!! Form::close() !!}

            </div>

        </div>

    </div>
</div>

<script type="text/javascript">
    $('.submit').on('submit',function(event){

            var password = $('#password_1').val();

                if (password.length < 5) { 
                  swal({
                  title: 'Vuelva a intentarlo',
                  html: $('<div>').text('Lo sentimos, pero tiene que escribir una contraseña (minimo 6 digitos).'),
                  animation: false,
                  customClass: 'animated tada'
                  });

                    event.preventDefault() ;
                }   
                if ($('#password_1').val() == '' || $('#password_2').val() == '') {
                  swal({
                  title: 'Vuelva a intentarlo',
                  html: $('<div>').text('Lo sentimos, pero tiene que digitar una contraseña.'),
                  animation: false,
                  customClass: 'animated tada'
                  });

                    event.preventDefault() ;
                }        //
                 if ($('#password_1').val() != $('#password_2').val()) {
                  swal({
                  title: 'Vuelva a intentarlo',
                  html: $('<div>').text('Lo sentimos, pero las contraseñas no coinciden.'),
                  animation: false,
                  customClass: 'animated tada'
                  });

                    event.preventDefault() ;
                }   

    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>