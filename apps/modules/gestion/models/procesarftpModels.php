<?php

/**
 * 
 * @link    
 * @author  
 * @version 2.0
 */

class procesarftpModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
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

    public function scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_tabla_detalle');
        parent::SetParameterSP($p['vp_tab_id'], 'varchar');
        parent::SetParameterSP($p['vp_shipper'], 'int');
       //  echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }  

    public function usr_sis_linea_negocio($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_linea_negocio');
        parent::SetParameterSP(USR_ID, 'int');
        //  echo '=>' . parent::getSql() . '</br>';die();
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

    public function gestor_consulta_ftp($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_consulta_ftp');
        parent::SetParameterSP($p['shipper'], 'int');
        parent::SetParameterSP($p['tipo'], 'int');
        parent::SetParameterSP($p['linea'], 'int');
        parent::SetParameterSP($p['producto'], 'int');
        parent::SetParameterSP($p['estado'], 'varchar');
        parent::SetParameterSP($p['fec_ini'], 'varchar');
        parent::SetParameterSP($p['fec_fin'], 'varchar');
         //  echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    } 

    public function gestor_ftp_procesar($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_ftp_procesar');
        parent::SetParameterSP($p['id_solicitud'], 'int');
        parent::SetParameterSP(USR_ID, 'int'); 
        parent::SetParameterSP(common::get_Ip(), 'varchar'); 
         //  echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    } 

    public function scm_gestor_ftp_pu($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_ftp_pu');
        parent::SetParameterSP($p['id_solicitud'], 'int');
        parent::SetParameterSP($p['tarch_id'], 'int');
        parent::SetParameterSP(USR_ID, 'int'); 
         //echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    } 

    public function scm_gestor_error_reporte($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_error_reporte');
        parent::SetParameterSP($p['id_solicitud'], 'int');
        //parent::SetParameterSP($p['tarch_id'], 'int');
       // parent::SetParameterSP(USR_ID, 'int'); 
         //echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_gestor_get_reclamos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_get_reclamos');
        parent::SetParameterSP($p['id_solicitud'], 'int');
        //parent::SetParameterSP($p['tarch_id'], 'int');
       // parent::SetParameterSP(USR_ID, 'int'); 
         //echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    } 

    public function scm_gestor_ftp_procesar_lista_etiquetas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_ftp_procesar_lista_etiquetas');
        parent::SetParameterSP($p['vp_solicitud'], 'int');
        parent::SetParameterSP($p['vp_tipo'], 'int');
         //echo '=>' . parent::getSql() . '</br>';die();
        $array = parent::ExecuteSPArray();
        return $array;
    }     




}
