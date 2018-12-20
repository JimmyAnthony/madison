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
        /*if((int)$_REQUEST['id_user']==8 || (int)$_REQUEST['id_user']==9 || trim($_REQUEST['usuario'])=="mobil" || trim($_REQUEST['usuario'])=="urbano.mobil" || trim($_REQUEST['usuario'])=="mifarma3"){
            $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm');
        }else{
            $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
        }*/
    }

    public function usr_sis_login($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_login');
        parent::SetParameterSP($p['usuario'], 'varchar');
        parent::SetParameterSP(sha1($p['password']), 'varchar');
        parent::SetParameterSP($p['ip'], 'varchar');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function usr_sis_register_mac($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_register_mac');
        parent::SetParameterSP(substr($p['codigo'], 0,5), 'varchar');
        parent::SetParameterSP($p['ip'], 'varchar');
         //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function usr_sis_login_mac($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'zc_mobile_login_mac');
        parent::SetParameterSP($p['id_user'], 'varchar');
        parent::SetParameterSP($p['id_mac'], 'varchar');
         //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_mobile_gps_demonio($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_gps_demonio');
        parent::SetParameterSP($p['id_user'], 'int');
        parent::SetParameterSP($p['pos_px'], 'varchar');
        parent::SetParameterSP($p['pos_py'], 'varchar');
        parent::SetParameterSP($p['fecha'], 'varchar');
        parent::SetParameterSP($p['hora'], 'varchar');
        parent::SetParameterSP($p['id_mac'], 'varchar');
        parent::SetParameterSP($p['bateria'], 'varchar');
        parent::SetParameterSP($p['accuracy'], 'varchar');
         //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_mobile_agencias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_agencias');
        parent::SetParameterSP($p['id_user'], 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_mobile_upd_time_gps($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_upd_time_gps');
        parent::SetParameterSP($p['id_user'], 'int');
        parent::SetParameterSP($p['id_mac'], 'varchar');
        parent::SetParameterSP($p['id_agencia'], 'int');
        parent::SetParameterSP($p['hora'], 'varchar');
        parent::SetParameterSP($p['dur_value'], 'varchar');
        parent::SetParameterSP($p['dur_text'], 'varchar');
        parent::SetParameterSP($p['dis_value'], 'varchar');
        parent::SetParameterSP($p['dis_text'], 'varchar');
         //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_CuadroMotivosView($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_list_chk');
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_servicio($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_ruta');
        parent::SetParameterSP($p['id_user'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_servicio($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_sincroniza');
        parent::SetParameterSP($p['vp_id_servicio'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        parent::SetParameterSP($p['vp_hora'], 'varchar');
        parent::SetParameterSP($p['id_mac'], 'varchar');

        parent::SetParameterSP($p['vp_secuencia'], 'int');
        parent::SetParameterSP($p['dur_value'], 'varchar');
        parent::SetParameterSP($p['dis_value'], 'varchar');
        parent::SetParameterSP(date("d/m/Y"), 'varchar');
        parent::SetParameterSP(date("H:i:s"), 'varchar');
        parent::SetParameterSP($p['id_user'], 'int');
        //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_novedad($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_nvd_status');
        parent::SetParameterSP($p['id_user'], 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }
    /**
     * Se encarga de marcar el ultimo sistema utilizado
     * para que al siguiente logueo se cargue ese por default.
     */
    public function usr_sis_change_first_sistema($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_change_first_sistema');
        parent::SetParameterSP($p['id_user'], 'int');
        parent::SetParameterSP($p['sis_id'], 'int');
        parent::SetParameterSP(Common::get_Ip(), 'varchar');
        $array = parent::ExecuteSPArray();
        return $array;
    }

    /**
     * Obtiene el listado de menus al que se tiene permiso
     * por usuario y sistema
     */
    public function usr_sis_menus($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_menus');//zc_mobile_menu
        parent::SetParameterSP($p['id_user'], 'int');
        parent::SetParameterSP($p['sis_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray(array('sql_error', 'msn_error'));
        return $array;
    }

    /**
     * Carga la lista de servicios a los cuales se tenga permiso
     * Permisos por botones
     */
    public function usr_sis_servicios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_servicios');
        parent::SetParameterSP($p['id_user'], 'int');
        parent::SetParameterSP($p['sis_id'], 'int');
        parent::SetParameterSP($p['vp_mod_id'], 'int');
        parent::SetParameterSP($p['vp_menu_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray(array('sql_error', 'msn_error'));
        return $array;
    }
    public function set_scm_mobile_upd_descarga($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_upd_descarga');
        parent::SetParameterSP($p['vp_id_servicio'], 'int');
        parent::SetParameterSP($p['vp_mot_id'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        parent::SetParameterSP($p['vp_hora'], 'varchar');
        parent::SetParameterSP($p['vp_geo_px'], 'varchar');
        parent::SetParameterSP($p['vp_geo_py'], 'varchar');
        parent::SetParameterSP($p['vp_nombre'], 'varchar');
        parent::SetParameterSP($p['vp_nro_dni'], 'varchar');
        parent::SetParameterSP($p['vp_num_pos'], 'varchar');
        parent::SetParameterSP($p['vp_piezas'], 'varchar');
        parent::SetParameterSP($p['vp_peso'], 'varchar');
        parent::SetParameterSP($p['vp_recolectado'], 'varchar');
        parent::SetParameterSP($p['vp_descripcion'], 'varchar');
        parent::SetParameterSP($p['id_user'], 'int');
        parent::SetParameterSP($p['id_mac'], 'varchar');
        //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_mobile_upd_imagen($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_upd_imagen');
        parent::SetParameterSP($p['id_servicio'], 'int');
        parent::SetParameterSP($p['imagen'], 'varchar');
        parent::SetParameterSP($p['fecha'], 'varchar');
        parent::SetParameterSP($p['hora'], 'varchar');
        parent::SetParameterSP($p['geo_px'], 'varchar');
        parent::SetParameterSP($p['geo_py'], 'varchar');
        parent::SetParameterSP($p['id_user'], 'int');
        //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }


    public function get_list_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_ext_list_shipper');
        parent::SetParameterSP($p['id_user'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_ext_list_campana_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_ext_list_campana_shipper');
        parent::SetParameterSP($p['id_user'], 'int');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_list_pasos_formulario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_list_pasos_formulario');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    
    public function get_list_component($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_list_component');
        // echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_list_component_estructura($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_list_component_estructura');
        parent::SetParameterSP($p['cod_comp'], 'int');
        parent::SetParameterSP($p['cod_padre'], 'int');
         //echo '=>' . parent::getSql().'<br>'; //exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_campana_formulario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_campana_formulario');
        parent::SetParameterSP($p['vp_cod_camp'], 'int');
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
    public function get_formulario_detalle_compomente($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'get_formulario_detalle_compomente');
        parent::SetParameterSP($p['cod_form'], 'int');
        parent::SetParameterSP($p['cod_form_comp'], 'int');
        parent::SetParameterSP($p['cod_padre'], 'int');
         //echo '=>' . parent::getSql().'<br>'; //exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
}