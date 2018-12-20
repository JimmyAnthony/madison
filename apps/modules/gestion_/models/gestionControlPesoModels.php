<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class gestionControlPesoModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');
    }

    /**
     * Obtiene los datos necesarios para el control de peso
     */
    public function scm_control_peso_escaneo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_control_peso_escaneo');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP(trim($p['vp_barra']), 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }

    /**
     * Modelo que sirve para guardar los datos del control de peso
     */
    public function scm_control_peso_pza_graba_peso($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_control_peso_pza_graba_peso');
        parent::SetParameterSP(trim($p['vp_barra']), 'varchar');
        parent::SetParameterSP(trim($p['vp_id_pieza']), 'int');
        parent::SetParameterSP(PROV_CODIGO, 'int');
        parent::SetParameterSP(trim($p['vp_peso_seco']), 'int');
        parent::SetParameterSP(trim($p['vp_vol_largo']), 'int');
        parent::SetParameterSP(trim($p['vp_vol_ancho']), 'int');
        parent::SetParameterSP(trim($p['vp_vol_alto']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array;
    }
}