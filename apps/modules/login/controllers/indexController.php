<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class indexController extends AppController {

    private $objDatos;
    private $arrayMenu;

    function __construct(){
        $this->objDatos = new indexModels();
    }

    public function index($p){
        /**
         * Cargando datos de archivo de configuracion
         */
        
        if (!isset($p['error']))
            $p['error'] = 0;

        $this->view('index/form_index.php', $p);
    }

    /**
     * Valida el inicio de session.
     */
    public function valida($p){
        $p['ip'] = Common::get_Ip();
        $rs = $this->objDatos->usr_sis_login($p);
        $rs = $rs[0];
        if (intval($rs['sql_error']) >= 0 ){
            $this->set_session($rs);
        }else{
            $p['sql_error'] = intval($rs['sql_error']);
            $p['msn_error'] = trim($rs['msn_error']);
            $this->view('index/form_logout.php', $p);
        }
    }

    /**
     * Se encarga de validar y almacenar las variables del inicio de session. 
     */
    public function set_session($rs){
        session_start();

        $_SESSION['timeout'] = time();
        $_SESSION['sql_error'] = intval($rs['sql_error']);
        $_SESSION['msn_error'] = trim($rs['msn_error']);
        $_SESSION['id_user'] = intval($rs['id_user']);
        $_SESSION['usuario'] = trim($rs['usuario']);
        $_SESSION['nombre'] = trim($rs['nombre']);
        $_SESSION['usr_tipo'] = trim($rs['usr_tipo']);
        $_SESSION['prov_codigo'] = intval($rs['prov_codigo']);
        $_SESSION['prov_nombre'] = trim($rs['prov_nombre']);
        $_SESSION['prov_sigla'] = trim($rs['prov_sigla']);
        $_SESSION['per_id'] = intval($rs['per_id']);
        $_SESSION['perfil'] = intval($rs['perfil']);
        $_SESSION['id_remitente'] = intval($rs['id_remitente']);
        $_SESSION['sis_id'] = intval($rs['sis_id']);
        $_SESSION['time_session'] = intval($rs['time_session']);
        $_SESSION['shi_codigo'] = intval($rs['shi_codigo']);

        header('Location: /inicio/index/');
    }

    public function cambiar_password($p){
        /**
         * Librería para la generación de imágenes de validación captcha.
         */
        require_once PATH . 'libs/recaptcha-php-1.11/recaptchalib.php';

        $p['publickey'] = GOOGLE_CAPTCHA_PUBLICKEY;
        $p['privatekey'] = GOOGLE_CAPTCHA_PRIVATEKEY;

        if (!isset($p['error']))
            $p['error'] = 0;

        $this->view('index/form_cambiar_password.php', $p);
    }

    /**
     * Se encarga de actualizar la contraseña del usuario.
     */
    public function update_pwd($p){
        require_once PATH . 'libs/recaptcha-php-1.11/recaptchalib.php';
        
        $publickey = GOOGLE_CAPTCHA_PUBLICKEY;
        $privatekey = GOOGLE_CAPTCHA_PRIVATEKEY;

        $resp = recaptcha_check_answer(
            $privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]
        );

        $p['path'] = '/login/index/cambiar_password/';
        // $resp->is_valid = true;
        if ($resp->is_valid){
            $rs = $this->objDatos->usr_sis_change_password($p);
            $rs = $rs[0];
            $p['sql_error'] = intval($rs['sql_error']);
            $p['msn_error'] = trim($rs['err_info']);
        }else{
            $p['sql_error'] = -999;
            $p['msn_error'] = trim($resp->error);
        }
        $this->view('index/form_error.php', $p);
    }

    public function status_session(){
        $data = $this->status();
        $rs = $this->objDatos->get_novedad($p);
        $data['novedad']=$rs[0]['novedad'];
        $data['msn_id']=$rs[0]['msn_id'];
        return $this->response($data);
    }

}