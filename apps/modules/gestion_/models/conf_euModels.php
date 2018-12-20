<?php

/**
 * 
 * @link    
 * @author  
 * @version 2.0
 */

class conf_euModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    public function scm_hue_add_udp_turnos_laboral($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_add_udp_turnos_laboral');
        parent::SetParameterSP($p['vp_id_turno'] , 'int');
        parent::SetParameterSP($p['vp_entrada'] , 'char');
        parent::SetParameterSP($p['vp_salida'] , 'char');
        parent::SetParameterSP($p['vp_irefri'] , 'char');
        parent::SetParameterSP($p['vp_frefri'] , 'char');
        parent::SetParameterSP($p['vp_estado'] , 'char');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_hue_select_turnos_laboral($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_select_turnos_laboral');
        parent::SetParameterSP($p['vp_id_turno'] , 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

 	public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias');
        parent::SetParameterSP($p['vp_id_linea'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
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

	public function scm_hue_select_unidades($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_select_unidades');
        parent::SetParameterSP($p['vp_und_id'], 'int');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_placa'], 'char');
        parent::SetParameterSP($p['vp_tipo'], 'int');
          // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }    
    public function scm_hue_add_udp_unidades($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_add_udp_unidades');
        parent::SetParameterSP($p['vp_und_id'], 'int');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_und_placa'], 'char');
        parent::SetParameterSP($p['vp_tund_id'], 'int');
        parent::SetParameterSP($p['vp_und_descri'], 'char');
        parent::SetParameterSP($p['vp_tprop_id'], 'int');
        parent::SetParameterSP($p['vp_und_anio'], 'char');
        parent::SetParameterSP($p['vp_und_marca'], 'char');
        parent::SetParameterSP($p['vp_und_capacidad'], 'int');
        parent::SetParameterSP($p['vp_und_kmact'], 'int');
        parent::SetParameterSP($p['vp_und_estado'], 'char');
        parent::SetParameterSP($p['vp_id_sne'], 'int');
        

        $array = parent::ExecuteSPArray();
        return $array;
    }    
	public function scm_hue_add_udp_celulares($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_add_udp_celulares');
        parent::SetParameterSP($p['vp_cel_id'], 'int');
        parent::SetParameterSP($p['vp_cel_imei'], 'char');
        parent::SetParameterSP($p['vp_cel_numero'], 'char');
        parent::SetParameterSP($p['vp_cel_num_rp'], 'char');
        parent::SetParameterSP($p['vp_tprop_id'], 'int');
        parent::SetParameterSP($p['vp_cel_estado'], 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }   
	public function scm_hue_select_celulares($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_select_celulares');
        parent::SetParameterSP($p['vp_imei'], 'char');
        parent::SetParameterSP($p['vp_cel_numero'], 'char');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }     

	public function scm_lista_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_lista_personal');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP($p['vp_cargo'], 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }        

	public function usr_sis_zonas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_zonas');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }               

	public function scm_hue_select_equipo_trabajo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_select_equipo_trabajo');
        parent::SetParameterSP($p['vp_prov_codigo'], 'int');
        parent::SetParameterSP($p['vp_doc_numero'], 'char');
        parent::SetParameterSP($p['vp_per_codigo'], 'char');
        parent::SetParameterSP($p['vp_per_apellido'], 'char');
        parent::SetParameterSP($p['vp_per_nombre'], 'char');
       //  echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }  
	public function scm_hue_add_udp_config($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_add_udp_config');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_id_turno'], 'int');
        parent::SetParameterSP($p['vp_cel_id'], 'int');
        parent::SetParameterSP($p['vp_und_id'], 'int');
        parent::SetParameterSP($p['vp_id_zona'], 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }   

    public function scm_gestion_personal_get_usuario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_get_usuario');
        parent::SetParameterSP(trim($p['vp_per_id']), 'int');
        parent::SetParameterSP(trim($p['vp_new']), 'char');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_hue_select_permiso_mac($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_select_permiso_mac');
        parent::SetParameterSP(trim($p['vp_id_user']), 'int');
        parent::SetParameterSP(trim($p['query']), 'varchar');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_hue_add_udp_config_permiso_mac($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_hue_add_udp_config_permiso_mac');
        parent::SetParameterSP(trim($p['vp_cel_id']), 'int');
        parent::SetParameterSP(trim($p['vp_estado']), 'char');
        parent::SetParameterSP(trim($p['vp_user_id']), 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function scm_socionegocio_ruc($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_ruc');
        parent::SetParameterSP($p['vp_sne_ruc'] , 'varchar');
        parent::SetParameterSP($p['vp_sne_nombre'] , 'varchar');
        parent::SetParameterSP($p['vp_tipo'] , 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
}   