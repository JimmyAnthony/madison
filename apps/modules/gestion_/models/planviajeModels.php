<?php

/**
  * @author  Robert Salvatierra (robertsalvatierraq@gmail.com)
 */

class planviajeModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm');
    }

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias');
        parent::SetParameterSP('1' , 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }



                     
}
