<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

if (!class_exists("Adodb")){
    require_once PATH . 'libs/adodb/Adodb.php';
}
if (!class_exists("Common")){
    require_once PATH . 'libs/Common.php';
}

class Sms extends Adodb {

    private $web_user = 'admin';
    private $web_pass = 'admin';
    private $lyric_ip = '192.168.41.15';
    private $api_user = 'lyric_api';
    private $api_pass = 'admini';
    private $api_version = '0.08';
    private $url = '';
    private $data = array();
    private $context = null;
    private $estado = -1;
    private $message_id = -1;
    private $msg = '';
    private $destination = '';
    private $guia = 0;

    private $dsn;

    function __construct($msg, $destination, $gui_numero){
        $this->msg = $this->string_replace($msg);
        $this->destination = $destination;
        $this->guia = $gui_numero;
        $this->url = "http://" . $this->web_user . ":" . $this->web_pass . "@" . $this->lyric_ip . "/cgi-bin/exec";
        $this->setData();
        $this->setContext();

        $this->dsn = Common::read_ini(PATH . 'config/config.ini', 'server_pcll');

    }

    public function string_replace($msg){
        $search = array('á','é','í','ó','ú','Ã­');
        $replace = array('a','e','i','o','u','i');
        return str_replace($search, $replace, $msg);
    }

    public function get_msg(){
        return $this->msg;
    }

    public function get_destination(){
        return $this->destination;
    }

    public function get_guia(){
        return $this->guia;
    }

    public function setData($cmd = 'api_queue_sms'){
        if ($cmd == 'api_queue_sms'){
            $this->data = array(
                'cmd' => $cmd, 
                'username' => $this->api_user, 
                'password' => $this->api_pass, 
                'content' => $this->msg, 
                'destination' => $this->destination, 
                'api_version' => $this->api_version
            );
        }else{
            $this->data = array(
                'cmd' => $cmd,
                'message_id' => $this->message_id,
                'username' => $this->api_user, 
                'password' => $this->api_pass,
                'api_version' => $this->api_version
            );
        }        
    }

    public function setContext(){
        $postdata = http_build_query($this->data);
        $headers = array(
            'http' => array(
                'method' => "POST",
                'header' => "Content-type: application/x-www-form-urlencoded" . "\r\n" .
                            "user: \r\n" .
                            "pass: \r\n" ,
                'content' => $postdata
            )
        );
        $this->context = stream_context_create($headers);
    }

    public function send(){
        $res = file_get_contents($this->url, false, $this->context);
        $res = json_decode($res, true);
        if ($res['success']){
            $this->estado = 0;
            $this->message_id = $res['message_id'];
            echo 'Mensaje insertado exitosamente. Ticket: ' . $this->message_id . '</br>';
        }else{
            $this->estado = -1;
            echo 'Error al insertar mensaje. Codigo de error: ' . $res['error_code'] . '</br>';
        }
    }

    public function get_status(){
        if ($this->estado == -1 || $this->estado >= 2)
            return $this->estado;

        $this->setData('api_get_status');
        $this->setContext();
        $res = file_get_contents($this->url, false, $this->context);
        $res = json_decode($res, true);

        if ($res['success']){
            $this->estado = $res['message_status'];
            if (intval($this->estado) == 2){
                echo 'Ticket: ' . $this->message_id . ' Estado: ' . $this->estado . '</br>';
                $this->setDataLog();
            }
            return $res['message_status'];
        }else{
            echo 'Error al consultar estado. Codigo de error: ' . $res['error_code'] . '</br>';
            return -1;
        }
    }

    public function setDataLog(){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'pcl_envio_sms_log');
        parent::SetParameterSP($this->get_guia(), 'int');
        parent::SetParameterSP($this->get_msg(), 'varchar');
        parent::SetParameterSP($this->get_destination(), 'varchar');
        parent::SetParameterSP('ER', 'varchar');
        parent::SetParameterSP(1, 'int');
        // echo parent::getSql(); die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

}