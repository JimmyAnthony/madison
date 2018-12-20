<?php

class gestiontransController extends AppController {
    private $objDatos;
    private $arrayMenu;

    public function __construct(){
    	$this->valida();
    	$this->objDatos = new gestiontransModels();
    }

    public function index($p){

    	//$this->view('/gtransporte/gtransporte.php', $p);
        $this->view('/gestiontrans/index_gestiontrans.php', $p);
        
    }

    /*public function form_transporte($p){
    	$this->view('/gtransporte/panel_transporte.php', $p);	
    }

    public function form_orden($p){
    	$this->view('/gtransporte/panel_orden.php', $p);	
    }

    public function form_p_servicio_transporte($p){
    	$this->view('/gtransporte/p_servicio_transporte.php', $p);	
    }*/

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

    public function scm_scm_tabla_detalle($p){
        $rs = $this->objDatos->scm_tabla_detalle($p);
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

    public function get_tipo_empaque($p){
        $array = array(
            array('temp_id' => 0, 'temp_descri' => 'Otros'),
            array('temp_id' => 1, 'temp_descri' => 'Caja'),
            array('temp_id' => 2, 'temp_descri' => 'Sobre'),
            array('temp_id' => 3, 'temp_descri' => 'Valija'),
        );
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

    public function get_usr_sis_shipper($p){
        $rs = $this->objDatos->usr_sis_shipper($p);
        $array = array(
         //   array('shi_codigo' => 0, 'shi_nombre' => '[ Todos ]', 'shi_id' => '')
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

    public function getComboDepartamentos($p){
        $rs = $this->objDatos->scm_gestion_personal_ubigeo($p);
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

    public function getComboProvincias($p){
        $rs = $this->objDatos->scm_gestion_personal_ubigeo($p);
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

    public function getComboDistritos($p){
        $rs = $this->objDatos->scm_gestion_personal_ubigeo($p);
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

    public function getAgencia_shipper($p){
        $rs = $this->objDatos->scm_getAgencia_shipper($p);
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
	


    public function scm_scm_dispatcher_unidades($p){
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

    public function scm_scm_dispatcher_panel($p){
        $rs = $this->objDatos->scm_dispatcher_panel($p);
        
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
       // return json_encode($data);
       return $this->response($data); 

    }

    public function scm_scm_dispatcher_servicios($p){
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
       // return json_encode($data);
       return $this->response($data); 

    }

    public function scm_scm_dispatcher_upd_direccion($p){
        $rs = $this->objDatos->scm_dispatcher_upd_direccion($p);
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
       // return json_encode($data);
       return $this->response($data); 

    }
    public function scm_scm_socionegocio_id_puerta($p){
        $rs = $this->objDatos->scm_socionegocio_id_puerta($p);
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
       // return json_encode($data);
       return $this->response($data); 

    }  


    public function scm_scm_dispatcher_upd_origen($p){
        $rs = $this->objDatos->scm_dispatcher_upd_origen($p);
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
       // return json_encode($data);
       return $this->response($data); 

    }     

    public function scm_scm_dispatcher_upd_destino($p){
        $rs = $this->objDatos->scm_dispatcher_upd_destino($p);
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
       // return json_encode($data);
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

    public function scm_scm_dispatcher_add_ruta($p){
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

    public function scm_scm_dispatcher_lista_ruta($p){
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


    public function scm_scm_dispatcher_unidad_gps($p){
        $rs = $this->objDatos->scm_dispatcher_unidad_gps($p);
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

    public function scm_scm_home_delivery_agencia_shipper($p){
        $rs = $this->objDatos->scm_home_delivery_agencia_shipper($p);
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

    public function scm_scm_ss_lista_contactos($p){
        $rs = $this->objDatos->scm_ss_lista_contactos($p);
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

    public function scm_scm_gestion_personal_areas($p){
        $rs = $this->objDatos->scm_gestion_personal_areas($p);
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

    public function scm_scm_dispatcher_horarios($p){
        $rs = $this->objDatos->scm_dispatcher_horarios($p);
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

    public function scm_scm_dispatcher_add_upd_orden($p){
        $rs = $this->objDatos->scm_dispatcher_add_upd_orden($p);
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

    public function scm_scm_dispatcher_add_upd_ruta($p){
        $rs1 = $this->objDatos->scm_dispatcher_add_upd_ruta_origen($p);
       // $rs2 = $this->objDatos->scm_dispatcher_add_upd_ruta_destino($p);
       //print_r($rs1);
        //echo $rs1[0]['error_sql'];

        if ($rs1[0]['error_sql'] == '1' ){
            $rs2 = $this->objDatos->scm_dispatcher_add_upd_ruta_destino($p);
           // print_r($rs2);
            if($rs2[0]['error_sql'] == '1' ){
                $err = array('error_sql'=>$rs2[0]['error_sql'],'error_info'=>$rs1[0]['error_info']);
            }else{
                $err = array('error_sql'=>$rs2[0]['error_sql'],'error_info'=>$rs1[0]['error_info']);
            }
        }else{
            $err = array('error_sql'=>$rs1[0]['error_sql'],'error_info'=>$rs1[0]['error_info']);
        }
        $data = array(
                'success'=>true,
                'data' => $err
        );
        return $this->response($data); 
    }

    public function scm_scm_dispatcher_upd_programacion($p){
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
    
    
    
}
