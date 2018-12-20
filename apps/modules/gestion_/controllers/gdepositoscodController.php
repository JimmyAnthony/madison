
<?php

/**
 * @author  Robert Salvatierra (robertsalvatierraq@gmail.com)
 */

class gdepositoscodController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new gdepositoscodModels();
    }

    public function index($p){        
        $this->view('gdepositoscod/form_index.php', $p);
    }
    public function show_deposito($p){        
        $this->view('gdepositoscod/show_deposito.php', $p);
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

    public function get_scm_tabla_detalles($p){
        $rs = $this->objDatos->scm_tabla_detalle($p);
        $array = array(
        	array('descripcion'=>'[ Todos ]','id_elemento'=>'0','des_corto'=>'TODOS')
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

    public function scm_scm_cod_deposito_panel($p){
        $rs = $this->objDatos->scm_cod_deposito_panel($p);
        $array = array(
        );
        if (count($rs) > 0){
            foreach($rs as $index => $value){
            	$value['chk'] = false;//($value['mot_id']) == 174 ? 'true':'false';
            	$value['disabled'] = ($value['mot_id']) == 175 ? false:true;
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

   	public function scm_scm_cod_deposito_grabar($p){
        $rs = $this->objDatos->scm_cod_deposito_grabar($p);
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

    public function scm_scm_cod_mot_codigo($p){
        $rs = $this->objDatos->scm_cod_mot_codigo($p);
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