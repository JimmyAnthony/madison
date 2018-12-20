<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

/**
 * Clase para autenticar al usuario.
 */
class Auth {

    public function __construct() {
        session_start();

        /**
         * Definiendo variables de session que se utilizaran en todo el sistema.
         */
        define(USR_ID, $_SESSION['id_user']);
        define(SIS_ID, $_SESSION['sis_id']);
        define(USR_LOGIN, $_SESSION['usuario']);
        define(USR_NOMBRE, $_SESSION['nombre']);
        define(USR_TIPO, $_SESSION['usr_tipo']);
        define(PROV_CODIGO, $_SESSION['prov_codigo']);
        define(PROV_NOMBRE, $_SESSION['prov_nombre']);
        define(PROV_SIGLA, $_SESSION['prov_sigla']);
        define(PER_ID, $_SESSION['per_id']);
        define(PERFIL_ID, $_SESSION['perfil']);
        define(REMITENTE_ID, $_SESSION['id_remitente']);
        define(SHI_CODIGO, $_SESSION['shi_codigo']);

    }

    /**
     * Validando el estado de la session
     */
    public function valida($p='') {
    	$inactivo = 60 * $_SESSION['time_session'];
        // $inactivo = 10;
        if (isset($_SESSION["timeout"])) {
            $tiempoSession = time() - $_SESSION["timeout"];            
            if ($tiempoSession > $inactivo) {
                session_destroy();

                $p['sql_error'] = -2;
                $p['msn_error'] = 'Tiempo de session expirado!';
                require_once PATH . 'apps/modules/login/views/index/form_logout.php';
            }
        }
        $_SESSION["timeout"] = time();

        if (!isset($_SESSION['id_user'])){
            header("Location: /");
            exit();
        }
    }

    /**
     * Liberando session de usuario
     */
    public function expire($p) {
        session_start();

        session_destroy();
    }

    public function status(){
        $inactivo = 60 * $_SESSION['time_session'];
        $a = array('time' => 0);
        if (isset($_SESSION["timeout"])) {
            $tiempoSession = time() - $_SESSION["timeout"];
            if ($tiempoSession > $inactivo) {
                session_destroy();
                $a = array('time' => 0);
            }else
                $a = array('time' => $tiempoSession == 0 ? 1 : $tiempoSession);
        }else
            $a = array('time' => 0);
        return $a;
    }

}
