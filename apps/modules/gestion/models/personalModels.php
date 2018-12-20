<?php

/**
 * 
 * @link    
 * @author  
 * @version 2.0
 */

class personalModels extends Adodb {

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

    public function scm_gestion_busq_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_busq_personal');
        parent::SetParameterSP(trim($p['vp_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_dni']), 'varchar');
        parent::SetParameterSP(trim($p['vp_codigorrhh']), 'varchar');
        parent::SetParameterSP(trim($p['vp_apellidos']), 'varchar');
        parent::SetParameterSP(trim($p['vp_nombres']), 'varchar');
        parent::SetParameterSP(trim($p['vp_id_area']), 'int');
         // echo '=>' . parent::getSql();die();
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

    public function scm_gestion_personal_areas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_areas');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_gestion_personal_cargos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_cargos');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_gestion_personal_nuevo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_nuevo');
        parent::SetParameterSP(trim($p['agencia']), 'int');
        parent::SetParameterSP(trim($p['codigorhh']), 'varchar');
        parent::SetParameterSP(trim($p['apellidos']), 'varchar');
        parent::SetParameterSP(trim($p['nombres']), 'varchar');
        parent::SetParameterSP(trim($p['direccion']), 'varchar');
        parent::SetParameterSP(trim($p['dni']), 'varchar');
        parent::SetParameterSP($p['distrito'], 'int');//es el ciu_id
        parent::SetParameterSP(trim($p['telefono']), 'varchar');
        parent::SetParameterSP(trim($p['rpm']), 'varchar');
        parent::SetParameterSP(trim($p['email']), 'varchar');
        parent::SetParameterSP(trim($p['fingreso']), 'varchar');
        parent::SetParameterSP(trim($p['cargo']), 'int');
        parent::SetParameterSP(trim($p['area']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP('1', 'int');// 1 es nuevo insert
        parent::SetParameterSP('', 'varchar');//per_id
        parent::SetParameterSP('', 'varchar');//per_estado
        parent::SetParameterSP('', 'varchar');//fecha de cese
        parent::SetParameterSP(trim($p['tip_doc']), 'int');
        parent::SetParameterSP(common::get_Ip(), 'varchar');//ip
        parent::SetParameterSP('', 'varchar');//mac

        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_gestion_personal_update($p){
       //echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_nuevo');
        parent::SetParameterSP(trim($p['agencia']), 'int');
        parent::SetParameterSP(trim($p['codigorhh']), 'varchar');
        parent::SetParameterSP(trim($p['apellidos']), 'varchar');
        parent::SetParameterSP(trim($p['nombres']), 'varchar');
        parent::SetParameterSP(trim($p['direccion']), 'varchar');
        parent::SetParameterSP(trim($p['dni']), 'varchar');
        parent::SetParameterSP(trim($p['ciu_ubigeo']), 'varchar');//es el ubigeo
        parent::SetParameterSP(trim($p['telefono']), 'varchar');
        parent::SetParameterSP(trim($p['rpm']), 'varchar');
        parent::SetParameterSP(trim($p['email']), 'varchar');
        parent::SetParameterSP(trim($p['fingreso']), 'varchar');
        parent::SetParameterSP(trim($p['cargo']), 'int');
        parent::SetParameterSP(trim($p['area']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP('2', 'int');// 1 es nuevo update
        parent::SetParameterSP(trim($p['per_id']), 'int');
        parent::SetParameterSP(trim($p['per_estado']), 'varchar');
        parent::SetParameterSP(trim($p['fecha_cese']), 'varchar');
        parent::SetParameterSP(trim($p['tip_doc']), 'int');
        parent::SetParameterSP(common::get_Ip(), 'varchar');//ip
        parent::SetParameterSP('', 'varchar');//mac

          //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_gestion_personal_val_dni($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_val_dni');
        parent::SetParameterSP(trim($p['vl_doc_numero']), 'varchar');
        parent::SetParameterSP(trim($p['vl_tip_doc']), 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_gestion_personal_tdi($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_tdi');
        parent::SetParameterSP('TDI', 'varchar');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    
    public function scm_gestion_personal_servicio($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_servicio');
        parent::SetParameterSP(trim($p['per_id']), 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public  function scm_gestion_personal_insert_servicios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_insert_servicios');
        parent::SetParameterSP(trim($p['per_id']), 'int');
        parent::SetParameterSP(trim($p['prov_codigo']), 'int');
        parent::SetParameterSP(trim($p['linea']), 'varchar');
        parent::SetParameterSP(trim($p['check']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
          // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;         
    }

    public function scm_gestion_personal_perfil($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_perfil');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_gestion_personal_servicio_menu($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_servicio_menu');
        parent::SetParameterSP(trim($p['vp_tpl']), 'int');
        parent::SetParameterSP(trim($p['vp_id_user']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_tabla_detalle');
        parent::SetParameterSP(trim($p['vp_tabid']), 'varchar');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
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

    public function scm_gestion_personal_add_udp_usuario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_add_udp_usuario');
        parent::SetParameterSP(trim($p['cbo_user']), 'int');
        parent::SetParameterSP(trim($p['per_id']), 'int');
        parent::SetParameterSP(trim($p['txt_user']), 'varchar');
        parent::SetParameterSP(trim($p['txt_pass']), 'varchar');
        parent::SetParameterSP(trim($p['sha1']), 'varchar');
        parent::SetParameterSP(trim($p['perf_acceso']), 'int');
        parent::SetParameterSP(trim($p['estado']), 'varchar');
        parent::SetParameterSP(trim($p['permiso']), 'int');//tpl
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function scm_gestion_personal_add_udp_permisos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_add_udp_permisos');
        parent::SetParameterSP(trim($p['vp_id_service']), 'int');
        parent::SetParameterSP(trim($p['vp_id_user']), 'int');
        parent::SetParameterSP(trim($p['vp_estado']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
         //echo '=>' . parent::getSql();//die();
        $array = parent::ExecuteSPArray();
        return $array; 
    } 

    public function usr_sis_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_shipper');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
       //  echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }     
    public function scm_gestion_personal_servicio_orden($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_servicio_orden');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
       //  echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    } 

    public function scm_gestion_personal_add_udp_servicio_orden($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_add_udp_servicio_orden');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_id_orden'], 'int');
        parent::SetParameterSP($p['vp_estado'], 'char');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';//die();
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

    public function scm_gestion_personal_area_select($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_area_select');
        parent::SetParameterSP($p['vp_per_id'], 'int');
           //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }       

    public function scm_gestion_personal_add_udp_p_area($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_add_udp_p_area');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_id_area'], 'int');
        parent::SetParameterSP($p['vp_estado'], 'char');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }       

    public function usr_sis_area($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_area');
        parent::SetParameterSP($p['vp_id_area'], 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }       
    

}
