<?php

require_once PATH . 'libs/adodb/Adodb_Zend.php';

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class preclamosModels extends Adodb {

    private $dsn;
    private $db;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm');
        $this->db = new Adodb_Zend(array('nConfigServ' => 'sqlite_preclamos'), false);
    }

    public function usr_sis_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_provincias');
        parent::SetParameterSP('0', 'int');
        parent::SetParameterSP(USR_ID, 'int');
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

    public function scm_reclamo_panel($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_panel');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_tipo'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP($p['vp_fecfin'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP($p['vp_nivel'], 'varchar');
        parent::SetParameterSP($p['vp_shipper'], 'int');
        
        // echo '=>' . parent::getSql() . '</br>';
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

    public function scm_reclamo_grabar($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_grabar');
        parent::SetParameterSP($p['vp_id_libro'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP($p['vp_tipo'], 'int');
        parent::SetParameterSP($p['vp_guia'], 'varchar');
        parent::SetParameterSP($p['vp_mot_rec'], 'varchar');
        parent::SetParameterSP($p['vp_det_rec'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function reclamos_erroneos($p){
        $sql = "select * from tmp_" . intval($p['aleatorio']) . "_" . USR_ID;
        $array = $this->db->ExecuteSql($sql, true);
        return $array;
    }

    public function scm_reclamo_lista_impresiones($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_lista_impresiones');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_impresion($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_impresion');
        parent::SetParameterSP($p['vp_id_imp'], 'int');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP($p['vp_id_tipo'], 'int');
        parent::SetParameterSP($p['vp_nivel'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_en_ld($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_en_ld');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP($p['vp_tipo'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP($p['vp_fecfin'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_qry_auditorias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_qry_auditorias');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_fecini'], 'varchar');
        parent::SetParameterSP($p['vp_fecfin'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_lista_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_lista_personal');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_linea'], 'int');
        parent::SetParameterSP($p['vp_cargo'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_check($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_check');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_editar($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_editar');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_man_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_nuevo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_nuevo');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_per_id'], 'int');
        parent::SetParameterSP($p['vp_fec_man'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_scaneo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_scaneo');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_man_id'], 'int');
        parent::SetParameterSP($p['vp_barra'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_graba($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_graba');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_man_id'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_anular($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_anular');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP($p['vp_man_id'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_qry_persona_aud($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_qry_persona_aud');
        parent::SetParameterSP($p['vp_reclamo'], 'int');
        parent::SetParameterSP($p['vp_tpar'], 'int');
        parent::SetParameterSP($p['vp_dni'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_descarga_grabar($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_descarga_grabar');
        parent::SetParameterSP($p['vp_reclamo'], 'int');
        parent::SetParameterSP($p['vp_fec_aud'], 'varchar');
        parent::SetParameterSP($p['vp_hor_aud'], 'varchar');
        parent::SetParameterSP($p['vp_chk_id'], 'int');
        parent::SetParameterSP($p['vp_resultado'], 'varchar');
        parent::SetParameterSP($p['vp_recepcion'], 'varchar');
        parent::SetParameterSP($p['vp_acuse'], 'int');
        parent::SetParameterSP($p['vp_observa'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_descarga_grabar_entrevista($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_descarga_grabar_entrevista');
        parent::SetParameterSP($p['vp_reclamo'], 'int');
        parent::SetParameterSP($p['vp_id_per'], 'int');
        parent::SetParameterSP($p['vp_tpar'], 'int');
        parent::SetParameterSP($p['vp_nrodni'], 'varchar');
        parent::SetParameterSP($p['vp_nombre'], 'varchar');
        parent::SetParameterSP($p['vp_telefono'], 'varchar');
        parent::SetParameterSP($p['vp_id_viv'], 'int');
        parent::SetParameterSP($p['vp_numsum'], 'varchar');
        parent::SetParameterSP($p['vp_tipviv'], 'int');
        parent::SetParameterSP($p['vp_pisos'], 'int');
        parent::SetParameterSP($p['vp_color'], 'varchar');
        parent::SetParameterSP($p['vp_tpuerta'], 'int');
        parent::SetParameterSP($p['vp_ventana'], 'int');
        parent::SetParameterSP($p['vp_buzon'], 'int');
        parent::SetParameterSP($p['vp_timbre'], 'int');
        parent::SetParameterSP($p['vp_intercom'], 'int');
        parent::SetParameterSP($p['vp_bz_bp'], 'int');
        parent::SetParameterSP($p['vp_dir_ok'], 'int');
        parent::SetParameterSP($p['vp_observ'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_descarga_scaneo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_descarga_scaneo');
        parent::SetParameterSP($p['vp_barra'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_cabecera($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_cabecera');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_man_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_detalle');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_man_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_lista_chk_motivos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_lista_chk_motivos');
        parent::SetParameterSP($p['vp_chk_id'], 'int');
        parent::SetParameterSP($p['vp_proceso'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function scm_reclamo_audi_verifica($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_reclamo_audi_verifica');
        parent::SetParameterSP($p['vp_prov'], 'int');
        parent::SetParameterSP($p['vp_man_id'], 'int');
        parent::SetParameterSP($p['vp_barra'], 'varchar');
        parent::SetParameterSP($p['vp_chk_id'], 'int');
        parent::SetParameterSP(USR_ID, 'int');
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

}
