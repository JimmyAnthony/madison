<?php

class salidaRutaController extends AppController {
    private $objDatos;
    private $arrayMenu;

    public function __construct(){
    	$this->valida();
    	$this->objDatos = new salidaRutaModels();
    }

    public function index($p){
     $this->view('/salidaRuta/form_index.php', $p);
    }


    
}
