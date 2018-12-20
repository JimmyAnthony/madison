<?php

/**
 * Geekode php (http://plasmosys.com/)
 * @link    https://github.com/jbazan/geekcode_php
 * @author  Jimmy Anthony Bazán Solis @remicioluis (https://twitter.com/jbazan)
 * @version 2.0
 */

class campanaFormularioModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_main');
    }

    public function get_campana_formulario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_campana_formulario');
        parent::SetParameterSP($p['vp_cod_camp'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_list_formulario_no_incluido($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_list_formulario_no_incluido');
        parent::SetParameterSP($p['vp_nombre'], 'varchar');
        parent::SetParameterSP($p['vp_cod_camp'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function set_insert_delete_relacion_camp_form($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_insert_delete_relacion_camp_form');
        parent::SetParameterSP($p['vp_op'], 'varchar');
        parent::SetParameterSP($p['cod_camp'], 'int');
        parent::SetParameterSP($p['cod_form'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
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
