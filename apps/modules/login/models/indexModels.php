<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class indexModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_main');
    }

    public function usr_sis_login($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_login');
        parent::SetParameterSP($p['usuario'], 'varchar');
        parent::SetParameterSP(sha1($p['password']), 'varchar');
        parent::SetParameterSP($p['ip'], 'varchar');
         //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function usr_sis_change_password($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_change_password');
        parent::SetParameterSP($p['usuario'], 'varchar');
        parent::SetParameterSP(sha1($p['password_old']), 'varchar');
        parent::SetParameterSP(sha1($p['password_new']), 'varchar');
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_novedad($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_nvd_status');
        parent::SetParameterSP(USR_ID, 'varchar');
        $array = parent::ExecuteSPArray();
        return $array;
    }

}
