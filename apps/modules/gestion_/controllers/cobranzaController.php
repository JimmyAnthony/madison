<?php

/**
 * @author  Robert Salvatierra (robertsalvatierraq@gmail.com)
 */

class cobranzaController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new cobranzaModels();
    }

    public function index($p){        
        //$this->view('cobranza/form_index.php', $p);
        $this->view('cobranza/f_index.php', $p);
    }
    public function show_retorno($p){        
        $this->view('cobranza/retorno_dinero.php', $p);
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

    public function scm_usr_sis_personal($p){
        $rs = $this->objDatos->usr_sis_personal($p);
        $array = array(
            array('per_id' => 0, 'nombre' => '[ Todos ]')
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
    public function scm_usr_sis_personals($p){
        $rs = $this->objDatos->usr_sis_personal($p);
        $array = array(
            //array('per_id' => 0, 'nombre' => '[ Todos ]')
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

    public function csm_scm_COD_panel_rutas($p){
        $rs = $this->objDatos->scm_COD_panel_rutas($p);
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

    public function scm_scm_COD_panel_rutas_detalle($p){
        $rs = $this->objDatos->scm_COD_panel_rutas_detalle($p);
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

    public function scm_scm_cod_rendir_datos_personal($p){
        $rs = $this->objDatos->scm_cod_rendir_datos_personal($p);
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

    public function scm_scm_cod_rendir_escaneo($p){
        $rs = $this->objDatos->scm_cod_rendir_escaneo($p);
        $array = array(
        );
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $value['chk']='true';
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

    public function scm_cod_rendir_grabar($p){
        $rs = $this->objDatos->scm_cod_rendir_grabar($p);
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

    public function scm_scm_cod_rendir_escaneo_estado($p){
        $rs = $this->objDatos->scm_cod_rendir_escaneo_estado($p);
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

    public function scm_scm_cod_rendir_smart_user($p){
        $rs = $this->objDatos->scm_cod_rendir_smart_user($p);
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

    public function scm_scm_cod_rendir_smart_detalle($p){
        $rs = $this->objDatos->scm_cod_rendir_smart_detalle($p);
        $array = array(
        );
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $value['chk']= ($value['estado']== 0) ? 'false':'true';
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

    public function scm_scm_cod_rendir_ge_personal($p){
        $rs = $this->objDatos->scm_cod_rendir_ge_personal($p);
        $array = array(
        );
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $value['chk']= ($value['estado']== 0) ? 'false':'true';
                $value['disabled'] = true;
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

    
    
    public function scm_scm_cod_rendir_anular($p){
        $rs = $this->objDatos->scm_cod_rendir_anular($p);
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

    public function scm_scm_cod_rendir_panel($p){
        $rs = $this->objDatos->scm_cod_rendir_panel($p);
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

    public function scm_scm_cod_rendir_panel_last($p){
        $rs = $this->objDatos->scm_cod_rendir_panel_last($p);
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
    public function scm_scm_cod_rendir_panel_pendientes($p){
        $rs = $this->objDatos->scm_cod_rendir_panel_pendientes($p);
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

    public function scm_pend_chart($p){
        $rs = $this->objDatos->scm_cod_rendir_panel_last($p);
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

}