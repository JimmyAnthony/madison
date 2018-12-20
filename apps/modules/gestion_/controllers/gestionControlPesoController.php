<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class gestionControlPesoController extends AppController {

    private $objDatos;
    private $objServicios;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new gestionControlPesoModels();
    }

    public function index($p){
        /**
         * Cargando datos de archivo de configuracion
         */
        
        $this->view('control_peso/form_index.php', $p);
    }

    public function scm_control_peso_escaneo($p){
         $rs = $this->objDatos->scm_control_peso_escaneo($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }
        $data = array(
                'success' => true,
                'total' => count($array),
                'data' => $array
        );
        return $this->response($data);        
    }

    public function scm_control_peso_pza_graba_peso($p){
         $rs = $this->objDatos->scm_control_peso_pza_graba_peso($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }
        $data = array(
                'success' => true,
                'total' => count($array),
                'data' => $array
        );
        return $this->response($data);        
    }

}