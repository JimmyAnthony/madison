<?php
//require_once PATH . 'libs/adodb/Adodb_Zend.php';

class salidaRutaModels extends Adodb{
	private $dsn;
    private $db;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm');
    }

    
    

}