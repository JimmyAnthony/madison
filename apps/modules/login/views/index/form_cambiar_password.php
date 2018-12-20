<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Zucuba</title>
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="/css/loginChange_bootstrap.css">
    <script type="text/javascript">
        var RecaptchaOptions = {
            lang : 'es',
            theme : 'white'
         };
    </script>
</head>
<body>
    <div class="contenedor_header">
        <header class="Header">
            <!--<div class="logo"></div>-->
        </header>
    </div>
    <section class="contenido">
        <div class="error-msg hide"><span class="info-icon icon-warning"></span><span class="texto-error">Error</span></div>
        <form action="/login/index/update_pwd/" id="formulario_login" name="formulario_login" method="POST" accept-charset="utf-8" autocomplete="off">
            <h1 class="title">Actualización de contraseña</h1>
            <span class="ingrese">Ingrese los datos de solicitados:</span>
            <p>
                <span class="label">Usuario:</span>
                <input type="text" class="input" placeholder="Ingrese usuario" autocomplete="off" name="usuario" id="usuario" required>
            </p>
            <p>
                <span class="label">Contraseña Actual:</span>
                <input type="password" class="input" name="password_old" placeholder="Ingrese contraseña actual" autocomplete="off" required>
            </p>
            <p>
                <span class="label">Nueva Contraseña:</span>
                <input type="password" class="input" name="password_new" id="password_new" placeholder="Ingrese nueva contraseña" autocomplete="off" required>
            </p>
            <div class="pass_meter">
                <div id="scorebarBorder">
                    <div id="score">0%</div>
                    <div id="scorebar">&nbsp;</div>
                </div>
            </div>
            <p>
                <span class="label">Repetir Contraseña:</span>
                <input type="password" class="input" name="password_new_rpt" id="password_new_rpt" placeholder="Repetir contraseña" autocomplete="off" required>
            </p>
            <p class="centrado">
                <div class="captcha"><?php echo recaptcha_get_html($p['publickey'], null);?></div>
            </p>
            <p>
                <button type="submit" class="button">Actualizar</button>
                <button type="submit" class="button" onclick="javascript:window.location='/'">Retornar</button>
            </p>
        </form>
    </section>
    <footer class="Footer">
        <div class="sello_izq"></div>
        <div class="creditos">
            <p>
                <span>Copyright&copy; Zucuba Perú</span>
                <span>Todos los derechos reservados</span>
            </p>
        </div>
        <div class="sello_der"></div>
    </footer>

    <script type="text/javascript" src="/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/js/passwordmeter/js/pwdmeter.js"></script>
    <script type="text/javascript" src="/js/modernizr.js"></script>
    <script type="text/javascript">
        var update_pwd = {
            id: 'update_pwd',
            error: parseInt('<?php echo $p["error"];?>'),
            init: function(){
                $('#usuario').focus();
                $('.error-msg').click(function(){
                    $('.error-msg').slideDown('fast').delay(100).fadeOut(400);
                });

                if (update_pwd.error < 0){
                    $( ".texto-error" ).text('<?php echo $p["msn_error"];?>');
                    $( ".error-msg" ).slideDown('slow', function(){});
                }else if(update_pwd.error != 0){
                    alert('<?php echo $p["msn_error"];?>' + '</br>Se rediccionará automáticamente!!!');
                    window.location = '/';
                }

                $( "#password_new" ).change(function() {
                    if ($('#password_new_rpt').val().trim() != ''){
                        if ($('#password_new').val().trim() != $('#password_new_rpt').val().trim()){
                            alert('Las contraseñas no coinciden!');
                            alert('Las contraseñas no coinciden!');
                        }
                    }
                });

                $( "#password_new_rpt" ).change(function() {
                    if ($('#password_new').val().trim() != ''){
                        if ($('#password_new').val().trim() != $('#password_new_rpt').val().trim()){
                            alert('Las contraseñas no coinciden!');
                            alert('Las contraseñas no coinciden!');
                        }
                    }
                });

                $("#password_new").keyup(function() {
                    chkPass($("#password_new").val().trim());
                });
            }
        }
        $(document).ready(function(){
            update_pwd.init();
        });
    </script>
</body>
</html>