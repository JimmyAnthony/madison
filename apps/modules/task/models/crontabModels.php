<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class crontabModels extends Adodb {

    private $dsn;
    private $dsn01;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_mssql_02');
        $this->dsn01 = Common::read_ini(PATH.'config/config.ini', 'server_pcll');
        $this->dsn02 = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    public function uSP_Alerta_Diaria_2($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'uSP_Alerta_Diaria_2');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_fecha_ini'], 'date');
        parent::SetParameterSP($p['vp_fecha_fin'], 'date');
        // echo parent::getSql(); die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function pcl_email_programacion($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn02, 'pcl_email_programacion');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function pcl_email_miembros($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn02, 'pcl_email_miembros');
        parent::SetParameterSP($p['vp_id_email'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function pcl_email_envios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn02, 'pcl_email_envios');
        parent::SetParameterSP($p['vp_id_email'], 'int');
        parent::SetParameterSP($p['vp_id_user'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray(array('error_sql','error_isam','error_info'));
        return $array;
    }

    /*public function pcl_envio_email($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn01, 'pcl_envio_email');
        parent::SetParameterSP($p['vp_ship'], 'int');
        parent::SetParameterSP($p['vp_agencia'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        parent::SetParameterSP($p['vp_user'], 'int');
        // echo parent::getSql(); die();
        $array = parent::ExecuteSPArray(array('error_sql','error_isam','error_info'));
        return $array;
    }*/

    public function ll_envio_sms_er($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn01, 'll_envio_sms_er');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        // echo parent::getSql(); die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function pcl_envio_sms_log($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn01, 'pcl_envio_sms_log');
        parent::SetParameterSP($p['vp_guinum'], 'int');
        parent::SetParameterSP($p['vp_sms'], 'varchar');
        parent::SetParameterSP($p['vp_celular'], 'varchar');
        parent::SetParameterSP($p['vp_chk'], 'varchar');
        parent::SetParameterSP($p['vp_user'], 'int');
        // echo parent::getSql(); die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function get_pcll_rpt_sin_img($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn01, 'pcll_rpt_sin_img');
        // echo parent::getSql(); die();
        $array = parent::ExecuteSPArray();
        return $array;
    }

}
