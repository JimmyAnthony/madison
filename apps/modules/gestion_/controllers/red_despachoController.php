<?php

/**
 * @link    
 * @author  
 * @version 2.0
 */

class red_despachoController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();
        $this->objDatos = new red_despachoModels();
    }

    public function index($p){        
         $this->view('red_despacho/form_index.php', $p);
        // $this->view('procesar_ftp/form_index.php', $p);
    }

    public function provincias_red_despacho($p){
        $rs = $this->objDatos->usr_sis_provincias_red_despacho($p);
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
    public function form_coberturas($p){        
         $this->view('red_despacho/cobertura.php', $p);
    }    
    public function new_age_coord($p){        
         $this->view('red_despacho/new_age_coord.php', $p);
    }    

    public function new_horario($p){        
         $this->view('red_despacho/new_horarios.php', $p);
    }    

    public function form_distribucion($p){        
         $this->view('red_despacho/distribucion.php', $p);
    }    

    /*----------------------------------------------------+*/
    public function form_transito(){
        $this->view('red_despacho/form_transito.php', $p);
    }

    public function get_cobertura($p){
        $rs =$this->objDatos->scm_red_despacho_tipos($p);
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

    public function red_despacho_horario($p){
        $rs =$this->objDatos->scm_red_despacho_horario($p);
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

    public function get_coordenadas($p){
        $rs =$this->objDatos->scm_red_despacho_mapas($p);
      //  var_export($rs);
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

    public function red_despacho_linehaul_local($p){
        $rs =$this->objDatos->scm_red_despacho_linehaul_local($p);
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

    public function red_despacho_get_linehaul($p){
        $rs =$this->objDatos->scm_red_despacho_get_linehaul($p);
        $array = array();
      //  var_export($rs);die();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
               // $value['dir_px']= $value['dir_px'];
               // $value['dir_py']= $value['dir_py'];
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

    /*  $arrayFile = explode('.', $_FILES['photo-path']['name']);
        $extFile = strtolower(trim($arrayFile[1]));
        $p['vp_prov_foto'] = 'urb_'.$p['vp_prov_codigo'].'.'.$extFile;
        $rs =$this->objDatos->scm_red_despacho_graba_provincia($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }
        if((int)$array[0]['error_sql']==0)$this->upload($p);
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($data); */
    public function red_despacho_graba_provincia($p){
        $arrayFile = explode('.', $_FILES['photo-path']['name']);
        $extFile = strtolower(trim($arrayFile[1]));
        $p['vp_prov_foto'] = 'urb_'.$p['vp_prov_codigo'].'.'.$extFile;
        $rs = $this->objDatos->scm_red_despacho_graba_provincia($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }
        //echo $array[0]['error_sql'];
        if((int)$array[0]['error_sql']==1)$this->upload($p);
        //print_r($p);
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($data); 
    }    

    public function upload($p){ 

        $nombre_archivo = $_FILES['photo-path']['name'];
        $tipo_archivo = $_FILES['photo-path']['type'];
        $tamano_archivo = $_FILES['photo-path']['size'];
        $arrayFile = explode('.', $nombre_archivo);
        $nameFile = trim($arrayFile[0]);
        $extFile = strtolower(trim($arrayFile[1]));
        //echo $nombre_archivo;
        if ( trim($nombre_archivo) != '' && in_array($extFile, array('jpg', 'gif','jpeg','JPG','BMP','bmp','GIF','png','PNG'))){

            $directorio = PATH . 'public_html/foto_agencias/urb_'.$p['vp_prov_codigo'].'.'.$extFile;
            $p['vp_lot_tel'] = $rs[0]['lot_id'];
            if (move_uploaded_file($_FILES['photo-path']['tmp_name'], $directorio)) 
            {
                //chmod($directorio,0777);
                return 1;
                
            }else{
                return 0;
            }
        }else{
            return 0;
        }
        
    }

    public function red_despacho_get_horarios($p){
        $rs =$this->objDatos->scm_red_despacho_get_horarios($p);
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

    public function red_despacho_graba_horarios($p){
        $rs =$this->objDatos->scm_red_despacho_graba_horarios($p);
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
        $rs =$this->objDatos->usr_sis_provincias($p);
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

    public function usr_sis_distritos($p){
        $rs =$this->objDatos->usr_sis_distritos($p);
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

    public function scm_red_distribucion($p){
        /*$rs =$this->objDatos->scm_red_distribucion($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $value['dia_']='<div style="height:13.4px;"><table width="65px"><tr><td width="60px";>'.$value['dia'].'</td><td  width="5px"; style="background-color: #'.(((int)$value['estado']==1)?'3CB371':'DC143C').';"></td></tr></table></div>';
                $array[] = $value;
            }
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($data); */

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        $this->rs_ = $this->objDatos->scm_red_distribucion($p);
        if(!empty($this->rs_)){
            return '['.$this->get_recursivo(1).']';
            //return json_encode($this->get_nivel_1());
        }else{
            return json_encode(
                array(
                    'text'=>'.',
                    'children'=>array(
                        'distrito'=>'Sin Información',
                        'est'=>'',
                        'lot_tot'=>0,
                        'lot_pro'=>0,
                        'iconCls'=>'task',
                        'nivel'=>1,
                        'leaf'=>'true'
                        )
                    )
                );
        }
    }

    public function get_recursivo($_nivel){
        foreach ($this->rs_ as $key => $value){
            if ($value['nivel'] == $_nivel){
                $json.="{";
                $json.='"text":"."';
                $json.=',"id1":"'.$value['id1'].'"';
                $json.=',"id2":"'.$value['id2'].'"';
                $json.=',"iconCls":""';
                $json.=',"tdia_id":"'.$value['tdia_id'].'"';
                $json.=',"estado":"'.$value['estado'].'"';
                $json.=',"dir_px":"'.$value['dir_px'].'"';
                $json.=',"dir_py":"'.$value['dir_py'].'"';
                $json.=',"x":"'.$value['x'].'"';
                $json.=',"y":"'.$value['y'].'"';
                $json.=',"info":"'.$value['info'].'"';
                $json.=',"distrito":"'.$value['distrito'].'"';
                $json.=',"hor_salida":"'.$value['hor_salida'].'"';
                $json.=',"hor_salida":"'.$value['hor_salida'].'"';
                $json.=',"haul_time":"'.$value['haul_time'].'"';
                $json.=',"haul_arribo":"'.$value['haul_arribo'].'"';
                $json.=',"haul_diario":"'.$value['haul_diario'].'"';
                $json.=',"haul_parada":"'.$value['haul_parada'].'"';
                $json.=',"tiempo_transito":"'.$value['tiempo_transito'].'"';
                $json.=',"ciu_id":"'.$value['ciu_id'].'"';
                $json.=',"nivel":"'.$value['nivel'].'"';
                $js = $this->getRecursividad_children($_nivel,$value['id1']);
                if(!empty($js)){
                    $json.=',"children":['.trim($js).']';
                }else{
                    $json.=',"leaf":"true"';
                }
                $json.="},";
            }
        }
        return $json;
    }
    public function getRecursividad_children($_nivel,$_hijo){
        foreach ($this->rs_ as $key => $value){
            if ($value['nivel'] != $_nivel && $value['id1'] == $_hijo){
                $json.="{";
                $json.='"task":"'.utf8_encode(trim($value['lot_arc'])).'"';
                $json.=',"distrito":"'.$value['dia'].'"';
                $json.=',"estado":"'.$value['estado'].'"';
                $json.=',"hor_salida":"'.$value['hor_salida'].'"';
                $json.=',"haul_time":"'.$value['haul_time'].'"';
                $json.=',"haul_arribo":"'.$value['haul_arribo'].'"';
                $json.=',"haul_diario":"'.$value['haul_diario'].'"';
                $json.=',"haul_parada":"'.$value['haul_parada'].'"';
                $json.=',"tiempo_transito":"'.$value['tiempo_transito'].'"';
                $json.=',"hor_salida":"'.$value['hor_salida'].'"';
                $json.=',"nivel":"'.$value['nivel'].'"';
                $json.=',"ciu_id":"'.$value['ciu_id'].'"';
                $js = $this->getRecursividad_children($_nivel,$value['id2']);
                if(!empty($js)){
                    $json.=',"children":['.trim($js).']';
                }else{
                    $json.=',"leaf":"true"';
                }
                $json.="},";
            }
        }
        return $json;
    }

    public function scm_red_distribucion_graba_horarios($p){
        $rs =$this->objDatos->scm_red_distribucion_graba_horarios($p);
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

    public function get_scm_red_distribucion_dias($p){
        $rs =$this->objDatos->get_scm_red_distribucion_dias($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                if((int)$value['id_elemento']!=0)$array[] = $value;
            }
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($data); 
    }

    public function scm_get_provincia($p){
        $rs =$this->objDatos->scm_get_provincia($p);
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
    public function scm_get_distrito($p){
        $rs =$this->objDatos->scm_get_distrito($p);
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