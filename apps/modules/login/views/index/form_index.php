<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Madison</title>
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="/css/login_bootstrap.css">
</head>
<body>
    <div class="error-msg hide"><span class="info-icon icon-warning"></span><span class="texto-error">Error</span></div>
    <div class="contenedor_login">
        <div class="contenedor_login_in">
            <header>
                <div class="logo"></div>
                <div class="welcome">Bienvenido</div>
            </header>
            <section>
                <span class="ingrese">Ingrese sus datos de acceso:</span>
                <form action="/login/index/valida" id="formulario_login" name="formulario_login" method="POST" accept-charset="utf-8">
                <p>
                    <span class="label">Usuario:</span>
                    <input type="text" class="input" placeholder="Ingrese usuario" autocomplete="off" name="usuario" id="usuario" required>
                </p>
                <p>
                    <span class="label">Contraseña:</span>
                    <input type="password" class="input" placeholder="Ingrese contraseña" autocomplete="off" name="password" required>
                </p>
                <p class="centrar">
                    <a href="/login/index/cambiar_password/" class="cambiar_password">Cambiar contraseña</a>
                </p>
                <p>
                    <button type="submit" class="button">Ingresar</button>
                </p>
                </form>
            </section>
            <footer>
                <p>
                    <span>Copyright&copy; Madison Perú</span>
                    <span>Todos los derechos reservados</span>
                </p>
            </footer>
        </div>
    </div>


    <script type="text/javascript" src="/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/js/modernizr.js"></script>
    <script type="text/javascript">
        var login = {
            id: 'login',
            error: parseInt('<?php echo $p["error"];?>'),
            init: function(){
                $('#usuario').focus();
                $('.error-msg').click(function(){
                    $('.error-msg').slideDown('fast').delay(100).fadeOut(400);
                });

                if (login.error < 0){
                    $( ".texto-error" ).text('<?php echo $p["msn_error"];?>');
                    $( ".error-msg" ).slideDown('slow', function(){});
                }

            }
        }
        $(document).ready(function(){
            login.init();
        });
    </script>
</body>
</html>