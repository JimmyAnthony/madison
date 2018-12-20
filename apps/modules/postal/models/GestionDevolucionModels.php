<?php

/**
    
 *
 * @link    https://github.com/JimmyAnthony
 * @author  Jimmy Anthony @JimAntho (https://twitter.com/JimAntho)
 * @version 2.0
 */

class GestionDevolucionModels extends Adodb {

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
        $this->dsn_scm = Common::read_ini(PATH.'config/config.ini', 'server_scm');
    }
    /*
        +--------------------+
        |    Add Procedure   |
        +--------------------+
    */
    public function get_pcn_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_pcm, 'pca_getprovincias');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_pcn_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_pcm, 'pa_getshipper');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_pcn_gestion_devolucion($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_pcn, 'pcn_tdp_gestion_telefonica');
        parent::SetParameterSP($p['vp_orden'], 'int');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        parent::SetParameterSP($p['vp_producto'], 'int');
        parent::SetParameterSP($p['vp_ciclo'], 'varchar');
        parent::SetParameterSP(1, 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function scm_call_gestionistas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_call_gestionistas');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function scm_call_tot_asig_gestionista($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_call_tot_asig_gestionista');
        parent::SetParameterSP($p['vp_ges_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function pcn_call_asignar_gestionista($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_pcn, 'pcn_call_asignar_gestionista');
        parent::SetParameterSP($p['vp_orden'], 'int');
        parent::SetParameterSP($p['vp_agencia'], 'int');
        parent::SetParameterSP($p['vp_ges_id'], 'int');
        parent::SetParameterSP($p['vp_tot_asignar'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
}
