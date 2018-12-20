<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://www.zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

/**
 * Configuracion para carga dinamica de librerias de Zend Framework
 */
require_once 'config-zf.php';


require_once 'vendor/autoload.php';

/**
 * Funciones comunes del sistemas (funciones globales)
 */
require_once 'functions.php';

/**
 * Clases de utilitarios
 */
require_once 'Common.php';

/**
 * Clase para carga dinámica de menu de opciones
 */
require_once 'Menu.php';

/**
 * Definicion de Path global del framework
 */
define('PATH', getPath());

/**
 * Obteniendo datos del archivo de configuracion
 */
$_CONF = new Zend_Config_Ini(PATH . 'config/config.ini', 'server_config');

/**
 * Definiendo variables globales
 */
define('DEVELOPMENT', $_CONF->app->development);
define('GOOGLE_CAPTCHA_PUBLICKEY', trim($_CONF->google->captcha->publickey));
define('GOOGLE_CAPTCHA_PRIVATEKEY', trim($_CONF->google->captcha->privatekey));

/**
 * Obteniendo url
 */
$paramUrl = get_url();

/**
 * Incluyendo clase manejadora de base de datos
 */
require_once PATH . 'libs/adodb/Adodb.php';

/**
 * Path de los modulos del sistema
 */
define('APPPATH', PATH . 'apps/modules/');

/**
 * Obteniendo nombre del modulo-paquete
 */
$module = trim($paramUrl[0]) != '' ? $paramUrl[0] : $_CONF->web->module;
define('APPNAME_MODULE', $module);

/**
 * Definiendo nombre del archivo controlador
 */
$controller = trim($paramUrl[1]) != '' ? $paramUrl[1] : $_CONF->web->controller;
define('APPNAME_CONTROLLER', $controller);

/**
 * Definiendo path de carpetas del sistema
 */
define('APPPATH_MODULE', APPPATH . APPNAME_MODULE . '/controllers/');
define('APPPATH_MODEL', APPPATH . APPNAME_MODULE . '/models/');
define('APPPATH_VIEW', APPPATH . APPNAME_MODULE . '/views/');

/**
 * Definiendo acciones
 */
$controllerClass = APPNAME_CONTROLLER . 'Controller';
$modelsClass = APPNAME_CONTROLLER . 'Models';

/**
 * Definiendo files del sistema
 */
define('APPFILE_MODULE', APPPATH_MODULE . $controllerClass . '.php');
define('APPFILE_MODEL', APPPATH_MODEL . $modelsClass . '.php');

/**
 * Manejador de vistas
 */
require_once PATH.'libs/AppController.php';

try {
    $controllerMethod = $paramUrl[2];
    if (trim($controllerMethod) == '')
        $controllerMethod = 'index';
    if (!file_exists(APPFILE_MODULE)) {
        throw new Exception('The page you request was not found.', 404);
    }else {
        if (file_exists(APPFILE_MODEL))
            require_once APPFILE_MODEL;
        require_once APPFILE_MODULE;
        if (!class_exists($controllerClass)) {
            throw new Exception('The controller you request was not found.', 404);
        } else {
            $classModule = new $controllerClass();
            if (!method_exists($classModule, $controllerMethod)) {
                throw new Exception('The method you request was not found.', 404);
            } else {
                echo $classModule -> $controllerMethod($paramUrl);
            }
        }
    }   
} catch (Exception $e) {
    require_once PATH . 'public_html/template/error.php';
}
