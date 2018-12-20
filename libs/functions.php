<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

/**
 * Se encarga de verificar las url y parametros enviados.
 */
function get_url() {
    $parametros = array();
    $url = parse_url($_SERVER['REQUEST_URI']);
    foreach (explode("/", $url['path']) as $p)
        if ($p != '')
            $parametros[] = $p;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $request = $_POST;
            break;
        case 'GET':
            $request = $_GET;
            break;
    }
    if (count($request) > 0) {
        foreach ($request as $index => $value) {
            switch(gettype($value)){
                case 'string': $value = trim($value); break;
            }
            $parametros[$index] = $value;
        }
    }
    return $parametros;
}

/**
 * Obtiene de forma dinamica el path principal del sistema.
 */
function getPath() {
    $ruta = realpath(dirname(__FILE__));
    if ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
        $separator = '\\';
    else
        $separator = '/';
    $aRuta = explode($separator,$ruta);
    $ruta = '';
    foreach($aRuta as $index => $value)
        if ( $index < count($aRuta) - 1 ) $ruta .= $value.$separator;
    return $ruta;
}
