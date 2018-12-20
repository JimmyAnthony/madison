<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class [template]Controller extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        // $this->valida();

        $this->objDatos = new [template]Models();
    }

    public function index($p){
        /**
         * Cargando datos de archivo de configuracion
         */
        
        $this->view('[template]/form_index.php', $p);
    }    

}