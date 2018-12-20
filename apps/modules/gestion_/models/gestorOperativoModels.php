<?php
require_once PATH . 'libs/adodb/Adodb_Zend.php';

class gestorOperativoModels extends Adodb{
	private $dsn;
    private $db;

    public function __construct(){
        $this->dsn_scm = Common::read_ini(PATH.'config/config.ini', 'server_scm');
    }

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'usr_sis_provincias');
        parent::SetParameterSP($p['linea'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_unidades($p){
        $p['vp_prov_codigo']=(empty($p['vp_prov_codigo']))?PROV_CODIGO:$p['vp_prov_codigo'];
        $p['vp_fecha']=(empty($p['vp_fecha']))?date('d/m/Y'):$p['vp_fecha'];
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_unidades');
        parent::SetParameterSP(trim($p['vp_prov_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_servicios($p){
        $p['vp_agencia']=(empty($p['vp_agencia']))?PROV_CODIGO:$p['vp_agencia'];
        $p['vp_fecha']=(empty($p['vp_fecha']))?date('d/m/Y'):$p['vp_fecha'];
        $p['vp_estado']=(empty($p['vp_estado']))?'SS':$p['vp_estado'];
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_list_servicios');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP(trim($p['vp_estado']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_lista_ruta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_lista_ruta');
        parent::SetParameterSP(trim($p['vp_prov_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_man_id']), 'int');
        //parent::SetParameterSP(trim($p['vp_estado']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_lista_servicios($p){
        $p['vp_prov_codigo'] = (empty($p['vp_prov_codigo']))?PROV_CODIGO:$p['vp_prov_codigo'];
        $p['vp_fecha']=(empty($p['vp_fecha']))?date('d/m/Y'):$p['vp_fecha'];
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_lista_servicios');
        parent::SetParameterSP(trim($p['vp_prov_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_datos_servicios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_datos_servicios');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function get_scm_dispatcher_horarios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_horarios');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_add_ruta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_add_ruta');
        parent::SetParameterSP($p['vp_agencia'] , 'int');
        parent::SetParameterSP($p['vp_man_id'] , 'int');
        parent::SetParameterSP($p['vp_srec_id'] , 'int');
        parent::SetParameterSP($p['vp_per_m'] , 'int');
        //parent::SetParameterSP($p['vp_per_id_h'] , 'int');
        parent::SetParameterSP($p['vp_und_id'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 
    public function get_scm_contactos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_lista_contactos');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        parent::SetParameterSP($p['vp_id_agencia'], 'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_unidades($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_lista_contactos');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        parent::SetParameterSP($p['vp_id_agencia'], 'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_delivery_unidad_gps_distance($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_home_delivery_unidad_gps_distance');
        parent::SetParameterSP(trim($p['vp_prov_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_id_agencia']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_usr_sis_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'usr_sis_personal');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(22, 'int');
        parent::SetParameterSP(0, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_upd_programacion ($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_upd_programacion');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['vp_fecini']), 'varchar');
        parent::SetParameterSP(trim($p['vp_horini']), 'varchar');
        parent::SetParameterSP(trim($p['vp_fecfin']), 'varchar');
        parent::SetParameterSP(trim($p['vp_horfin']), 'varchar');
        parent::SetParameterSP(trim($p['vp_semana']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
       // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function get_CuadroMotivos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_mobile_list_chk');
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_mobile_upd_descarga($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_mobile_upd_descarga');
        parent::SetParameterSP($p['vp_id_servicio'], 'int');
        parent::SetParameterSP($p['vp_mot_id'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        parent::SetParameterSP($p['vp_hora'], 'varchar');
        parent::SetParameterSP($p['vp_geo_px'], 'varchar');
        parent::SetParameterSP($p['vp_geo_py'], 'varchar');
        parent::SetParameterSP($p['vp_nombre'], 'varchar');
        parent::SetParameterSP($p['vp_nro_dni'], 'varchar');
        parent::SetParameterSP($p['id_user'], 'int');
        //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function scm_home_delivery_upd_destino($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_home_delivery_upd_destino');
        parent::SetParameterSP(trim($p['vp_gui_num']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_id']), 'int');
        parent::SetParameterSP(trim($p['vp_ciu_id']), 'int');
        parent::SetParameterSP(trim($p['vp_id_geo']), 'int');
        parent::SetParameterSP(trim($p['vp_via_id']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_calle']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_numvia']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_refere']), 'char');
        parent::SetParameterSP(trim($p['vp_urb_id']), 'int');
        parent::SetParameterSP(trim($p['vp_urb_nom']), 'char');
        parent::SetParameterSP(trim($p['vp_mz_id']), 'int');
        parent::SetParameterSP(trim($p['vp_mz_nom']), 'char');
        parent::SetParameterSP(trim($p['vp_num_lote']), 'char');
        parent::SetParameterSP(trim($p['vp_num_int']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_px']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_py']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }     
}
?>