<?php

/**
  * @author  Robert Salvatierra (robertsalvatierraq@gmail.com)
 */

class gdepositoscodModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias');
        parent::SetParameterSP($p['vp_id_linea'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
         //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_tabla_detalle');
        parent::SetParameterSP($p['vp_tab_id'], 'varchar');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_cod_deposito_panel($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_deposito_panel');
        parent::SetParameterSP($p['vp_provincia'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP($p['vp_fecfin'], 'varchar');
        parent::SetParameterSP($p['vp_medio_pago'], 'int');
        parent::SetParameterSP($p['vp_estado'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
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
    public function scm_cod_deposito_grabar($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_deposito_grabar');
        parent::SetParameterSP($p['vp_provincia'], 'int');
        parent::SetParameterSP($p['vp_cta_id'], 'int');
        parent::SetParameterSP($p['vp_tmp_id'], 'int');
        parent::SetParameterSP($p['vp_moneda'], 'int');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_cod_ids'], 'varchar');
        parent::SetParameterSP($p['vp_montos'], 'int');
        parent::SetParameterSP($p['vp_voucher'], 'varchar');
        parent::SetParameterSP($p['vp_tcambio'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
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

    public function scm_cod_mot_codigo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_cod_mot_codigo');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }          

               
}
