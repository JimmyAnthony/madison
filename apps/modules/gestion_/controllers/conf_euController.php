<?php

/**
 * @link    
 * @author  
 * @version 2.0
 */

class conf_euController extends AppController {

    public $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();
        $this->objDatos = new conf_euModels();
    }

    public function index($p){        
        $this->view('conf_eu/form_index.php', $p);
    }
    public function form_show_horario($p){        
        $this->view('conf_eu/show_horario.php', $p);
    }
    public function form_show_unidades($p){        
        $this->view('conf_eu/show_unidades.php', $p);
    }
    public function form_show_celulares($p){        
        $this->view('conf_eu/show_celulares.php', $p);
    }
    public function form_show_configurar($p){        
        $this->view('conf_eu/show_configurar.php', $p);
    }

    public function scm_scm_hue_add_udp_turnos_laboral($p){
        $rs = $this->objDatos->scm_hue_add_udp_turnos_laboral($p);
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
    public function scm_scm_hue_select_turnos_laboral($p){
        $rs = $this->objDatos->scm_hue_select_turnos_laboral($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
            	$value['rango'] = $value['hora_ini'].' A '.$value['hora_fin'];
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
    public function get_usr_sis_provinciass($p){
        $rs = $this->objDatos->usr_sis_provincias($p);
        $array = array(
            //array('prov_codigo' => 0, 'prov_nombre' => '[ Todos ]', 'prov_sigla' => '')
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
    
    public function get_scm_tabla_detalle($p){
        $rs = $this->objDatos->scm_tabla_detalle($p);
        $array = array(
            array('descripcion' => '[ Todos ]', 'id_elemento' => 0, 'des_corto' => '')
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
    public function get_scm_tabla_detalles($p){
        $rs = $this->objDatos->scm_tabla_detalle($p);
        $array = array(
            //array('descripcion' => '[ Todos ]', 'id_elemento' => 0, 'des_corto' => '')
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
    public function scm_scm_hue_select_unidades($p){
        $rs = $this->objDatos->scm_hue_select_unidades($p);
        $array = array(
            //array('prov_codigo' => 0, 'prov_nombre' => '[ Todos ]', 'prov_sigla' => '')
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

    public function scm_scm_hue_add_udp_unidades($p){
        $rs = $this->objDatos->scm_hue_add_udp_unidades($p);
        $array = array(
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
    public function scm_scm_hue_add_udp_celulares($p){
        $rs = $this->objDatos->scm_hue_add_udp_celulares($p);
        $array = array(
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
    public function scm_scm_hue_select_celulares($p){
        $rs = $this->objDatos->scm_hue_select_celulares($p);
        $array = array(
        );
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }

       // $debug = $array[count($array) - 1];
       // unset($array[count($array) - 1]);

        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array,
         //   'debug' => $debug
        );
        return $this->response($data);
    }    
    
    public function scm_scm_lista_personal($p){
        $rs = $this->objDatos->scm_lista_personal($p);
        $array = array(
        );
        if (count($rs) > 0){
            foreach($rs as $index => $value){
            	$value['nombres'] = $value['codigo'].' - '.$value['nombres'];
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

    public function scm_usr_sis_zonas($p){
        $rs = $this->objDatos->usr_sis_zonas($p);
        $array = array(
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

    public function scm_scm_hue_select_equipo_trabajo($p){
        $rs = $this->objDatos->scm_hue_select_equipo_trabajo($p);
        $array = array(
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

    public function scm_scm_hue_add_udp_config($p){
        $rs = $this->objDatos->scm_hue_add_udp_config($p);
        $records = json_decode(stripslashes($p['grid']));
        $array = array(
        );
         if (count($rs) > 0){     
             foreach($rs as $index => $value){
                if ((int)$value['error_sql'] == 0){
                	$array[] = $value;
                    if (isset($records)){
                        foreach ($records as $rec) {
                            $h['vp_cel_id'] = $rec->cel_id;
                            $h['vp_estado'] = $rec->estado;
                            $h['vp_user_id'] = $p['user'];
                            //print_r($h);
                            $rs2 = $this->objDatos->scm_hue_add_udp_config_permiso_mac($h);
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

    public function scm_scm_gestion_personal_get_usuario($p){
        $rs = $this->objDatos->scm_gestion_personal_get_usuario($p);
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
    public function scm_scm_hue_select_permiso_mac($p){
        $rs = $this->objDatos->scm_hue_select_permiso_mac($p);
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

    public function scm_scm_hue_add_udp_config_permiso_mac($p){
        $rs = $this->objDatos->scm_hue_add_udp_config_permiso_mac($p);
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
}