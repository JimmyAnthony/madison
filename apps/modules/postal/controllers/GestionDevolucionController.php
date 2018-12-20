<?php

/**
    
 *
 * @link    https://github.com/JimmyAnthony/
 * @author  Jimmy Anthony @JimAntho (https://twitter.com/JimAntho)
 * @version 2.0
 */

class GestionDevolucionController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new GestionDevolucionModels();
    }
    /*
        +---------------+
        |    Add View   |
        +---------------+
    */
    public function index($p){        
        $this->view('gestion_devolucion/form_index.php', $p);
    }

    public function form_panel_detalle($p){
        $this->view('gestion_devolucion/form_detalle_gestion.php', $p);
    }
    public function get_form_asignar($p){
        $this->view('gestion_devolucion/form_asignar_gestion.php', $p);
    }
    /*
        +----------------------+
        |    Add Function  Get |
        +----------------------+
    */
    public function get_pcn_provincias($p){
        $rs = $this->objDatos->get_pcn_provincias($p);
        $array = array();
        if (count($rs) > 0){
            $array = array(
                array('prov_codigo' => '0', 'prov_nombre' => '[ Todos ]')
            );
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
    public function get_pcn_shipper($p){
        $rs = $this->objDatos->get_pcn_shipper($p);
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
    public function get_pcn_gestion_devolucion($p){
        $rs = $this->objDatos->get_pcn_gestion_devolucion($p);
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
    public function get_scm_call_gestionistas($p){
        $rs = $this->objDatos->scm_call_gestionistas($p);
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
    public function get_scm_call_tot_asig_gestionista($p){
        $rs = $this->objDatos->scm_call_tot_asig_gestionista($p);
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
    /*
        +----------------------+
        |    Add Function  Set |
        +----------------------+
    */
    public function set_pcn_call_asignar_gestionista($p){
        $rs = $this->objDatos->pcn_call_asignar_gestionista($p);
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