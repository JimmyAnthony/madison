<?php

/**
  * @author  Robert Salvatierra (robertsalvatierraq@gmail.com)
 */

class cobranzaModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias');
        parent::SetParameterSP($p['vp_id_linea'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function usr_sis_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_personal');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_id_cargo'], 'char');
        parent::SetParameterSP($p['vp_id_area'], 'char');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_tabla_detalle');
        parent::SetParameterSP($p['vp_tab_id'], 'varchar');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }
    
    public function scm_COD_panel_rutas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_COD_panel_rutas');
        parent::SetParameterSP($p['vp_prov_codigo'], 'varchar');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP($p['vp_fecfin'], 'varchar');
        parent::SetParameterSP($p['vp_tmnd_id'], 'varchar');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }    
    public function scm_COD_panel_rutas_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_COD_panel_rutas_detalle');
        parent::SetParameterSP($p['vp_prov_codigo'], 'varchar');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP($p['vp_fecfin'], 'varchar');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }        

    public function scm_cod_rendir_datos_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_datos_personal');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_med_pago'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }     

    public function scm_cod_rendir_escaneo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_escaneo');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_med_pago'], 'int');
        parent::SetParameterSP($p['vp_gui_numero'], 'varchar');
        parent::SetParameterSP($p['vp_id_grupo'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }     

    public function scm_cod_rendir_grabar($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_grabar');
        parent::SetParameterSP($p['vp_id_grupo'], 'int');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }     

    public function scm_cod_rendir_escaneo_estado($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_escaneo_estado');
        parent::SetParameterSP($p['vp_doc_id'], 'int');
        parent::SetParameterSP($p['vp_estado'], 'varchar');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }        

    public function scm_cod_rendir_smart_user($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_smart_user');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }             

    public function scm_cod_rendir_smart_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_smart_detalle');
        parent::SetParameterSP($p['vp_id_grupo'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }    

    public function scm_cod_rendir_anular($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_anular');
        parent::SetParameterSP($p['vp_id_grupo'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_cod_rendir_panel($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_panel');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'string');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }        

    public function scm_cod_rendir_ge_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_ge_personal');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_med_pago'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }            
    
    public function scm_cod_rendir_panel_last($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_panel_last');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }        

    public function scm_cod_rendir_panel_pendientes($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_rendir_panel_pendientes');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        parent::SetParameterSP($p['vp_medio_pago'], 'int');
        parent::SetParameterSP($p['vp_moneda'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }                    
}
