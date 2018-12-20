<?php

/**
  * @author  Robert Salvatierra (robertsalvatierraq@gmail.com)
 */

class georefModels extends Adodb {

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

    public function usr_sis_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_shipper');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }   

    public function usr_sis_productos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_productos');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //  echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }   

    public function scm_georeferenciar_select($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_georeferenciar_select');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        parent::SetParameterSP($p['vp_pro_id'], 'int');
        parent::SetParameterSP($p['vp_fini'], 'char');
        parent::SetParameterSP($p['vp_ffin'], 'char');
        parent::SetParameterSP(USR_ID, 'int');
        //  echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }     

    public function scm_georeferenciar_dowload($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_georeferenciar_dowload');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_ord_numero'], 'int');
          //echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_georeferenciar_dowload_all($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_georeferenciar_dowload_all');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        parent::SetParameterSP($p['vp_pro_id'], 'int');
        parent::SetParameterSP($p['vp_fini'], 'char');
        parent::SetParameterSP($p['vp_ffin'], 'char');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_georeferenciar_update($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_georeferenciar_update');
        parent::SetParameterSP($p['vp_dir_id'], 'int');
        parent::SetParameterSP($p['vp_ubigeo'], 'char');
        parent::SetParameterSP($p['vp_x'], 'int');
        parent::SetParameterSP($p['vp_y'], 'int');
        parent::SetParameterSP($p['vp_err'], 'int');
        
        //echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }                       
}
