<?php

class gestorOperativoController extends AppController {
    private $objDatos;
    private $arrayMenu;

    public function __construct(){
    	$this->valida();
    	$this->objDatos = new gestorOperativoModels();
    }

    public function index($p){
    	//$this->view('/gtransporte/gtransporte.php', $p);
        $this->view('/gestor_operativo/form_index.php', $p);
    }
    public function scm_dispatcher_unidades($p){
        $rs = $this->objDatos->scm_dispatcher_unidades($p);
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
    public function get_usr_sis_provincias($p){
        $rs = $this->objDatos->usr_sis_provincias($p);
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
    public function get_estados($p){
        $array = array(
            array('chk' => 'SS', 'descri' => 'Servicios Nuevos'),
            array('chk' => 'LD', 'descri' => 'Servicios en Ejecución'),
            array('chk' => 'DL', 'descri' => 'Servicios Ejecutados'),
        );
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($data); 
    }
    public function scm_dispatcher_servicios($p){
        $rs = $this->objDatos->scm_dispatcher_servicios($p);
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
    public function scm_dispatcher_lista_ruta($p){
        $rs = $this->objDatos->scm_dispatcher_lista_ruta($p);
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
    public function scm_dispatcher_lista_servicios($p){
        $rs = $this->objDatos->scm_dispatcher_lista_servicios($p);
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
    public function scm_dispatcher_datos_servicios($p){
        $rs = $this->objDatos->scm_dispatcher_datos_servicios($p);
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
    public function get_scm_dispatcher_horarios($p){
        $rs = $this->objDatos->get_scm_dispatcher_horarios($p);
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
    public function scm_dispatcher_add_ruta($p){
        $rs = $this->objDatos->scm_dispatcher_add_ruta($p);
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
    public function get_scm_contactos($p){
        $rs = $this->objDatos->get_scm_contactos($p);
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
    public function get_scm_unidades($p){
        $rs = $this->objDatos->get_scm_unidades($p);
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
    public function get_scm_delivery_unidad_gps_distance($p){
        $rs = $this->objDatos->get_scm_delivery_unidad_gps_distance($p);
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
    public function get_scm_usr_sis_personal($p){
        $rs = $this->objDatos->get_scm_usr_sis_personal($p);
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
    public function scm_dispatcher_upd_programacion($p){
        $rs = $this->objDatos->scm_dispatcher_upd_programacion($p);
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
    public function get_CuadroMotivos($p){
        $rs=$this->objDatos->get_CuadroMotivos($p);
        $array = array();$array_ = array();
        foreach ($rs as $index => $value){
            if((int)$p['vp_opcion']==(int)$value['chk_id']){
                $array_['mot_id'] = intval($value['mot_id']);
                $array_['chk_id'] = intval($value['chk_id']);
                $array_['chk'] = utf8_encode(trim($value['chk']));
                $array_['chk_descri'] = utf8_encode(trim($value['chk_descri']));
                $array_['orden'] = intval($value['orden']);
                $array[]=$array_;
            }
        }
        $data = array(
            'success' => true,
            'error'=>0,
            'total' => count($array),
            'data' => $array
        );
        header('Content-Type: application/json');
        return $this->response($data);
    }
    public function set_scm_mobile_upd_descarga($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_scm_mobile_upd_descarga($p);
        $rs = $rs[0];
        if (intval($rs['error_sql']) == 1 ){
            $men = "{success: true,error:0,data:".json_encode($rs)."}";
        }else{
            $men =  "{success: true,error:1,errors: '".trim($rs['error_info'])."',error_info: '".trim($rs['error_info'])."'}";
        }
        return $men;
    }
    public function scm_scm_home_delivery_upd_destino($p){
        $rs = $this->objDatos->scm_home_delivery_upd_destino($p);
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
?>