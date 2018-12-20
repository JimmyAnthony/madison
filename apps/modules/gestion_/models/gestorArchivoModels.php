<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class gestorArchivoModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
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
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function usr_sis_linea_negocio($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_linea_negocio');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function usr_sis_productos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_productos');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
         //echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_gestor_ftp_panel($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_ftp_panel');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        parent::SetParameterSP($p['vp_tipfile'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP($p['vp_producto'], 'int');
        parent::SetParameterSP($p['vp_desde'], 'varchar');
        parent::SetParameterSP($p['vp_hasta'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_gestor_ftp_upfile($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_ftp_upfile');
        parent::SetParameterSP($p['vp_tip_file'], 'int');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        parent::SetParameterSP($p['vp_id_line'], 'int');
        parent::SetParameterSP($p['vp_id_orden'], 'int');
        parent::SetParameterSP($p['vp_ciclo'], 'varchar');
        parent::SetParameterSP($p['vp_guirec'], 'varchar');
        parent::SetParameterSP($p['vp_ord_descri'], 'varchar');
        parent::SetParameterSP($p['vp_apunts'], 'varchar');
        parent::SetParameterSP($p['vp_file'], 'varchar');
        parent::SetParameterSP($p['vp_fec_ini_mat'], 'varchar');
        parent::SetParameterSP($p['vp_fec_fin_mat'], 'varchar');
        parent::SetParameterSP($p['vp_fec_ini_prov'], 'varchar');
        parent::SetParameterSP($p['vp_fec_fin_prov'], 'varchar');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP(Common::get_Ip(), 'varchar');
        // echo '=>' . parent::getSql() . '</br>'; die();
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function scm_gestor_ftp_upfile_confirma($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestor_ftp_upfile_confirma');
        parent::SetParameterSP($p['vp_id_solicitud'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

}
