<?php

/**
 * 
 * @link    
 * @author  
 * @version 2.0
 */

class red_despachoModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    public  function usr_sis_provincias_red_despacho($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias_red_despacho');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP($p['tipo'], 'varchar');
        parent::SetParameterSP($p['prov_codigo'], 'int');
          // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;         
    }
    public  function scm_red_despacho_tipos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_tipos');
        parent::SetParameterSP($p['vl_prov_origen'], 'int');
          // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;         
    }   

    public  function scm_red_despacho_horario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_horario');
        parent::SetParameterSP($p['vl_cbe_id'], 'int');
        parent::SetParameterSP($p['vl_tvia_id'], 'int');
          // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;         
    }  

    public  function scm_red_despacho_mapas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_mapas');
        parent::SetParameterSP($p['vl_prov_origen'], 'int');
        parent::SetParameterSP($p['vl_prov_destino'], 'int');
        parent::SetParameterSP($p['tip_via'], 'int');
          // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;         
    }

    public  function scm_red_despacho_linehaul_local($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_linehaul_local');
        parent::SetParameterSP($p['vl_prov_origen'], 'int');
          // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;         
    }   

    public  function scm_red_despacho_get_linehaul($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_get_linehaul');
        parent::SetParameterSP($p['vl_prov_origen'], 'int');
          // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;         
    }     

    public  function scm_red_despacho_graba_provincia($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_graba_provincia');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_dir_id'], 'int');
        parent::SetParameterSP($p['vp_id_geo'], 'int');
        parent::SetParameterSP($p['vp_ciu_id'], 'int');
        parent::SetParameterSP($p['vp_via_id'], 'int');
        parent::SetParameterSP($p['vp_dir_calle'], 'char');
        parent::SetParameterSP($p['vp_dir_numvia'], 'char');
        parent::SetParameterSP($p['vp_dir_referent'], 'char');
        parent::SetParameterSP($p['vp_urb_id'], 'int');
        parent::SetParameterSP($p['vp_urb_nombre'], 'char');
        parent::SetParameterSP($p['vp_mz_id'], 'int');
        parent::SetParameterSP($p['vp_mz_nom'], 'char');
        parent::SetParameterSP($p['vp_num_lote'], 'char');
        parent::SetParameterSP($p['vp_num_int'], 'char');
        parent::SetParameterSP($p['vp_lat'], 'int');
        parent::SetParameterSP($p['vp_lon'], 'int');
        parent::SetParameterSP($p['vp_prov_foto'], 'char');
        
         // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;        
    }         
    
    public  function scm_red_despacho_get_horarios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_get_horarios');
        parent::SetParameterSP($p['vl_cbe_id'], 'int');
       //  echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;        
    }           

    public  function scm_red_despacho_graba_horarios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_graba_horarios');
        parent::SetParameterSP($p['vl_cbe_id'], 'int');
        parent::SetParameterSP($p['th_salida'], 'varchar');
        parent::SetParameterSP($p['th_llegada'], 'varchar');
        parent::SetParameterSP($p['testado'], 'varchar');
		parent::SetParameterSP($p['ah_salida'], 'varchar');        
		parent::SetParameterSP($p['ah_llegada'], 'varchar');   
		parent::SetParameterSP($p['aestado'], 'varchar');   
		parent::SetParameterSP($p['oh_salida'], 'varchar');        
		parent::SetParameterSP($p['oh_llegada'], 'varchar');
		parent::SetParameterSP($p['oestado'], 'varchar');		        

       // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;        
    }  

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias');
        parent::SetParameterSP('0', 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function usr_sis_distritos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_distritos');
        parent::SetParameterSP('0', 'int');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_red_distribucion($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_distribucion');
        //parent::SetParameterSP($p['vp_prov_origen'], 'int');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        //parent::SetParameterSP($p['vp_ciu_id'], 'int');
        //parent::SetParameterSP($p['vp_tipo'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>'.parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_red_distribucion_graba_horarios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_distribucion_graba_horarios');
        parent::SetParameterSP($p['vp_op'], 'int');
        parent::SetParameterSP($p['vp_id'], 'int');
        parent::SetParameterSP($p['vp_haul_diario'], 'int');
        parent::SetParameterSP($p['vp_tdia_id'], 'int');
        parent::SetParameterSP($p['vp_hor_salida'], 'varchar');
        parent::SetParameterSP($p['vp_haul_time'], 'varchar');
        parent::SetParameterSP($p['vp_haul_parada'], 'varchar');
        parent::SetParameterSP($p['vp_dia_estado'], 'int');
        parent::SetParameterSP($p['vp_mant_coord'], 'int');
        parent::SetParameterSP($p['vp_coor_x'], 'varchar');
        parent::SetParameterSP($p['vp_coor_y'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>'.parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
        
    }
    public function get_scm_red_distribucion_dias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_distribucion_dias');
        parent::SetParameterSP($p['vp_id'], 'int');
        //echo '=>'.parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function scm_get_provincia($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_agencias');
        //echo '=>'.parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function scm_get_distrito($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_red_despacho_distritos');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>'.parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }
}
