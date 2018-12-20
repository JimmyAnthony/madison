<?php

/**
    
 *
 * @link    https://github.com/JimmyAnthony
 * @author  Jimmy Anthony @JimAntho (https://twitter.com/JimAntho)
 * @version 2.0
 * @date 15092014
 */

class re_impresionModels extends Adodb {

    private $dsn_pcm;
    private $dsn_pcn;
    private $dsn_scm;

    /*
        +-----------------+
        |    Add Config   |
        +-----------------+
    */
    public function __construct(){
        $this->dsn_pcm = Common::read_ini(PATH.'config/config.ini', 'server_pcm');
        $this->dsn_pcn = Common::read_ini(PATH.'config/config.ini', 'server_pcn');
        $this->dsn_scm = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }
    /*
        +--------------------+
        |    Add Procedure   |
        +--------------------+
    */
    public function get_scm_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_shipper');
        parent::SetParameterSP(trim(USR_ID),'int');
        parent::SetParameterSP('1','char');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_tipo_servicios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_lista_servicios');
        parent::SetParameterSP(trim($p['vp_id_linea']),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_provincias');
        parent::SetParameterSP('1','int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_lista_ge($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_print_ge');
        parent::SetParameterSP(trim($p['vp_gui_numero']),'int');
        parent::SetParameterSP(trim($p['vp_codsuc']),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim($p['vp_tipo_servicio']),'int');
        parent::SetParameterSP(trim($p['vp_fecha']),'varchar');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
}
