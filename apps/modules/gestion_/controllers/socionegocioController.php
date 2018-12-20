<?php

class socionegocioController extends AppController {
    private $objDatos;
    private $arrayMenu;

    public function __construct(){
    	$this->valida();
    	$this->objDatos = new socionegocioModels();
    }

    public function index($p){
    	$this->view('/socionegocio/form_index.php', $p);
    }

    public function scm_get_tipo($p){
        //$rs = $this->objDatos->scm_socionegocio_ruc($p);
        $array = array(
            array('tipo' =>'C','Descri' => 'Cliente' ),
            array('tipo' =>'P','Descri' => 'Proveedor' ),
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

    public function scm_scm_socionegocio_ruc($p){
        $rs = $this->objDatos->scm_socionegocio_ruc($p);
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

    public function scm_scm_socionegocio_shipper($p){
        $rs = $this->objDatos->scm_socionegocio_shipper($p);
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

    public function scm_scm_socionegocio_estado_ruc($p){
        $rs = $this->objDatos->scm_socionegocio_estado_ruc($p);
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

    public function scm_scm_socionegocio_add_ruc($p){
        $rs = $this->objDatos->scm_socionegocio_add_ruc($p);
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

    public function scm_scm_socionegocio_add_shipper($p){
        $rs = $this->objDatos->scm_socionegocio_add_shipper($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
                if ((int)$value['error_sql'] == 0){
                    $records = json_decode(stripslashes($p['vp_agencias']));
                    //print_r($records);
                    if(isset($records)){
                        foreach($records as $record){
                            if(isset($record->id_agencia)){
                                $a['vp_shi_codigo']=$p['vp_shi_codigo'];
                                $a['id_agencia']=$record->id_agencia;
                                $a['codigo']=$record->codigo;
                                $a['nombre']=$record->nombre;
                                $a['direccion']=$record->direccion;
                                $a['distrito']=$record->distrito;
                                $a['op']=$record->op;
                                $a['x']=$record->x;
                                $a['y']=$record->y;
                                $a['referencia']=$record->referencia;
                                $a['id_geo']=$record->id_geo;
                                $rss = $this->objDatos->scm_socionegocio_add_agencia($a);
                            }

                        }
                    }

                    $contactos = json_decode(stripslashes($p['vp_contactos']));
                   // print_r($contactos);die();
                    if(isset($contactos)){
                        foreach ($contactos as $contacto){
                            if(isset($contacto->id_contacto)){
                                //$c['vp_shi_codigo']=$p['vp_shi_codigo'];
                                $c['id_contacto']=$contacto->id_contacto;
                                $c['shi_codigo']=$contacto->shi_codigo;
                                $c['con_codigo']=$contacto->con_codigo;
                                $c['id_agencia']=$contacto->id_agencia;
                                $c['tcon_id']=$contacto->tcon_id;
                                $c['con_nombre']=$contacto->con_nombre;
                                $c['con_cargo']=$contacto->con_cargo;
                                $c['con_email']=$contacto->con_email;
                                $c['dir_npiso']=$contacto->dir_npiso;
                                $c['dir_office']=$contacto->dir_office;
                                $c['telf'] = $contacto->telf;
                                $c['anexo'] = $contacto->anexo;
                                $rsc = $this->objDatos->scm_socionegocio_add_contacto($c);

                            }
                        }
                    }
                }
            }
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($data); 
    } 

    public function update_uno_scm_socionegocio_add_agencia($p){
        $rs = $this->objDatos->scm_socionegocio_add_agencia($p);
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

    public function getComboDistritosProvinciaDepartamento($p){
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

    public function scm_scm_socionegocio_select_agencia_shi($p){
        $rs = $this->objDatos->scm_socionegocio_select_agencia_shi($p);
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

    public function scm_scm_socionegocio_select_contacto_shi($p){
        $rs = $this->objDatos->scm_socionegocio_select_contacto_shi($p);
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
    

    public function scm_scm_ss_agencia_shipper($p){
        $rs = $this->objDatos->scm_ss_agencia_shipper($p);
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

    public function scm_scm_socionegocio_estado_contacto($p){
        $rs = $this->objDatos->scm_socionegocio_estado_contacto($p);
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

    public function scm_scm_socionegocio_estado_activity($p){
        $rs = $this->objDatos->scm_socionegocio_estado_activity($p);
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
    
    public function scm_scm_socionegocio_estado_socio_shipper($p){
        $rs = $this->objDatos->scm_socionegocio_estado_socio_shipper($p);
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

    public function scm_scm_socionegocio_select_shipper($p){
        $rs = $this->objDatos->scm_socionegocio_select_shipper($p);
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

    

    public function form_new_socio($p){
        $this->view('/socionegocio/panel_new.php', $p);   
    }

    public function form_new_socio_shipper($p){
        $this->view('/socionegocio/socio_shipper_new.php', $p);   
    }

    public function getDisProvDep($p){
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

    
}
