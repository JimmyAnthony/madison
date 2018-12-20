<?php
require_once PATH . 'libs/adodb/Adodb_Zend.php';

class recoleccionesModels extends Adodb{
	private $dsn;
    private $db;

    public function __construct(){
        $this->dsn_scm = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }
    public function get_scm_lista_requerimientos($p){
        $p['vp_shi_codigo']=(empty($p['vp_shi_codigo']))?0:trim($p['vp_shi_codigo']);
        $p['vp_estado']=(empty($p['vp_estado']))?'%%':trim($p['vp_estado']);
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_list_solicitud');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP(trim($p['vp_estado']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_add_upd_orden ($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_add_upd_orden');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['vp_shi_cod']), 'int');
        parent::SetParameterSP(trim($p['vp_linea']), 'int');
        parent::SetParameterSP(trim($p['vp_sentido_ruta']), 'varchar');
        parent::SetParameterSP(trim($p['vp_tipo_srec']), 'int');
        parent::SetParameterSP(trim($p['vp_tipo_pqt']), 'int');
        parent::SetParameterSP(trim($p['vp_cantidad']), 'int');
        parent::SetParameterSP(trim($p['vp_peso']), 'int');
        parent::SetParameterSP(trim($p['vp_alto']), 'int');
        parent::SetParameterSP(trim($p['vp_ancho']), 'int');
        parent::SetParameterSP(trim($p['vp_largo']), 'int');
        parent::SetParameterSP(trim($p['vp_descri']), 'varchar');
        parent::SetParameterSP(trim($p['vp_observ']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function usr_sis_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'usr_sis_shipper');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_linea($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_linea_negocio');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_tabla_detalle');
        parent::SetParameterSP($p['tab_id'] , 'char');
        parent::SetParameterSP(0, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function get_scm_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_provincias_geo');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_area($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_area');
        parent::SetParameterSP(0,'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_personal');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_cargo'],'int');
        parent::SetParameterSP($p['vp_id_area'], 'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_agencia_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_agencia_shipper_geo');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_ciu_id'], 'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
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
    public function scm_dispatcher_add_upd_ruta_origen($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_add_upd_ruta');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['vp_id_ruta']), 'int');
        parent::SetParameterSP(trim($p['vp_origen']), 'int');
        parent::SetParameterSP(trim($p['vp_id_origen']), 'int');
        parent::SetParameterSP(trim($p['vp_ciu_id']), 'int');
        parent::SetParameterSP(trim($p['vp_id_contacto']), 'int');
        parent::SetParameterSP(trim($p['vp_id_area']), 'int');
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
    public function get_scm_dispatcher_horarios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_horarios');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function get_scm_dispatcher_anular_solicitud($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_anular_solicitud');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
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
    public function scm_dispatcher_datos_servicios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_dispatcher_datos_servicios');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
}