<?php

/**
 * @link    
 * @author  
 * @version 2.0
 */

class planviajeController extends AppController {

    public $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();
        $this->objDatos = new planviajeModels();
    }

    public function index($p){        
        $this->view('planviaje/f_index.php', $p);
    }

    public function usr_sis_provincias($p){
        $rs = $this->objDatos->usr_sis_provincias($p);
        $array = array();
        //$fila['nivel']=0;
        //$array[] = $fila;
        foreach ($rs as $fila) {
           //$fila['nivel']=1;
            $fila["id"]=$fila["prov_codigo"];
            $array[] = $fila;
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return json_encode($data);
    }
   
}