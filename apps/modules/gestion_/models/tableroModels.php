<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class tableroModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    public function bi_tablero_panel($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'bsc_tablero_panel');
        parent::SetParameterSP($p['vp_id_tablero'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray(array('error_sql', 'error_info'));
        return $array;
    }

    public function bi_tablero_panel_valores($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'bsc_tablero_panel_valores');
        parent::SetParameterSP($p['vp_id_indicador'], 'int');
        parent::SetParameterSP($p['vp_anio'], 'int');
        parent::SetParameterSP($p['vp_mes'], 'int');
        parent::SetParameterSP($p['vp_nro_meses'], 'int');
        parent::SetParameterSP($p['vp_id_semana'], 'int');
        parent::SetParameterSP($p['vp_ship_abc'], 'varchar');
        parent::SetParameterSP($p['vp_id_shipp'], 'varchar');
        parent::SetParameterSP($p['vp_id_orden'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
         //echo '=>' . parent::getSql() . '</br>'; die();
        $array = parent::ExecuteSPArray(array('error_sql', 'error_info'));
        return $array;
    }

    public function scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_tabla_detalle');
        parent::SetParameterSP($p['vp_tab_id'], 'varchar');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function bsc_anio_semanas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'bsc_anio_semanas');
        parent::SetParameterSP($p['vp_anio'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
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

    public function bsc_tablero_panel_grafico($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'bsc_tablero_panel_grafico');
        parent::SetParameterSP($p['vp_id_grafic'], 'int');
        parent::SetParameterSP($p['vp_periodo'], 'varchar');
        parent::SetParameterSP($p['vp_anio'], 'int');
        parent::SetParameterSP($p['vp_mes'], 'int');
        parent::SetParameterSP($p['vp_nro_meses'], 'int');
        parent::SetParameterSP($p['vp_id_semana'], 'int');
        parent::SetParameterSP($p['vp_ship_abc'], 'varchar');
        parent::SetParameterSP($p['vp_id_shipp'], 'varchar');
        parent::SetParameterSP($p['vp_id_orden'], 'varchar');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function bsc_tablero_panel_drill($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'bsc_tablero_panel_drill');
        parent::SetParameterSP($p['vp_id_indicador'], 'int');
        parent::SetParameterSP($p['vp_anio'], 'int');
        parent::SetParameterSP($p['vp_mes'], 'int');
        parent::SetParameterSP($p['vp_nro_meses'], 'int');
        parent::SetParameterSP($p['vp_id_semana'], 'int');
        parent::SetParameterSP($p['vp_ship_abc'], 'varchar');
        parent::SetParameterSP($p['vp_id_shipp'], 'varchar');
        parent::SetParameterSP($p['vp_id_orden'], 'varchar');
        parent::SetParameterSP($p['vp_ambito'], 'varchar');
        //echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_tipo_servicios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn,'bsc_lista_servicios_shipper');        
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_bsc_tablero_panel_help($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn,'bsc_tablero_panel_help');        
        parent::SetParameterSP(trim($p['vp_id_indicador']),'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }
}
