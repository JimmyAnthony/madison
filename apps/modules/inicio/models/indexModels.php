<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class indexModels extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_main');
    }

    /**
     * Obtiene todos los sistemas disponibles por usuario
     */
    public function usr_sis_sistemas($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_sistemas');
        parent::SetParameterSP(USR_ID, 'int');
        $array = parent::ExecuteSPArray();
        return $array;
    }

    /**
     * Se encarga de marcar el ultimo sistema utilizado
     * para que al siguiente logueo se cargue ese por default.
     */
    public function usr_sis_change_first_sistema($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_change_first_sistema');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP($p['sis_id'], 'int');
        parent::SetParameterSP(Common::get_Ip(), 'varchar');
        $array = parent::ExecuteSPArray();
        return $array;
    }

    /**
     * Obtiene el listado de menus al que se tiene permiso
     * por usuario y sistema
     */
    public function usr_sis_menus($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_menus');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP($p['sis_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray(array('sql_error', 'msn_error'));
        return $array;
    }

    /**
     * Carga la lista de servicios a los cuales se tenga permiso
     * Permisos por botones
     */
    public function usr_sis_servicios($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_servicios');
        parent::SetParameterSP(USR_ID, 'int');
        parent::SetParameterSP(SIS_ID, 'int');
        parent::SetParameterSP($p['vp_mod_id'], 'int');
        parent::SetParameterSP($p['vp_menu_id'], 'int');
        // echo '=>' . parent::getSql() . '</br>';
        $array = parent::ExecuteSPArray(array('sql_error', 'msn_error'));
        return $array;
    }

}
