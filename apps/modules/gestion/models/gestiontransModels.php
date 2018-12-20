<?php
require_once PATH . 'libs/adodb/Adodb_Zend.php';

class gestiontransModels extends Adodb{
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
    public function scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_tabla_detalle');
        parent::SetParameterSP($p['tab_id'] , 'char');
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


    public function scm_dispatcher_add_upd_ruta_origen($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_add_upd_ruta');
        parent::SetParameterSP(trim($p['o_vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['o_vp_id_ruta']), 'int');//1 origen 2 destino
        parent::SetParameterSP(trim($p['o_vp_origen']), 'int');//agencia urbano //agencia shipper //direccion
        parent::SetParameterSP(trim($p['o_vp_id_origen']), 'int');//1 prov_codigo 2 id_age 3 0
        parent::SetParameterSP(trim($p['o_vp_ciu_id']), 'int');
        parent::SetParameterSP(trim($p['o_vp_id_contacto']), 'int');
        parent::SetParameterSP(trim($p['o_vp_id_area']), 'int');
        parent::SetParameterSP(trim($p['o_vp_id_geo']), 'int');
        parent::SetParameterSP(trim($p['o_vp_via_id']), 'int');
        parent::SetParameterSP(trim($p['o_vp_dir_calle']), 'char');
        parent::SetParameterSP(trim($p['o_vp_dir_numvia']), 'char');
        parent::SetParameterSP(trim($p['o_vp_dir_refere']), 'char');
        parent::SetParameterSP(trim($p['o_vp_urb_id']), 'int');
        parent::SetParameterSP(trim($p['o_vp_urb_nom']), 'char');
        parent::SetParameterSP(trim($p['o_vp_mz_id']), 'int');
        parent::SetParameterSP(trim($p['o_vp_mz_mom']), 'char');
        parent::SetParameterSP(trim($p['o_vp_num_lote']), 'char');
        parent::SetParameterSP(trim($p['o_vp_num_int']), 'char');        
        //parent::SetParameterSP(trim($p['o_vp_dir_id']), 'int');
        parent::SetParameterSP(trim($p['o_vp_dir_px']), 'int');
        parent::SetParameterSP(trim($p['o_vp_dir_py']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function scm_dispatcher_add_upd_ruta_destino($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_add_upd_ruta');
        parent::SetParameterSP(trim($p['d_vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['d_vp_id_ruta']), 'int');//1 origen 2 destino
        parent::SetParameterSP(trim($p['d_vp_origen']), 'int');//agencia urbano //agencia shipper //direccion
        parent::SetParameterSP(trim($p['d_vp_id_origen']), 'int');//1 prov_codigo 2 id_age 3 0
        parent::SetParameterSP(trim($p['d_vp_ciu_id']), 'int');
        parent::SetParameterSP(trim($p['d_vp_id_contacto']), 'int');
        parent::SetParameterSP(trim($p['d_vp_id_area']), 'int');
        parent::SetParameterSP(trim($p['d_vp_id_geo']), 'int');
        parent::SetParameterSP(trim($p['d_vp_via_id']), 'int');
        parent::SetParameterSP(trim($p['d_vp_dir_calle']), 'char');
        parent::SetParameterSP(trim($p['d_vp_dir_numvia']), 'char');
        parent::SetParameterSP(trim($p['d_vp_dir_refere']), 'char');
        parent::SetParameterSP(trim($p['d_vp_urb_id']), 'int');
        parent::SetParameterSP(trim($p['d_vp_urb_nom']), 'char');
        parent::SetParameterSP(trim($p['d_vp_mz_id']), 'int');
        parent::SetParameterSP(trim($p['d_vp_mz_mom']), 'char');
        parent::SetParameterSP(trim($p['d_vp_num_lote']), 'char');
        parent::SetParameterSP(trim($p['d_vp_num_int']), 'char');        
        //parent::SetParameterSP(trim($p['o_vp_dir_id']), 'int');
        parent::SetParameterSP(trim($p['d_vp_dir_px']), 'int');
        parent::SetParameterSP(trim($p['d_vp_dir_py']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
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
        parent::SetParameterSP($p['vp_cargo'] , 'int');
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

    public function scm_home_delivery_agencia_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_home_delivery_agencia_shipper');
        parent::SetParameterSP(trim($p['va_shipper']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function scm_ss_lista_contactos ($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_ss_lista_contactos');
        parent::SetParameterSP(trim($p['vp_id_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function scm_gestion_personal_areas ($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_areas');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }      
    
    public function scm_dispatcher_horarios ($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_horarios');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 


    public function scm_dispatcher_add_upd_orden ($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_add_upd_orden');
        parent::SetParameterSP(trim($p['vp_srec_id']), 'int');
        parent::SetParameterSP(trim($p['vp_shi_cod']), 'int');
        parent::SetParameterSP(trim($p['vp_linea']), 'int');
        parent::SetParameterSP(trim($p['vp_sentido_ruta']), 'int');
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

    public function scm_dispatcher_upd_programacion ($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_dispatcher_upd_programacion');
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
}