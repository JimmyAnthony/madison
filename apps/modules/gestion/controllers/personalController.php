<?php

/**
 * @link    
 * @author  
 * @version 2.0
 */

class personalController extends AppController {

    public $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new personalModels();
    }

    public function index($p){        
        $this->view('personal/form_index.php', $p);
    }

    public function get_usr_sis_provincias($p){
        $rs = $this->objDatos->usr_sis_provincias($p);
        $array = array(
          //  array('prov_codigo' => 0, 'prov_nombre' => '[ Todos ]', 'prov_sigla' => '')
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

    public function form_show_nuevo($p){        
        $this->view('personal/show_nuevo.php', $p);
    }

    public function form_show_editar($p){        
        $this->view('personal/show_editar.php', $p);
    }

    public function form_show_sys_permiss($p){        
        $this->view('personal/sys_permiss.php', $p);
    }

    public function get_gestion_busq_personal($p){
        $rs = $this->objDatos->scm_gestion_busq_personal($p);
        $array = array(
          //  array('prov_codigo' => 0, 'prov_nombre' => '[ Todos ]', 'prov_sigla' => '')
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

    public function gestion_personal_areas($p){
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

    public function gestion_personal_cargos($p){
        $rs = $this->objDatos->scm_gestion_personal_cargos($p);
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

    public function gestion_personal_nuevo($p){
        $rs = $this->objDatos->scm_gestion_personal_nuevo($p);
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

    public function gestion_personal_update($p){
        $rs = $this->objDatos->scm_gestion_personal_update($p);
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

    public function get_excel($p){
        $this->view('personal/get_excel.php', $p);
    }

    public function gestion_personal_val_dni($p){
        $rs = $this->objDatos->scm_gestion_personal_val_dni($p);
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

    public function gestion_personal_tdi($p){
        $rs = $this->objDatos->scm_gestion_personal_tdi($p);
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

    public function listar_servicio($p){
        $rs = $this->objDatos->scm_gestion_personal_servicio($p);
        $offset = isset($p['start']) ? $p['start'] : 0;
        $size = isset($p['limit']) ? $p['limit'] : 25;

        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }
        $data = array(
                'success' => true,
                'total' => count($array),
                'data' => array_splice($array, $offset, $size)
        );
        return $this->response($data);         
    }

    public function per_servicio($p){
       // sleep(0.5);
        $rs = $this->objDatos->scm_gestion_personal_insert_servicios($p);
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

    public function scm_scm_gestion_personal_perfil($p){
        $rs = $this->objDatos->scm_gestion_personal_perfil($p);
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

    public function scm_scm_gestion_personal_servicio_menu($p){
        $rs = $this->objDatos->scm_gestion_personal_servicio_menu($p);
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

    public function scm_scm_gestion_personal_add_udp_usuario($p){
        $p['txt_pass']= $p['txt_pass'];
        $p['sha1'] = sha1($p['txt_pass']);
        $rs = $this->objDatos->scm_gestion_personal_add_udp_usuario($p);
        $records = json_decode(stripslashes($p['grid']));

       

       if (count($rs) > 0){     
             foreach($rs as $index => $value){
                if ((int)$value['error_sql'] == 0){
                    if (isset($records)){
                        foreach ($records as $rec) {
                            $h['vp_id_service']=$rec->id_service;
                            $h['vp_id_user']=$value['id_user'];
                            $h['vp_estado']=$rec->chk;
                            $rs2 = $this->objDatos->scm_gestion_personal_add_udp_permisos($h);
                        }
                    }
                }
             }
        } 
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

    public function scm_scm_gestion_personal_servicio_orden($p){
        $rs = $this->objDatos->scm_gestion_personal_servicio_orden($p);
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

    public function scm_scm_gestion_personal_add_udp_servicio_orden($p){
        $records = json_decode(stripslashes($p['grid']));
        $rs = array();
        if (isset($records)){
             foreach ($records as $rec) {
                $h['vp_id_orden'] = $rec->id_orden;
                $h['vp_estado'] = $rec->estado;
                $h['vp_per_id'] = $p['per_id'];
                $rs = $this->objDatos->scm_gestion_personal_add_udp_servicio_orden($h);
             }
        }

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

    public function scm_scm_gestion_personal_area_select($p){
        $rs = $this->objDatos->scm_gestion_personal_area_select($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $value['estado'] = ($value['area_estado'] == 0) ? 'false':'true';
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
    public function scm_scm_gestion_personal_add_udp_p_area($p){
        $records = json_decode(stripslashes($p['grid']));
         if (isset($records)){
             foreach ($records as $rec) {
                $h['vp_per_id'] = $rec->vp_per_id;
                $h['vp_id_area'] = $rec->vp_id_area;
                $h['vp_estado'] = $rec->vp_estado;
                $rs = $this->objDatos->scm_gestion_personal_add_udp_p_area($h);
             }
        }
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
    
    public function scm_usr_sis_area($p){
        $rs = $this->objDatos->usr_sis_area($p);
        $array = array(
            array('id_area'=>'0','area_nombre'=>'[ Todos ]')
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