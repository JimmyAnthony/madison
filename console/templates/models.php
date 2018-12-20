<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class [template]Models extends Adodb {

    private $dsn;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_mysql');
    }

    /*public function pa_demo($p){
     parent::ReiniciarSQL();
     parent::ConnectionOpen($this->dsn, 'pa_demo');
     parent::SetParameterSP(trim($p['param1']), 'int');
     parent::SetParameterSP(trim($p['param2']), 'int');
     //echo parent::getSql().'<br>'; exit();
     $array = parent::ExecuteSPArray();
     return $array;
    }*/

}
