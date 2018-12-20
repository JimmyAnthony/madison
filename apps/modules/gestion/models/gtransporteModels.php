<?php
require_once PATH . 'libs/adodb/Adodb_Zend.php';

class gtransporteModels extends Adodb{
	private $dsn;
    private $db;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm');
    }

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias');
        parent::SetParameterSP($p['linea'] , 'int');
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

    public function scm_gestion_personal_ubigeo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_ubigeo');
        parent::SetParameterSP(trim($p['va_departamento']), 'int');
        parent::SetParameterSP(trim($p['va_provincia']), 'char');
        parent::SetParameterSP(trim($p['va_distrito']), 'char');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_getAgencia_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_ss_agencia_shipper');
        parent::SetParameterSP(trim($p['va_shipper']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_dispatcher_unidades($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_unidades');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_dispatcher_panel($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_panel');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP('0', 'int');//zona
        parent::SetParameterSP('0', 'int');//unidad
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_dispatcher_servicios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_servicios');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP('0', 'int');//zona
        parent::SetParameterSP('0', 'int');//unidad
        parent::SetParameterSP('%', 'char');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_dispatcher_upd_direccion($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_upd_direccion');
        parent::SetParameterSP(trim($p['id_direccion']), 'int');
        parent::SetParameterSP(trim($p['ciu_id']), 'int');
        parent::SetParameterSP(trim($p['tip_via']), 'int');
        parent::SetParameterSP(trim($p['id_via']), 'int');
        parent::SetParameterSP(trim($p['nom_via']), 'char');
        parent::SetParameterSP(trim($p['id_urb']), 'int');
        parent::SetParameterSP(trim($p['urb']), 'char');
        parent::SetParameterSP(trim($p['id_puerta']), 'int');
        parent::SetParameterSP(trim($p['via']), 'char');
        parent::SetParameterSP(trim($p['x']), 'int');
        parent::SetParameterSP(trim($p['y']), 'int');
        parent::SetParameterSP(trim($p['id_mza']), 'int');
        parent::SetParameterSP(trim($p['lote']), 'char');
        parent::SetParameterSP(trim($p['manzana']), 'char');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP(common::get_Ip(), 'varchar');//ip
         // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function scm_socionegocio_id_puerta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_id_puerta');
        parent::SetParameterSP(trim($p['vl_id_puerta']), 'int');
         // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  


    public function scm_dispatcher_upd_origen($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_upd_origen');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['vp_origen']), 'int');
        parent::SetParameterSP(trim($p['vp_provincia']), 'int');
        parent::SetParameterSP(trim($p['vp_id_age']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_id']), 'int');
        parent::SetParameterSP(trim($p['vp_ciu_id']), 'int');
        parent::SetParameterSP(trim($p['vp_id_contacto']), 'int');
        parent::SetParameterSP(trim($p['vp_id_area']), 'int');

        parent::SetParameterSP(trim($p['vp_id_geo']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_calle']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_numvia']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_refere']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_px']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_py']), 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function scm_dispatcher_upd_destino($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_upd_destino');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['vp_origen']), 'int');
        parent::SetParameterSP(trim($p['vp_provincia']), 'int');
        parent::SetParameterSP(trim($p['vp_id_age']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_id']), 'int');
        parent::SetParameterSP(trim($p['vp_ciu_id']), 'int');
        parent::SetParameterSP(trim($p['vp_id_contacto']), 'int');
        parent::SetParameterSP(trim($p['vp_id_area']), 'int');

        parent::SetParameterSP(trim($p['vp_id_geo']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_calle']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_numvia']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_refere']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_px']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_py']), 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function usr_sis_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_personal');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(22, 'int');
        parent::SetParameterSP(0, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 


    public function scm_dispatcher_add_ruta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_add_ruta');
        parent::SetParameterSP($p['vp_agencia'] , 'int');
        parent::SetParameterSP($p['vp_man_id'] , 'int');
        parent::SetParameterSP($p['vp_srec_id'] , 'int');
        parent::SetParameterSP($p['vp_per_m'] , 'int');
        parent::SetParameterSP($p['vp_per_id_h'] , 'int');
        parent::SetParameterSP($p['vp_und_id'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_dispatcher_lista_ruta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_lista_ruta');
        parent::SetParameterSP($p['vp_agencia'] , 'int');
        parent::SetParameterSP($p['vp_man_id'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_dispatcher_unidad_gps($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_unidad_gps');
        parent::SetParameterSP($p['vp_unidad'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
       // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_home_delivery_unidades($p){
        $p['vp_prov_codigo']=(empty($p['vp_prov_codigo']))?PROV_CODIGO:$p['vp_prov_codigo'];
        $p['vp_fecha']=(empty($p['vp_fecha']))?date('d/m/Y'):$p['vp_fecha'];
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_unidades');
        parent::SetParameterSP(trim($p['vp_prov_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_home_delivery_servicios($p){
        $p['vp_agencia']=(empty($p['vp_agencia']))?PROV_CODIGO:$p['vp_agencia'];
        $p['vp_fecha']=(empty($p['vp_fecha']))?date('d/m/Y'):$p['vp_fecha'];
        $p['vp_estado']=(empty($p['vp_estado']))?'SS':$p['vp_estado'];
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_servicios');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha']), 'varchar');
        parent::SetParameterSP(trim($p['vp_estado']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 
    public function scm_home_delivery_upd_destino($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_upd_destino');
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

    public function scm_home_delivery_paradas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_paradas');
        parent::SetParameterSP(trim($p['vp_guia']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_home_delivery_unidad_gps($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_unidad_gps');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_home_delivery_add_ruta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_add_ruta');
        parent::SetParameterSP(trim($p['vp_gui_num']), 'int');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['vp_id_age']), 'int');
        parent::SetParameterSP(trim($p['vp_provin']), 'int');
        parent::SetParameterSP(trim($p['vp_man_id']), 'int');
        parent::SetParameterSP(trim($p['vp_per_m']), 'int');
        parent::SetParameterSP(trim($p['vp_und_id']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 
    public function scm_home_delivery_pendientes($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_pendientes');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_home_delivery_upd_origen($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_upd_origen');
        parent::SetParameterSP(trim($p['vp_guia']), 'int');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['vp_origen']), 'int');
        parent::SetParameterSP(trim($p['vp_provincia']), 'int');
        parent::SetParameterSP(trim($p['vp_id_age']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_id']), 'int');
        parent::SetParameterSP(trim($p['vp_ciu_id']), 'int');
        parent::SetParameterSP(trim($p['vp_id_geo']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_calle']), 'varchar');
        parent::SetParameterSP(trim($p['vp_dir_numvia']), 'varchar');
        parent::SetParameterSP(trim($p['vp_dir_refere']), 'varchar');
        parent::SetParameterSP(trim($p['vp_dir_px']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_py']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
           //echo '=>' . parent::getSql();//die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }   

    public function scm_home_delivery_lista_ruta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_lista_ruta');
        parent::SetParameterSP(trim($p['vp_prov_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_man_id']), 'int');
        parent::SetParameterSP(trim($p['vp_estado']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }   

    public function scm_home_delivery_panel($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_panel');
        parent::SetParameterSP(trim($p['vp_prov_cod']), 'int');
        parent::SetParameterSP(trim($p['vp_fecha_inicio']), 'varchar');
        parent::SetParameterSP(trim($p['vp_fecha_fin']), 'varchar');
        parent::SetParameterSP(trim($p['vp_estado']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_home_delivery_agencia_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_agencia_shipper');
        parent::SetParameterSP(trim($p['vp_prov_cod']), 'int');
        parent::SetParameterSP(trim($p['va_shipper']), 'int');
        parent::SetParameterSP(trim($p['vp_id_agencia']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 
    
    public function get_scm_mobile_agencias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_mobile_agencias');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }  

    public function scm_home_delivery_unidad_gps_distance($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_unidad_gps_distance');
        parent::SetParameterSP(trim($p['vp_prov_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_id_agencia']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }  
    
}