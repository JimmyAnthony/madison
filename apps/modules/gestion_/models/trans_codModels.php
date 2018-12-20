<?php

/**
 * 
 * @link    
 * @author  
 * @version 2.0
 */

class trans_codModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    /*public function scm_hue_add_udp_turnos_laboral($p){
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
    }*/
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
    public function scm_cod_financiero_panel($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_financiero_panel');
        parent::SetParameterSP($p['vp_operacion'], 'int');
        parent::SetParameterSP($p['vp_estado'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'char');
        parent::SetParameterSP($p['vp_fecfin'], 'char');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        parent::SetParameterSP($p['vp_provincia'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    } 




    public function usr_sis_bancos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_bancos');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }   

    public function usr_sis_cta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_cta');
        parent::SetParameterSP($p['vp_cta'], 'int');
        parent::SetParameterSP($p['vp_shi_codigo'], 'int');
        parent::SetParameterSP($p['vp_tmnd_id'], 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }       
    public function usr_sis_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_personal');
        parent::SetParameterSP($p['vp_prov_codigo'] , 'int');
        parent::SetParameterSP($p['vp_cargo'] , 'int');
        parent::SetParameterSP($p['vp_area'] , 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }  

    public function scm_cod_financiero_grabar($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_financiero_grabar');
        parent::SetParameterSP($p['vp_id_dep'] , 'int');
        parent::SetParameterSP($p['vp_cta_id'] , 'int');
        parent::SetParameterSP($p['vp_fecha'] , 'char');
        parent::SetParameterSP($p['vp_voucher'] , 'char');
        parent::SetParameterSP($p['vp_retenido'] , 'int');
        parent::SetParameterSP($p['vp_observ'] , 'char');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }     


}   