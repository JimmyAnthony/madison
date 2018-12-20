<?php

/**
 * Geekode php (http://plasmosys.com/)
 * @link    https://github.com/jbazan/geekcode_php
 * @author  Jimmy Anthony Bazán Solis @remicioluis (https://twitter.com/jbazan)
 * @version 2.0
 */

class formularioGestionModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_main');
    }

    public function get_list_formularios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_list_formulario');
        parent::SetParameterSP($p['vp_nombre'], 'varchar');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_list_formulario_componente($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_list_formulario_componente');
        parent::SetParameterSP($p['cod_form'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_formulario_detalle');
        parent::SetParameterSP($p['cod_form'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_insert_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_insert_formulario_detalle');
        parent::SetParameterSP($p['cod_form'], 'int');
        parent::SetParameterSP($p['cod_comp'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_delete_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_delete_formulario_detalle');
        parent::SetParameterSP($p['cod_form_comp'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    /*
        +----------------------+
        |    Add Valide  Set   |
        +----------------------+
    */
    public function set_update_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_update_formulario_detalle');
        parent::SetParameterSP($p['id_det'], 'int');
        parent::SetParameterSP($p['cod_form_comp'], 'int');
        parent::SetParameterSP($p['cod_form'], 'int');
        parent::SetParameterSP($p['cod_comp_estr'], 'int');
        parent::SetParameterSP(utf8_decode($p['name']), 'varchar');
        parent::SetParameterSP($p['value'], 'varchar');
        parent::SetParameterSP($p['nivel'], 'int');
        parent::SetParameterSP($p['cod_padre'], 'int');
        parent::SetParameterSP($p['estado'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_insert_only_one_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_insert_only_one_formulario_detalle');
        parent::SetParameterSP($p['cod_form_comp'], 'int');
        parent::SetParameterSP($p['cod_form'], 'int');
        parent::SetParameterSP(utf8_decode($p['name']), 'varchar');
        parent::SetParameterSP($p['value'], 'varchar');
        parent::SetParameterSP($p['cod_padre'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_insert_chk_rdb_only_one_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_insert_chk_rdb_only_one_formulario_detalle');
        parent::SetParameterSP($p['tipo'], 'varchar');
        parent::SetParameterSP($p['cod_form_comp'], 'int');
        parent::SetParameterSP($p['cod_form'], 'int');
        parent::SetParameterSP(utf8_decode($p['name']), 'varchar');
        parent::SetParameterSP($p['value'], 'varchar');
        parent::SetParameterSP($p['cod_padre'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_update_only_one_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_update_only_one_formulario_detalle');
        parent::SetParameterSP($p['id_det'], 'int');
        parent::SetParameterSP($p['cod_form_comp'], 'int');
        parent::SetParameterSP($p['cod_form'], 'int');
        parent::SetParameterSP($p['name'], 'varchar');
        parent::SetParameterSP(utf8_decode($p['value']), 'varchar');
        parent::SetParameterSP($p['cod_padre'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_delete_only_one_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_delete_only_one_formulario_detalle');
        parent::SetParameterSP($p['id_det'], 'int');
        parent::SetParameterSP($p['cod_form_comp'], 'int');
        parent::SetParameterSP($p['cod_form'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_delete_chk_rdb_only_one_formulario_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_delete_chk_rdb_only_one_formulario_detalle');
        parent::SetParameterSP($p['id_det'], 'int');
        parent::SetParameterSP($p['cod_form_comp'], 'int');
        parent::SetParameterSP($p['cod_form'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_formulario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'set_formulario');
        parent::SetParameterSP($p['vp_op'], 'varchar');
        parent::SetParameterSP($p['cod_form'], 'int');
        parent::SetParameterSP(utf8_decode($p['nombre']), 'varchar');
        parent::SetParameterSP(utf8_decode($p['descripcion']), 'varchar');
        parent::SetParameterSP($p['estado'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function get_list_componente($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_list_componente');
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
