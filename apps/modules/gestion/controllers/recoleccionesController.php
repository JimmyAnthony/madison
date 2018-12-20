<?php

class recoleccionesController extends AppController {
    private $objDatos;
    private $arrayMenu;

    public function __construct(){
    	$this->valida();
    	$this->objDatos = new recoleccionesModels();
    }

    public function index($p){
        $this->view('/recolecciones/servicio_recoleccion.php', $p);
    }
    
    public function get_scm_lista_requerimientos($p){
        $rs = $this->objDatos->get_scm_lista_requerimientos($p);
        $array = array();
        if (count($rs) > 0){$x=1;
            foreach($rs as $index => $value){
                $value['msn_texto']=trim($value['Servicio']);
                if((int)$value['linea']==3){
                    $value['linea']='LL';
                    $value['class_line']='databox_nodedad_ll';
                }
                if((int)$value['linea']==1){
                    $value['linea']='MS';
                    $value['class_line']='databox_nodedad_mv';
                }
                if((int)$value['linea']==2){
                    $value['linea']='VA';
                    $value['class_line']='databox_nodedad_va';
                }
                $value['titulo']=utf8_encode($value['shipper']);
                $value['tipo_orden']=(trim($value['tipo'])=='E')?'RECOJO':'ENTREGA';
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
    public function get_usr_sis_shipper_filtro($p){
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
    public function get_scm_linea($p){
        $rs = $this->objDatos->get_scm_linea($p);
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
    public function get_scm_tabla_detalle($p){
        $rs = $this->objDatos->get_scm_tabla_detalle($p);
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
    public function scm_dispatcher_add_upd_orden($p){
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
    public function get_scm_provincias($p){
        $rs = $this->objDatos->get_scm_provincias($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                if(intval($value['prov_codigo']) != intval($p['vp_prov_codigo']))$array[] = $value;
            }
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($data);
    }
    public function get_scm_area($p){
        $rs = $this->objDatos->get_scm_area($p);
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
    public function get_scm_personal($p){
        $rs = $this->objDatos->get_scm_personal($p);
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
    public function get_scm_agencia_shipper($p){
        $rs = $this->objDatos->get_scm_agencia_shipper($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                if(intval($value['id_agencia']) != intval($p['vp_id_agencia']))$array[] = $value;
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
    public function get_scm_dispatcher_anular_solicitud($p){
        $rs = $this->objDatos->get_scm_dispatcher_anular_solicitud($p);
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
    public function scm_dispatcher_add_upd_ruta_origen($p){
        $rs = $this->objDatos->scm_dispatcher_add_upd_ruta_origen($p);
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
            array('chk' => '%%', 'descri' => '[Todos]'),
            array('chk' => 'SS', 'descri' => 'Pendientes'),
            array('chk' => 'SP', 'descri' => 'En Ejecución'),
            array('chk' => 'RO', 'descri' => 'Ejecutados')
        );
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
}