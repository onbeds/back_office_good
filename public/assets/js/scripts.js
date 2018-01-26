
function scroll_to_class(element_class, removed_height) {
	var scroll_to = $(element_class).offset().top - removed_height;
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 0);
	}
}

jQuery.extend(jQuery.jtsage.datebox.prototype.options,
    {
        'useLang': 'es-ES'
    });


jQuery(document).ready(function() {



    $("#type_client").select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccionar tipo cliente'
    });

    $("#type_dni").select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccionar tipo documento'
    });

    $("#city").select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccionar ciudad'
    });

    $("#sex").select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccionar sexo'
    });

    $("#bank").select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccionar banco'
    });

    $("#type_acount_bank").select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccionar tipo cuenta'
    });
	
    /*
        Fullscreen background
    */
    $.backstretch("assets/img/backgrounds/1.jpg");
    
    $('#top-navbar-1').on('shown.bs.collapse', function(){
    	$.backstretch("resize");
    });
    $('#top-navbar-1').on('hidden.bs.collapse', function(){
    	$.backstretch("resize");
    });
    
    /*
        Form
    */
    $('.f1 fieldset:first').fadeIn('slow');
    
    $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });

    var exp_number = /^[a-zA-Z0-9]{6,15}$/;
    var exp_acount = /^[0-9\-]{7,}$/;
    var exp_names =/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s]+$/;
    var exp_date = /^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/;
    var exp_address = /^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ#.\-_\s]+$/;
    var exp_phone = /^[0-9-()+]{3,20}$/;
    var exp_email = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.]+$/;

    var icono_paso_tres = '<div id="two" class="f1-step">\n' +
        '                            <div class="f1-step-icon"><i class="fa fa-check-square-o"></i></div>\n' +
        '                            <p>Tus documentos</p>\n' +
        '                        </div>';

    var prime = '<div class="form-group">\n' +
        '                            <label class="form-check-label">\n' +
        '                                <input id="prime" name="prime" class="form-check-input" type="checkbox">\n' +
        '                                Usuario Prime\n' +
        '                            </label>\n' +
        '                        </div>';

    var contract = '<div class="form-group">\n' +
        '                            <label for="contrato" class="form-check-label">\n' +
        '                                <input type="checkbox" id="contract" name="contract" required/>\n' +
        '                                Contrato <a href="terms" target="_blank">terminos</a>\n' +
        '                            </label>\n' +
        '                        </div>';
    var terms = '<div class="form-group">\n' +
        '                            <label for="condiciones" class="form-check-label">\n' +
        '                                <input  type="checkbox" id="terms" name="terms" required/>\n' +
        '                                ¿Acepta <a href="terms" target="_blank">terminos</a> y condiciones?\n' +
        '                            </label>\n' +
        '                        </div>';


    var tipo_cliente = $("#type_client").val();

    $("#four .btn-submit").hide();

    if (tipo_cliente.length > 0 && tipo_cliente != 83) {

        $("#two").remove();

        $("#four .btn-next").hide();

        $("#four .btn-submit").show();

        $("#two").remove();

        var padre = $("#password_confirmation").parent();

        padre.after("" +contract + terms + "");

        $("#four .btn-next").hide();

        $("#four .btn-submit").show();

        var parent_fieldset = $("#rut").parents('fieldset');

        parent_fieldset.find('input[type="checkbox"]').each(function () {
            $(this).parent().remove();
        });

        $('.f1-step').css({
            'width': '50%'
        });
    }

    $('#type_dni, #city, #sex').on('change', function() {
        var valor = $(this).val();
        $(this).parent().find('.alert-message').fadeOut();
        $(this).next().find('.select2-selection--single').removeClass('input-error');
    });

    $('#bank').on('change', function() {
        if ($(this).attr('id') == 'bank') {
            var bank = $(this).val();
            var type_acount_bank = $("#type_acount_bank").val();
            var acount = $("#acount").val();
            console.log(bank);
            if(bank != "") {
                $("#bank").next().find('.select2-selection--single').removeClass('input-error');
                $("#bank").parent().find('.alert-message').fadeOut();
                if (acount.length == 0){
                    $("#acount").addClass('input-error');
                    $("#acount").parent().find('.alert-message').fadeIn();
                }else{
                    $("#acount").removeClass('input-error');
                    $("#acount").parent().find('.alert-message').fadeOut();
                }

                if (type_acount_bank.length == 0){
                    $("#type_acount_bank").next().find('.select2-selection--single').addClass('input-error');
                    $("#type_acount_bank").parent().find('.alert-message').fadeIn();
                }else{
                    $("#type_acount_bank").next().find('.select2-selection--single').removeClass('input-error');
                    $("#type_acount_bank").parent().find('.alert-message').fadeOut();
                }
            }
            else{
                $("#bank").next().find('.select2-selection--single').removeClass('input-error');
                $("#bank").parent().find('.alert-message').fadeOut();
                $("#type_acount_bank").next().find('.select2-selection--single').removeClass('input-error');
                $("#type_acount_bank").parent().find('.alert-message').fadeOut();
                $("#acount").next().fadeOut();
                $("#acount").removeClass('input-error');                 
            }
        }
    });

    $('#type_acount_bank').on('change', function() {
        if ($(this).attr('id') == 'type_acount_bank') {
            var type_acount_bank = $(this).val();
            var acount = $("#acount").val();
            var bank =  $("#bank").val();
            console.log(type_acount_bank);

            if(type_acount_bank != "" ) {                
                $("#type_acount_bank").next().find('.select2-selection--single').removeClass('input-error');
                $("#type_acount_bank").parent().find('.alert-message').fadeOut();
                if (bank.length == 0){
                    $("#bank").next().find('.select2-selection--single').addClass('input-error');
                    $("#bank").parent().find('.alert-message').fadeIn();
                }else{
                    $("#bank").next().find('.select2-selection--single').removeClass('input-error');
                    $("#bank").parent().find('.alert-message').fadeOut();
                }

                if (acount.length == 0){
                    $("#acount").next().find('.select2-selection--single').addClass('input-error');
                    $("#acount").parent().find('.alert-message').fadeIn();
                }else{
                    $("#acount").next().find('.select2-selection--single').removeClass('input-error');
                    $("#acount").parent().find('.alert-message').fadeOut();
                }
            }
            else{
                $("#bank").next().find('.select2-selection--single').removeClass('input-error');
                $("#bank").parent().find('.alert-message').fadeOut();
                $("#type_acount_bank").next().find('.select2-selection--single').removeClass('input-error');
                $("#type_acount_bank").parent().find('.alert-message').fadeOut();
                $("#acount").next().fadeOut();
                $("#acount").removeClass('input-error');                 
            }
        }
    });

    $('#acount').on('keyup', function() {
        var acount= $(this).val();        
        if (acount != "") {
            if (exp_acount.test(acount)) {
                var bank = $("#bank").val();
                var type_acount_bank = $("#type_acount_bank").val();                
                    if (bank.length == 0){
                        $("#bank").next().find('.select2-selection--single').addClass('input-error');
                        $("#bank").parent().find('.alert-message').fadeIn();
                    }
                    else{
                        $("#type_acount_bank").next().find('.select2-selection--single').removeClass('input-error');
                        $("#type_acount_bank").parent().find('.alert-message').fadeOut();                   
                    }
                    if (type_acount_bank.length == 0){
                        $("#type_acount_bank").next().find('.select2-selection--single').addClass('input-error');
                        $("#type_acount_bank").parent().find('.alert-message').fadeIn();
                    }
                    else{
                        $("#type_acount_bank").next().find('.select2-selection--single').removeClass('input-error');
                        $("#type_acount_bank").parent().find('.alert-message').fadeOut();                   
                    }
                    $(this).next().fadeOut();
                    $(this).removeClass('input-error');                
            } else {
                    $(this).next().fadeIn();
                    $(this).addClass('input-error');                  
            }
        }
        else{
            $(this).next().fadeOut();
            $(this).removeClass('input-error'); 
            $("#bank").next().find('.select2-selection--single').removeClass('input-error');
            $("#bank").parent().find('.alert-message').fadeOut();
            $("#type_acount_bank").next().find('.select2-selection--single').removeClass('input-error');
            $("#type_acount_bank").parent().find('.alert-message').fadeOut();             
        }
    });

    $('#first-name').on('keyup', function() {
        if ($(this).attr('id') == 'first-name') {

            var first_name = $(this).val();

            if (exp_names.test(first_name) && first_name.length > 2) {

                $(this).removeClass('input-error');
                $(this).next().fadeOut();

            }
            else {

                $(this).addClass('input-error');
                $(this).next().fadeIn();
                next_step = false;
            }
        }
    });

    $('#last-name').on('keyup', function() {
        if ($(this).attr('id') == 'last-name') {

            var last_name = $(this).val();

            if (exp_names.test(last_name) && last_name.length > 2 ) {

                $(this).removeClass('input-error');
                $(this).next().fadeOut();
            }
            else {

                $(this).addClass('input-error');
                $(this).next().fadeIn();
                next_step = false;
            }
        }
    });

    $('#dni').on('keyup', function() {
        if ($(this).attr('id') == 'dni') {

            var number = $(this).val();

            if (exp_number.test(number)) {

                //$(this).removeClass('input-error');
                $(this).removeClass('input-error');
                $(this).next().fadeOut();
            }
            else {
                //$(this).addClass('input-error');
                $(this).addClass('input-error');
                $(this).next().fadeIn();
                $(this).next().html('Escribe una contraseña (minimo 6 digitos).');
                next_step = false;
            }
        }
    });

    $('#address').on('keyup', function() {
        if ($(this).attr('id') == 'address') {

            var address = $(this).val();

            if (exp_address.test(address) && address.length > 5 ) {

                $(this).removeClass('input-error');
                $(this).next().fadeOut();
            }
            else {

                $(this).addClass('input-error');
                $(this).next().fadeIn();
                next_step = false;
            }
        }
    });

    $('#phone').on('keyup', function() {
        if ($(this).attr('id') == 'phone') {

            var phone = $(this).val();

            if (exp_phone.test(phone) && phone.length == 10 ) {

                $(this).removeClass('input-error');
                $(this).next().fadeOut();
            }
            else {

                $(this).addClass('input-error');
                $(this).next().fadeIn();
                $(this).next().html('Es demasiado corto o grande (se usa 10 dígitos).');
                next_step = false;
            }
        }
    });

    $('#email').on('keyup', function() {
        if ($(this).attr('id') == 'email') {

            var email = $(this).val();

            if (exp_email.test(email) && email.length > 0) {

                $(this).removeClass('input-error');
                $(this).next().fadeOut();
            }
            else {

                $(this).addClass('input-error');
                $(this).next().fadeIn();
                next_step = false;
            }
        }
    });

    $('#password').on('keyup', function() {
        if ($(this).attr('id') == 'password') {

            var password = $(this).val();

            if (password.length > 5) {

                $(this).removeClass('input-error');
                $(this).next().fadeOut();
            }
            else {

                $(this).addClass('input-error');
                $(this).next().fadeIn();
                next_step = false;
            }
        }
    });

    $('#password_confirmation').on('keyup', function(e) {
        if ($(this).attr('id') == 'password_confirmation') {

            var password_confirmation = $(this).val();
            var password = $("#password").val();

            if (password_confirmation.length > 5 && password_confirmation == password) {

                $(this).removeClass('input-error');+
                    $(this).next().fadeOut();

            }
            else {

                $(this).addClass('input-error');
                $(this).next().fadeIn();
            }
        }
    });

    $('#type_client').on('change', function() {

        var valor = $(this).val();

        $(this).parent().find('.alert-message').fadeOut();
        $(this).next().find('.select2-selection--single').removeClass('input-error');

        var parent_fieldset = $("#password_confirmation").parents('fieldset');

        parent_fieldset.find('input[type="checkbox"]').each(function () {
            $(this).parent().remove();
        });

        var parent_fieldset = $("#rut").parents('fieldset');

        parent_fieldset.find('input[type="checkbox"]').each(function () {
            $(this).parent().remove();
        });

        if (valor != 83) {

            $("#two").remove();

            var padre = $("#password_confirmation").parent();

            padre.after("" + contract + terms + "");

            $("#four .btn-next").hide();

            $("#four .btn-submit").show();

            var parent_fieldset = $("#rut").parents('fieldset');

            parent_fieldset.find('input[type="checkbox"]').each(function () {
                $(this).parent().remove();
            });

            $('.f1-step').css({
                'width': '50%'
            });

        } else {

            $("#two").remove();

            $("#one").after(icono_paso_tres);

            $("#four .btn-next").show();

            $("#four .btn-submit").hide();

            var parent_fieldset = $("#rut").parents('fieldset');

            parent_fieldset.find('input[type="checkbox"]').each(function () {
                $(this).parent().remove();
            });

            var padre = $("#rut").parent();

            padre.after("" + prime + "" + contract + terms + "");

            var parent_fieldset = $("#password_confirmation").parents('fieldset');

            parent_fieldset.find('input[type="checkbox"]').each(function () {
                $(this).parent().remove();
            });

            $('.f1-step').css({
                'width': '33.3%'
            });

        }
    });

    $('#bank').on('change', function() {


        var bank = $(this).val();
        var type_acount = $("#type_acount_bank").val();
        var acount = $("#acount").val();

        if (type_acount.length == 0 && bank.length == 0 && acount.length === 0) {

            $(this).removeClass('input-error');
            $("#type_acount_bank").removeClass('input-error');
            $("#acount").removeClass('input-error');
        }

    });

    $('#type_acount_bank').on('change', function() {

        var type_acount= $(this).val();
        var bank = $("#bank").val();
        var acount = $("#acount").val();

        if (type_acount.length == 0 && bank.length == 0 && acount.length === 0) {

            $(this).removeClass('input-error');
            $("#bank").removeClass('input-error');
            $("#acount").removeClass('input-error');
        }
    });

    // next step
    $('.f1 .btn-next').on('click', function() {

        var parent_fieldset = $(this).parents('fieldset');
        var next_step = true;
        // navigation steps / progress steps
        var current_active_step = $(this).parents('.f1').find('.f1-step.active');
        var progress_line = $(this).parents('.f1').find('.f1-progress-line');

        // fields validation

        parent_fieldset.find('input[type="text"], input[type="email"], input[type="number"], input[type="tel"], input[type="date"], input[type="password"], select').each(function() {

            if ($(this).attr('id') == 'type_client') {

                if( $(this).val() == "" ) {

                    //$(this).addClass('input-error');
                    $(this).parent().find('.alert-message').fadeIn();
                    $(this).next().find('.select2-selection--single').addClass('input-error');

                    next_step = false;

                }
                else {
                    $(this).parent().find('.alert-message').fadeOut();
                    $(this).next().find('.select2-selection--single').removeClass('input-error');
                    $(this).blur();
                    //$(this).removeClass('input-error');
                }
            }

            if ($(this).attr('id') == 'type_dni') {

                if($(this).val() == ""  ) {

                    $(this).parent().find('.alert-message').fadeIn();
                    //$(this).addClass('input-error');
                    $(this).next().find('.select2-selection--single').addClass('input-error');
                    next_step = false;

                }
                else {
                    $(this).parent().find('.alert-message').fadeOut();
                    $(this).next().find('.select2-selection--single').removeClass('input-error');
                    //$(this).removeClass('input-error');

                }
            }

            if ($(this).attr('id') == 'city') {

                if( $(this).val() == "" ) {

                    $(this).parent().find('.alert-message').fadeIn();
                    //$(this).addClass('input-error');
                    $(this).next().find('.select2-selection--single').addClass('input-error');
                    next_step = false;

                }
                else {

                    $(this).parent().find('.alert-message').fadeOut();
                    //$(this).removeClass('input-error');
                    $(this).next().find('.select2-selection--single').removeClass('input-error')

                }
            }

            if ($(this).attr('id') == 'sex') {

                if( $(this).val() == "" ) {

                    $(this).parent().find('.alert-message').fadeIn();
                    $(this).next().find('.select2-selection--single').addClass('input-error');
                    //$(this).addClass('input-error');
                    next_step = false;

                }
                else {

                    $(this).parent().find('.alert-message').fadeOut();
                    //$(this).removeClass('input-error');
                    $(this).next().find('.select2-selection--single').removeClass('input-error')

                }
            }

            if ($(this).attr('id') == 'first-name') {

                var first_name = $(this).val();

                if (exp_names.test(first_name) && first_name.length > 2 ) {

                    $(this).removeClass('input-error');
                    $(this).next().fadeOut();

                }
                else {

                    $(this).addClass('input-error');
                    $(this).next().fadeIn();
                    next_step = false;
                }
            }

            if ($(this).attr('id') == 'last-name') {

                var last_name = $(this).val();

                if (exp_names.test(last_name) && last_name.length > 2 ) {

                    $(this).removeClass('input-error');
                    $(this).next().fadeOut();

                }
                else {

                    $(this).addClass('input-error');
                    $(this).next().fadeIn();
                    next_step = false;
                }
            }

            if ($(this).attr('id') == 'birthday') {

                var date = $(this).val();

                if (exp_date.test(date) && date.length == 10  ) {
                    $(this).removeClass('input-error');
                    $('.fech').next().fadeOut();
                }
                else {
                    $(this).addClass('input-error');
                    $('.fech').next().fadeIn();
                    next_step = false;
                }
            }

            if ($(this).attr('id') == 'address') {

                var address = $(this).val();

                if (exp_address.test(address) && address.length > 5 ) {

                    $(this).removeClass('input-error');
                    $(this).next().fadeOut();
                }
                else {

                    $(this).addClass('input-error');
                    $(this).next().fadeIn();
                    next_step = false;
                }
            }

            if ($(this).attr('id') == 'dni') {
                var info = dni(); 
                if (info == false){  next_step = false;  }
            }

            if ($(this).attr('id') == 'phone') {
                var info = phone(); 
                if (info == false){  next_step = false;  }
            }

            if ($(this).attr('id') == 'code') {
                var info = code();   
                if (info == false){  next_step = false;  }         
            }

            if ($(this).attr('id') == 'email') {
                var info = email();
                if (info == false){  next_step = false;  }
            }

            if ($(this).attr('id') == 'password') {

                var password = $(this).val();

                if (password.length > 5) {

                    $(this).removeClass('input-error');
                    $(this).next().fadeOut();

                }
                else {

                    $(this).addClass('input-error');
                    $(this).next().fadeIn();
                    next_step = false;
                }
            }

            if ($(this).attr('id') == 'password_confirmation') {

                var password_confirmation = $(this).val();
                var password = $("#password").val();

                if (password_confirmation.length > 5 && password_confirmation == password) {

                    $(this).removeClass('input-error');
                    $(this).next().fadeOut();

                }
                else {

                    $(this).addClass('input-error');
                    $(this).next().fadeIn();
                    next_step = false;
                }
            }


        });

        // fields validation

        if( next_step ) {

            parent_fieldset.fadeOut(400, function() {
                // change icons
                current_active_step.removeClass('active').addClass('activated').next().addClass('active');

                // show next step
                $(this).next().fadeIn();
                // scroll window to beginning of the form
                scroll_to_class( $('.f1'), 20 );
            });
        }

    });


    // previous step
    $('.f1 .btn-previous').on('click', function() {
    	// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	
    	$(this).parents('fieldset').fadeOut(400, function() {
    		// change icons
    		current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');

    		// show previous step
    		$(this).prev().fadeIn();
    		// scroll window to beginning of the form
			scroll_to_class( $('.f1'), 20 );
    	});
    });

    // submit
    $('.f1').on('submit', function(e) {

        var result_email;
        var result_code;
    	var result_phone;
        var result_dni;
    	// fields validation
    	$(this).find('input[type="checkbox"], input[type="number"], input[type="text"], input[type="email"], input[type="password"], select').each(function() {

    	    
            if ($(this).attr('id') == 'contract') {

                if( $(this).is(':checked')  ) {
                    $(this).parent().removeClass('input-error');

                }else {
                    $(this).parent().addClass('input-error');

                    e.preventDefault();
                }
            }

            if ($(this).attr('id') == 'phone') {

                var phone = $(this).val();
                console.log(phone);
                result_phone = JSON.parse( $.ajax({
                    url: 'validate/phone',
                    type: 'post',
                    data: {phone: phone},
                    dataType: 'json',
                    async:false,
                    success: function (json) {
                        console.log(json);
                        return json;
                    },

                    error : function(xhr, status) {
                        console.log('Disculpe, existió un problema');
                    },

                    complete : function(xhr, status) {
                        console.log('Petición realizada');
                    }
                }).responseText);

                if (exp_phone.test(phone)) {

                    $(this).removeClass('input-error');
                    $(this).next().fadeOut();
                }
                else {

                    $(this).addClass('input-error');
                    $(this).next().fadeIn();

                    e.preventDefault();
                }

            }

            if ($(this).attr('id') == 'dni') {

                var dni = $(this).val();

                result_dni = JSON.parse( $.ajax({
                    url: 'validate/dni',
                    type: 'post',
                    data: {dni: dni},
                    dataType: 'json',
                    async:false,
                    success: function (json) {
                        return json
                    },

                    error : function(xhr, status) {
                        console.log('Disculpe, existió un problema');
                    },

                    complete : function(xhr, status) {
                        console.log('Petición realizada');
                    }
                }).responseText);

                if (exp_number.test(code)) {

                    $(this).removeClass('input-error');
                    $(this).next().fadeOut();
                }
                else {

                    $(this).addClass('input-error');
                    $(this).next().fadeIn();

                    e.preventDefault();
                }

                if (result_code == false) {
                    e.preventDefault();
                }
            }

            if ($(this).attr('id') == 'code') {

                var code = $(this).val();

                result_code = JSON.parse( $.ajax({
                    url: 'validate/code',
                    type: 'post',
                    data: {code: code},
                    dataType: 'json',
                    async:false,
                    success: function (json) {
                        return json
                    },

                    error : function(xhr, status) {
                        console.log('Disculpe, existió un problema');
                    },

                    complete : function(xhr, status) {
                        console.log('Petición realizada');
                    }
                }).responseText);

                if (exp_number.test(code)) {

                    $(this).removeClass('input-error');
                    $(this).next().fadeOut();
                }
                else {

                    $(this).addClass('input-error');
                    $(this).next().fadeIn();

                    e.preventDefault();
                }

                if (result_code == false) {
                    e.preventDefault();
                }
            }

            if ($(this).attr('id') == 'email') {

                var email = $(this).val();

                result_email = JSON.parse( $.ajax({
                    url: 'validate/email',
                    type: 'post',
                    data: {email: email},
                    dataType: 'json',
                    async:false,
                    success: function (json) {
                        return json
                    },

                    error : function(xhr, status) {
                        console.log('Disculpe, existió un problema');
                    },

                    complete : function(xhr, status) {
                        console.log('Petición realizada');
                    }
                }).responseText);



                if (exp_email.test(email) && email.length > 0) {
                    $(this).next().fadeOut();
                    $(this).removeClass('input-error');

                }
                else {
                    $(this).next().fadeOut();
                    $(this).addClass('input-error');
                    e.preventDefault();
                }
                if (result_email == false){
                    e.preventDefault();
                }

            }

            if ($(this).attr('id') == 'password') {

                var password = $(this).val();

                if (password.length > 5) {
                    $(this).next().fadeOut();
                    $(this).removeClass('input-error');
                }
                else {
                    $(this).next().fadeIn();
                    $(this).addClass('input-error');
                    e.preventDefault();
                }
            }

            if ($(this).attr('id') == 'password_confirmation') {

                var password_confirmation = $(this).val();
                var password = $("#password").val();

                if (password_confirmation.length > 5 && password_confirmation == password) {
                    $(this).next().fadeOut();
                    $(this).removeClass('input-error');

                }
                else {
                    $(this).next().fadeIn();
                    $(this).addClass('input-error');
                    e.preventDefault();
                }
            }

            if ($(this).attr('id') == 'terms') {

                if( $(this).is(':checked')  ) {
                    $(this).parent().removeClass('input-error');

                }else {
                    $(this).parent().addClass('input-error');
                    e.preventDefault();
                }
            }

            if ($(this).attr('id') == 'bank') {

                var bank = $(this).val();
                var type_acount_bank = $("#type_acount_bank").val();
                var acount = $("#acount").val();

                if( bank != "") {

                    if (acount.length == 0){

                        $("#acount").addClass('input-error');
                        e.preventDefault();

                    }else{

                        $("#acount").removeClass('input-error');
                    }

                    if (type_acount_bank.length == 0){

                        $("#type_acount_bank").addClass('input-error');
                        e.preventDefault();

                    }else{

                        $("#type_acount_bank").removeClass('input-error');
                    }

                }
            }

            if ($(this).attr('id') == 'type_acount_bank') {

                var type_acount_bank = $(this).val();
                var acount = $("#acount").val();
                var bank =  $("#bank").val();

                if(type_acount_bank != "" ) {

                    if (bank.length == 0){
                        $("#bank").addClass('input-error');
                        e.preventDefault();
                    }else{
                        $("#bank").removeClass('input-error');
                    }

                    if (acount.length == 0){
                        $("#acount").addClass('input-error');
                        e.preventDefault();
                    }else{
                        $("#acount").removeClass('input-error');
                    }

                }
            }

            if ($(this).attr('id') == 'acount') {

                var acount= $(this).val();
                var bank = $("#bank").val();
                var type_acount_bank = $("#type_acount_bank").val();


                if (acount != "") {

                    if(exp_acount.test(acount)) {

                        $(this).removeClass('input-error');

                        if (bank == 0 && type_acount_bank.length == 0){


                            $("#bank").addClass('input-error');
                            $("#type_acount_bank").addClass('input-error');
                            e.preventDefault();

                        }

                        if (bank.length == 0 && type_acount_bank.length > 0){

                            $("#bank").addClass('input-error');
                            $("#type_acount_bank").removeClass('input-error');
                            e.preventDefault();

                        }

                        if (bank.length > 0 && type_acount_bank.length == 0){

                            $("#type_acount_bank").addClass('input-error');
                            $("#bank").removeClass('input-error');
                            e.preventDefault();

                        }

                        if (bank.length > 0 && type_acount_bank.length > 0){

                            $("#type_acount_bank").removeClass('input-error');
                            $("#bank").removeClass('input-error');

                        }

                    } else {

                        $(this).addClass('input-error');

                        e.preventDefault();
                    }
                }
            }

    	});

        if (result_code.err  || result_email.err || result_phone.err || result_dni.err) {

            if (result_code.err) {

                swal(
                    'Oops...',
                    'El código de su referido no existe o no se puede hacer red con este código, verifiquelo por favor.',
                    'error'
                );

                e.preventDefault();

            }

            if (result_email.err == 'email existe') {
                swal(
                    'Oops...',
                    'El email que ingresó existe, ingrese otro por favor.',
                    'error'
                );

                e.preventDefault();
            }

            if (result_phone.err == 'telefono existe') {
                swal(
                    'Oops...',
                    'El número de teléfono que ingresó existe, ingrese otro por favor.',
                    'error'
                );

                e.preventDefault();
            }

            if(result_dni.err == 'dni no valido') {
                     swal(
                    'Oops...',
                    'El número de documento que ingresó existe, ingrese otro por favor.',
                    'error'
                    );

                    e.preventDefault();                              
            }
        }

        //e.preventDefault();
    	
    });


    $('#code').on('blur', function() {
        code();
    });

    $('#email').on('blur', function() {
        email();
    });

    $('#dni').on('blur', function() {
        dni();
    });

    $('#phone').on('blur', function() { 
        phone();
    });

    $('#birthday, .glyphicon-calendar').on('click', function() { 
        $('.fech').next().fadeOut(); 
    });

    function dni(){
        var dni = $('#dni').val();
        if (exp_number.test(dni)) {
                result_dni = JSON.parse( $.ajax({
                    url: 'validate/dni',
                    type: 'post',
                    data: {dni: dni},
                    dataType: 'json',
                    async:false
                }).responseText);

                if (result_dni.msg == 'dni valido') {
                    $('#dni').removeClass('input-error');
                    $('#dni').next().fadeOut();
                    return true;
                }
                else if(result_dni.err == 'dni no valido') {
                    $('#dni').addClass('input-error');
                    $('#dni').next().fadeIn();
                    $('#dni').next().html('El número de documento que ingresó existe, ingrese otro por favor.');
                    return false;
                }
                else if(result_dni.err == 'desactivado') { 
                    $('#dni').next().fadeIn();
                    $('#dni').next().html('<span style="color:green">Usuario anulado, la información sera actualizada.</span>'); 
                }  
        }
        else{
                    $('#dni').addClass('input-error');
                    $('#dni').next().fadeIn(); 
                    $('#dni').next().html('Es demasiado corto (usa mínimo 6 caracteres).');
                    return false;
        } 

    }

    function phone(){
        var phone = $('#phone').val();
        if (exp_phone.test(phone) && phone.length == 10 ) {
                result_phone = JSON.parse( $.ajax({
                    url: 'validate/phone',
                    type: 'post',
                    data: {phone: phone},
                    dataType: 'json',
                    async:false
                }).responseText);

                if (result_phone.msg == 'telefono valido') {
                    $('#phone').removeClass('input-error');
                    $('#phone').next().fadeOut();
                    return true;
                }
                else if(result_phone.err == 'telefono existe') {
                    $('#phone').addClass('input-error');
                    $('#phone').next().fadeIn();
                    $('#phone').next().html('El número de teléfono que ingresó existe, ingrese otro por favor.');
                    return false;
                } 
        }
        else{
                $('#phone').addClass('input-error');
                $('#phone').next().fadeIn();
                $('#phone').next().html('Es demasiado corto o grande (se usa 10 dígitos).');
                return false;
            }
    }

    function email(){
        var email = $('#email').val();
        if (exp_email.test(email) && email.length > 0) {
                result_email = JSON.parse( $.ajax({
                    url: 'validate/email',
                    type: 'post',
                    data: {email: email},
                    dataType: 'json',
                    async:false
                }).responseText);

                if (result_email.msg == 'email valido') {
                    $('#email').removeClass('input-error');
                    $('#email').next().fadeOut();
                    return true;
                }
                else if(result_email.err == 'email existe') {
                    $('#email').addClass('input-error');
                    $('#email').next().fadeIn();
                    $('#email').next().html('El email que ingresó existe, ingrese otro por favor.');
                    return false;
                } 
        }
        else{
                    $('#email').addClass('input-error');
                    $('#email').next().fadeIn();
                    $('#email').next().html('Ingresa un correo electrónico válido.');  
                    return false;         
        }
    }

    function code(){
        var code = $('#code').val();
        if (exp_number.test(code)) {
                result_code = JSON.parse( $.ajax({
                    url: 'validate/code',
                    type: 'post',
                    data: {code: code},
                    dataType: 'json',
                    async:false
                }).responseText);

                if (result_code.msg == 'código valido') {
                    $('#code').removeClass('input-error');
                    $('#code').next().fadeOut();
                    return true;
                }
                else if(result_code.err == 'código no valido') {
                    $('#code').addClass('input-error');
                    $('#code').next().fadeIn();
                    $('#code').next().html('El código de su referido no existe o no se puede hacer red con este código, verifiquelo por favor.');
                    return false;
                } 
        }
        else{
                    $('#code').addClass('input-error');
                    $('#code').next().fadeIn();  
                    $('#code').next().html('Ingresa el código de su referido.'); 
                    return false;            
        }
    } 

});
