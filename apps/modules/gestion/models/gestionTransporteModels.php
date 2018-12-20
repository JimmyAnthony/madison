<?php
require_once PATH . 'libs/adodb/Adodb_Zend.php';

class gestionTransporteModels extends Adodb{
	private $dsn;
    private $db;

    public function __construct(){
        $this->dsn_scm = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'usr_sis_provincias');
        parent::SetParameterSP(1, 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_track_panel_unidades($p){
        $p['vp_agencia']=(!empty($p['vp_agencia']))?$p['vp_agencia']:PROV_CODIGO;
        $p['vp_fecha']=(!empty($p['vp_fecha']))?$p['vp_fecha']:date('d/m/Y');
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_track_panel_unidades');
        parent::SetParameterSP($p['vp_agencia'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        parent::SetParameterSP($p['vp_placa'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function scm_track_panel_unidades_carga($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_track_panel_unidades_carga');
        parent::SetParameterSP($p['vp_agencia'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        parent::SetParameterSP($p['vp_id_placa'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_api_track_datos_cliente($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_api_track_datos_cliente');
        parent::SetParameterSP($p['vp_guia'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function scm_track_panel_gps_unidad($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_track_panel_gps_unidad');
        parent::SetParameterSP($p['vp_id_placa'], 'int');
        parent::SetParameterSP($p['vp_fecha'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array; 
    }
    public function get_img_tracks($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_api_track_fotos_visita');
        parent::SetParameterSP($p['vp_rut_id'], 'int');
        parent::SetParameterSP($p['vp_guia'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //echo parent::getSql(); 
        $array = parent::ExecuteSPArray();
        return $array; 
    }
}
?>