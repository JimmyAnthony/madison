<?php

/**
 * Geekode php (http://plasmosys.com/)
 * @link    https://github.com/jbazan/geekcode_php
 * @author  Jimmy Anthony Bazán Solis @remicioluis (https://twitter.com/jbazan)
 * @version 2.0
 */

class shipperModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_main');
    }

    public function get_list_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_sis_list_shipper');
        parent::SetParameterSP($p['id_user'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_list_campana_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_list_campana_shipper');
        parent::SetParameterSP($p['shi_codigo'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function setRegisterShipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_shipper');
        parent::SetParameterSP($p['vp_op'], 'varchar');
        parent::SetParameterSP($p['vp_shi_codigo'], 'varchar');
        parent::SetParameterSP($p['vp_shi_nombre'], 'varchar');
        parent::SetParameterSP($p['vp_fec_ingreso'], 'varchar');
        parent::SetParameterSP($p['vp_shi_logo'], 'varchar');
        parent::SetParameterSP($p['vp_estado'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
}
