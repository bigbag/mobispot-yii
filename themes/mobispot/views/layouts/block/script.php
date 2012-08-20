<script type="text/javascript">
    //Блок авторизации
    $(document).ready(function () {
        $('#button-auth').click(function () {
            $("#form-auth-login").show();
            $("#form-auth-pass").show();
            $("#button-auth input").show();
            $("#button-auth span").hide();
        });
    });
    //Регистрация
    $(function () {
        var count = 0;
        $('#email, #password, #verifyPassword, #code').bind('change', function(){
            count++;
        });
        $('#email, #password, #verifyPassword, #code').bind('focus', function(){
            if (count == 3){
                $('#terms').show();
            }
        });
    });


    $(document).ready(function () {
        var options = {
            success:showRegistrationResponse,
            clearForm:false,
            url:'/ajax/registration/'
        };

        $('#registration').submit(function () {
            $(this).ajaxSubmit(options);
            return false;
        });
    });

    function showRegistrationResponse(responseText) {
        if (responseText) {
            alert('<?php echo Yii::t("user", "Спасибо за регистрацию на нашем сайте, на указанную вами email была отправлено письмо с инструкцией по активации вашей учётной записи.")?>');
            $().redirect('/');
        }
        else {
            alert(0);
        }
    }
    //Вход
    $(document).ready(function () {
        var options = {
            success:showLoginResponse,
            clearForm:true,
            url:'/ajax/login/'
        };

        $('#login_form').submit(function () {
            $(this).ajaxSubmit(options);
            return false;
        });

    });

    $(document).ready(function () {
        var options = {
            success:showLoginCaptchaResponse,
            clearForm:false,
            url:'/ajax/loginCaptcha/'
        };

        $('#login_captcha_form').submit(function () {
            $(this).ajaxSubmit(options);
            return false;
        });

    });

    function showLoginResponse(responseText) {
        if (responseText == 'email_error') {
            $('#mistake-auth').show();
            $('#mistake-auth').text('<?php echo Yii::t('user', 'Пользователя с таким логином не существует.')?>');
        }
        else if (responseText == 'password_error') {
            $('#mistake-auth').show();
            $('#mistake-auth').text('<?php echo Yii::t('user', 'Пароль не верен.')?>');
        }
        else if (responseText  == 'login_error_count'){
            $('#login-captcha').modalPopLite({ openButton: '#button-auth', closeButton: '.close-btn' });
            $('#login-captcha').show();
        }
        else if (responseText) {
            $().redirect('/');
        }
    }

    function showLoginCaptchaResponse(responseText) {
        if (responseText == 1) {
            $().redirect('/');
        }
        else {
            $('#yw0_button').click();
            $('#login_captcha_form span.error').text('<?php echo Yii::t("user", "Вы не правильно заполнили поля.")?>');
        }
    }

    //Восстановление пароля
    $(function () {
        $('#recovery').modalPopLite({ openButton: '#forget-pass', closeButton: '.close-btn' });
        $('#recovery').show();
    });

    $(document).ready(function ()  {
        var options = {
            success:showRecoveryResponse,
            clearForm:true,
            url:'/ajax/recovery/'
        };

        $('#recovery_form').submit(function () {
            $(this).ajaxSubmit(options);
            return false;
        });
    });

    function showRecoveryResponse(responseText) {
        if (responseText == 1) {
            $('#recovery').hide();
            alert('<?php echo Yii::t("user", "По указанному вами адресу отправлено письмо с информацией о востановлении пароля.")?>');
            $().redirect('/');
        }
        else if (responseText == 0) {
            $('#recovery_form span').text('<?php echo Yii::t("user", "Hа сайте не зарегистрирован пользователь с таким Email.")?>');
        }
    }

    $(document).ready(function () {
        $('input.txt').bind('focus', function () {
            $(this).parent().css('background-position', '100% -105px');
            $(this).parent().parent().css('background-position', '0 -70px');
        });
        $('input.txt').bind('blur', function () {
            $(this).parent().css('background-position', '100% -35px');
            $(this).parent().parent().css('background-position', '0 0');
        });
    });

    $(document).ready(function () {
        $('#circle-menu li').click(function () {

            $(".circle-hint:visible").not($(this).children(".circle-hint")).hide();
            $(this).children(".circle-hint").toggle();
        });
    });
    jQuery('input[placeholder], textarea[placeholder]').placeholder();
</script>
