<?php

/**
 * Geekode php (http://plasmosys.com/)
 * @link    https://github.com/jbazan/geekcode_php
 * @author  Jimmy Anthony Bazán Solis @remicioluis (https://twitter.com/jbazan)
 * @version 2.0
 */

class campanaModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_main');
    }

    public function get_sis_list_campana($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_sis_list_campana');
        parent::SetParameterSP($p['nombre'], 'varchar');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_sis_list_shipper_campana($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_sis_list_shipper_campana');
        parent::SetParameterSP($p['campana'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function setRegisterShipper($p){
        $p['vp_shi_codigo'] =(empty($p['vp_shi_codigo']))?0:$p['vp_shi_codigo'];
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_campana');
        parent::SetParameterSP($p['vp_op'], 'varchar');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        parent::SetParameterSP($p['vp_shi_nombre'], 'varchar');
        parent::SetParameterSP($p['vp_shi_descripcion'], 'varchar');
        parent::SetParameterSP($p['vp_fec_ingreso'], 'varchar');
        parent::SetParameterSP($p['vp_shi_logo'], 'varchar');
        parent::SetParameterSP($p['vp_estado'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
         //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
}
