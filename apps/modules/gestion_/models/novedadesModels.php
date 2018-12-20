<?php

/**
    
 *
 * @link    https://github.com/JimmyAnthony
 * @author  Jimmy Anthony @JimAntho (https://twitter.com/JimAntho)
 * @version 2.0
 */

class novedadesModels extends Adodb {

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
    public function get_scm_lista_novedades($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_listado');
        parent::SetParameterSP(trim($p['vp_id_nov']),'int');
        parent::SetParameterSP(trim($p['vp_desde']),'char');
        parent::SetParameterSP(trim($p['vp_hasta']),'char');
        parent::SetParameterSP(trim($p['vp_id_linea']),'int');
        parent::SetParameterSP(trim($p['vp_pnov_id']),'int');
        parent::SetParameterSP(trim($p['vp_tnov_id']),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim($p['vp_tdoc_id']),'int');
        parent::SetParameterSP(trim($p['vp_prov_codigo']),'int');
        parent::SetParameterSP(trim($p['vp_doc_numero']),'char');
        parent::SetParameterSP(trim($p['vp_activas']),'int');
        parent::SetParameterSP(trim($p['vp_you']),'int');
        parent::SetParameterSP(USR_ID,'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_lista_novedades_front($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_listado_front');
        parent::SetParameterSP(USR_ID,'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_lista_comentarios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_comentarios');
        parent::SetParameterSP(trim($p['vp_id_nov']),'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_linea($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_linea_negocio');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_check_novedad($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_procesos_macro');
        parent::SetParameterSP(trim($p['vp_tipo']),'char');
        parent::SetParameterSP(0,'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_motivo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_motivo');
        parent::SetParameterSP(trim($p['vp_pnov_id']),'int');
        parent::SetParameterSP('','int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_provincias($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_provincias');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'usr_sis_shipper');
        parent::SetParameterSP(trim(USR_ID),'int');
        parent::SetParameterSP(trim($p['vp_linea']),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_registro($p){
        $prov = (trim($p['vp_prov_codigo'])!='')?$p['vp_prov_codigo']:PROV_CODIGO;
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_registro');
        parent::SetParameterSP(trim($p['vp_fac_linea']),'int');
        parent::SetParameterSP(trim($prov),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim($p['vp_tipo_doc']),'int');
        parent::SetParameterSP(trim($p['vp_doc_numero']),'char');
        parent::SetParameterSP(trim($p['vp_pnov_id']),'int');
        parent::SetParameterSP(trim($p['vp_tnov_id']),'int');
        parent::SetParameterSP(trim($p['vp_msn_publico']),'char');
        parent::SetParameterSP(utf8_decode(trim($p['vp_msn_texto'])),'char');
        parent::SetParameterSP(trim($p['vp_file_nombre']),'char');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_registro_comentario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_registro_comentario');
        parent::SetParameterSP(trim($p['vp_id_nov']),'int');
        parent::SetParameterSP(utf8_decode(trim($p['vp_msn_texto'])),'char');
        parent::SetParameterSP(trim($p['vp_msn_publico']),'int');
        parent::SetParameterSP(trim($p['vp_file_nombre']),'char');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_registro_cierre($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_registro_cierre');
        parent::SetParameterSP(trim($p['vp_id_nov']),'int');
        parent::SetParameterSP(utf8_decode(trim($p['vp_responsable'])),'char');
        parent::SetParameterSP(utf8_decode(trim($p['vp_valor'])),'char');
        parent::SetParameterSP(trim($p['vp_tnov_id']),'int');
        parent::SetParameterSP(utf8_decode(trim($p['vp_msn_texto'])),'char');
        parent::SetParameterSP(trim($p['vp_msn_publico']),'int');
        parent::SetParameterSP(trim($p['vp_file_nombre']),'char');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_registro_descarga($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_registra_descarga');
        parent::SetParameterSP(trim($p['vp_id_file']),'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_elimina_comentario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_nvd_elimina_comentario');
        parent::SetParameterSP(trim($p['vp_msn_id']),'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
}
