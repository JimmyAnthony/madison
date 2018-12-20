<?php

/**
    
 *
 * @link    https://github.com/JimmyAnthony
 * @author  Jimmy Anthony @JimAntho (https://twitter.com/JimAntho)
 * @version 2.0
 * @date 15092014
 */

class solicitar_enviosModels extends Adodb {

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
    public function get_scm_agencia_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_agencia_shipper');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_contactos($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_lista_contactos');
        parent::SetParameterSP(trim($p['vp_id_agencia']),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_cliente_frecuente($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_cliente_frecuente');
        parent::SetParameterSP(trim(USR_ID),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }

    public function get_scm_ciudad($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_lista_ciudades');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_busca_destinatario($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_busca_cliente');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim($p['vp_ciu_id']),'int');
        parent::SetParameterSP(trim($p['vp_nombre']),'varchar');
        parent::SetParameterSP(trim($p['vp_cli_codigo']),'varchar');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_detalle_tabla($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_tabla_detalle');
        parent::SetParameterSP('TDO','char');
        parent::SetParameterSP(0,'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function get_scm_ss_lista_ge_generadas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_lista_ge_generadas');
        parent::SetParameterSP(trim(USR_ID),'int');
        parent::SetParameterSP(trim($p['vp_gui_numero']),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function gis_busca_distrito($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'gis_busca_distrito');
        parent::SetParameterSP($p['vp_nomdis'], 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }
    public function get_scm_producto_sku($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm, 'scm_ss_lista_sku');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray();
        $array[count($array)]['sql'] = parent::getSql();
        return $array;
    }
    
    public function set_scm_registra($p){
        $p['vp_dir_entrega'] = str_replace("'", '', trim($p['vp_dir_entrega']));
        $p['vp_nom_empresa'] = str_replace("'", '', trim($p['vp_nom_empresa']));
        $p['vp_nom_urb'] = str_replace("'", '', trim($p['vp_nom_urb']));
        $p['vp_ubi_direc'] = str_replace("'", '', trim($p['vp_ubi_direc']));
        $p['vp_ref_direc'] = str_replace("'", '', trim($p['vp_ref_direc']));
        $p['vp_descr_sku'] = str_replace("'", '', trim($p['vp_descr_sku']));
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_registra');
        parent::SetParameterSP(trim($p['vp_id_orden']),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim($p['vp_id_agencia']),'int');
        parent::SetParameterSP(trim($p['vp_id_remitente']),'int');
        parent::SetParameterSP(trim($p['vp_cod_cliente']),'varchar');
        parent::SetParameterSP(trim($p['vp_cli_id']),'int');
        parent::SetParameterSP(utf8_encode(trim($p['vp_nom_cliente'])),'varchar');
        parent::SetParameterSP(trim($p['vp_nom_empresa']),'varchar');
        parent::SetParameterSP(trim($p['vp_nro_telf']),'varchar');
        parent::SetParameterSP(trim($p['vp_nro_telf_mobil']),'varchar');
        parent::SetParameterSP(trim($p['vp_correo_elec']),'varchar');
        parent::SetParameterSP(utf8_encode(trim($p['vp_dir_entrega'])),'varchar');
        parent::SetParameterSP('S','varchar');
        parent::SetParameterSP(trim($p['vp_nro_via']),'varchar');
        parent::SetParameterSP(utf8_encode(trim($p['vp_nom_urb'])),'varchar');
        parent::SetParameterSP(trim($p['vp_ciu_id']),'int');
        parent::SetParameterSP(utf8_encode(trim($p['vp_ref_direc'])),'varchar');
        parent::SetParameterSP(trim($p['vp_id_geo']),'int');
        parent::SetParameterSP(trim($p['vp_ciu_px']),'varchar');
        parent::SetParameterSP(trim($p['vp_ciu_py']),'varchar');

        parent::SetParameterSP(trim($p['vp_sku_id']),'int');
        parent::SetParameterSP(trim($p['vp_cod_sku']),'varchar');
        parent::SetParameterSP(utf8_encode(trim($p['vp_descr_sku'])),'varchar');
        parent::SetParameterSP(utf8_encode(trim($p['vp_modelo_sku'])),'varchar');
        parent::SetParameterSP(utf8_encode(trim($p['vp_marca_sku'])),'varchar');
        parent::SetParameterSP(trim($p['vp_peso_v_sku']),'varchar');
        parent::SetParameterSP(trim($p['vp_valor_sku']),'varchar');

        parent::SetParameterSP(trim($p['vp_asegura_serv']),'int');
        parent::SetParameterSP(trim($p['vp_cantidad_sku_sobre']),'int');
        parent::SetParameterSP(trim($p['vp_peso_sku_sobre']),'varchar');

        parent::SetParameterSP(trim($p['vp_cantidad_sku_valija']),'int');
        parent::SetParameterSP(trim($p['vp_peso_sku_valija']),'varchar');

        parent::SetParameterSP(trim($p['vp_cantidad_sku_paquete']),'int');
        parent::SetParameterSP(trim($p['vp_peso_sku_paquete']),'varchar');

        parent::SetParameterSP(trim($p['vp_cod_rastreo']),'varchar');
        parent::SetParameterSP(trim($p['vp_nro_documento']),'varchar');

        parent::SetParameterSP(trim(USR_ID),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_ss_add_ge_sku($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_add_ge_sku');
        parent::SetParameterSP(trim($p['vp_gui_numero']),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        parent::SetParameterSP(trim($p['vp_cod_sku']),'int');
        parent::SetParameterSP(trim($p['vp_descr_sku']),'int');
        parent::SetParameterSP(trim($p['vp_modelo_sku']),'int');
        parent::SetParameterSP(trim($p['vp_marca_sku']),'int');
        parent::SetParameterSP(trim($p['vp_peso_sku']),'int');
        parent::SetParameterSP(trim($p['vp_peso_v_sku']),'int');
        parent::SetParameterSP(trim($p['vp_valor_sku']),'int');
        parent::SetParameterSP(trim($p['vp_cantidad_sku']),'int');
        parent::SetParameterSP(trim(USR_ID),'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_ss_registra_cliente($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_registra_cliente_frecuente');
        parent::SetParameterSP(REMITENTE_ID,'int');
        parent::SetParameterSP(trim($p['vp_cli_id']),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
    public function set_scm_ss_add_ge_acuse($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn_scm,'scm_ss_registra_acuses');
        parent::SetParameterSP(trim($p['vp_gui_numero']),'int');
        parent::SetParameterSP(trim($p['vp_doc_numero']),'varchar');
        parent::SetParameterSP(trim($p['vp_tipo_doc']),'int');
        //echo parent::getSql();
        $array = parent::ExecuteSPArray();
        return $array;
    }
}
