<!DOCTYPE html>
<html lang="es">
    <head>
        <title>.:Zucuba:.</title>
        <meta charset="utf-8">
    </head>
    <body onload="document.form.submit();">
        <form name="form" action="/" method="POST">
            <input type="hidden" name="error" value="<?php echo $p['sql_error'];?>" />
            <input type="hidden" name="msn_error" value="<?php echo $p['msn_error'];?>" />
        </form>
    </body>
</html>