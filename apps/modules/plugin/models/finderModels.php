<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class finderModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm');
    }

    public function usr_sis_login($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_login');
        parent::SetParameterSP($p['user'], 'varchar');
        parent::SetParameterSP($p['key'], 'varchar');
        parent::SetParameterSP($p['ip'], 'varchar');
         //echo '=>' . parent::getSql().'<br>'; exit();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_call_gestionistas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_call_gestionistas');
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_call_buzon_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_call_buzon_detalle');
        parent::SetParameterSP($p['vp_ges_id'], 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_call_buzon_gestiones($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_call_buzon_gestiones');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP($p['vp_fecfin'], 'varchar');
        parent::SetParameterSP($p['vp_gestionista'], 'int');
        parent::SetParameterSP($p['vp_id_user'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function gis_busca_distrito($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_busca_distrito');
        parent::SetParameterSP($p['vp_nomdis'], 'varchar');
        parent::SetParameterSP($p['vp_id_user'], 'int');
         //echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_tabla_detalle');
        parent::SetParameterSP($p['vp_tab_id'], 'varchar');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function gis_busca_via($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_busca_via');
        parent::SetParameterSP($p['vp_nomvia'], 'varchar');
        parent::SetParameterSP($p['vp_ciu_id'], 'varchar');
        parent::SetParameterSP($p['vp_tipvia'], 'varchar');
        parent::SetParameterSP($p['vp_id_user'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function gis_busca_via_segmentos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_busca_via_segmentos');
        parent::SetParameterSP($p['vp_id_via'], 'varchar');
        parent::SetParameterSP($p['vp_ciu_id'], 'varchar');
        parent::SetParameterSP($p['vp_id_user'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function gis_busca_via_grupoviviendas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_busca_via_grupoviviendas');
        parent::SetParameterSP($p['vp_id_via'], 'varchar');
        parent::SetParameterSP($p['vp_ciu_id'], 'varchar');
        parent::SetParameterSP($p['vp_id_user'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function gis_busca_grupoviviendas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_busca_grupoviviendas');
        parent::SetParameterSP($p['vp_ciu_id'], 'varchar');
        parent::SetParameterSP($p['vp_nombre'], 'varchar');
        parent::SetParameterSP($p['vp_id_user'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function gis_busca_via_numero_lote($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_busca_via_numero_lote');
        parent::SetParameterSP($p['vp_ciu_id'], 'int');
        parent::SetParameterSP($p['vp_id_via'], 'int');
        parent::SetParameterSP($p['vp_id_urb'], 'int');
        parent::SetParameterSP($p['vp_numero'], 'varchar');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function gis_busca_manzanas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_busca_manzanas');
        parent::SetParameterSP($p['vp_ciu_id'], 'int');
        parent::SetParameterSP($p['vp_id_urb'], 'int');
        parent::SetParameterSP($p['vp_id_via'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function gis_busca_lotes($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_busca_lotes');
        parent::SetParameterSP($p['vp_ciu_id'], 'int');
        parent::SetParameterSP($p['vp_id_mza'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

    public function get_gis_export_puerta($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'gis_export_puerta');
        parent::SetParameterSP($p['vp_id_geo'], 'int');
        parent::SetParameterSP($p['vp_dir_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }

}
