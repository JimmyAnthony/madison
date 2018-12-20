<?php

/**
 * @link    
 * @author  
 * @version 2.0
 */

class trans_codController extends AppController {

    public $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();
        $this->objDatos = new trans_codModels();
    }

    public function index($p){        
        $this->view('trans_cod/form_index.php', $p);
    }
    public function show_deposito($p){        
        $this->view('trans_cod/transDeposito.php', $p);
    }
    public function show_peso($p){        
        $this->view('trans_cod/controlpeso.php', $p);
    }
    public function get_scm_get_estado($p){
        //$rs = $this->objDatos->scm_hue_add_udp_turnos_laboral($p);
        $array = array(
                array('id_elemento'=>'52','descripcion'=>'COD Recaudo Sin Depósito'),
                array('id_elemento'=>'177','descripcion'=>'COD Depósito en Custodia'),
                array('id_elemento'=>'178','descripcion'=>'COD Depósito en CTA Urbano'),
                array('id_elemento'=>'179','descripcion'=>'COD Transferido / Rendido'),
            );
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

    public function get_usr_sis_provincias($p){
        $rs = $this->objDatos->usr_sis_provincias($p);
        $array = array(
            array('prov_codigo' => 0, 'prov_nombre' => '[ Todos ]', 'prov_sigla' => '')
        );
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
    public function get_usr_sis_shipper($p){
        $rs = $this->objDatos->usr_sis_shipper($p);
        $array = array(
            array('shi_codigo' => 0, 'shi_nombre' => '[ Todos ]', 'shi_id' => '')
        );
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

    public function scm_scm_cod_financiero_panel($p){
        $rs = $this->objDatos->scm_cod_financiero_panel($p);
        //print_r($rs);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                //$value['chk'] = $value['mot_id'] == 177 or $value['mot_id'] == 178 ? true:false;
                $value['chk'] = false;
                $value['disabled'] =  ($value['mot_id'] == 178) ? false:true;//($value['mot_id'] == 177 or $value['mot_id'] == 178) ? false:true;
                //$value['fecha_min'] = Common::getFormatDMY($value['fecha'],'-');
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

    public function scm_usr_sis_bancos($p){
        $rs = $this->objDatos->usr_sis_bancos($p);
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

    public function scm_usr_sis_cta($p){
        $rs = $this->objDatos->usr_sis_cta($p);
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
    
    public function scm_usr_sis_personal($p){
        $rs = $this->objDatos->usr_sis_personal($p);
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

    public function scm_scm_cod_financiero_grabar($p){
        $rs = $this->objDatos->scm_cod_financiero_grabar($p);
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

     public function get_estado($p){
        //$rs = $this->objDatos->usr_sis_shipper($p);
        $array = array(
            array('id' => 0, 'descripcion' => 'Pendiente'),
            array('id' => 1, 'descripcion' => 'Ejecutado')
        );
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