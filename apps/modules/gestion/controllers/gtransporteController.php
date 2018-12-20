<?php

class gtransporteController extends AppController {
    private $objDatos;
    private $arrayMenu;

    public function __construct(){
    	$this->valida();
    	$this->objDatos = new gtransporteModels();
    }

    public function index($p){
    	//$this->view('/gtransporte/gtransporte.php', $p);
        $this->view('/gtransporte/panelHomeDelivery.php', $p);
        
    }

    public function form_transporte($p){
    	$this->view('/gtransporte/panel_transporte.php', $p);	
    }

    public function form_orden($p){
    	$this->view('/gtransporte/panel_orden.php', $p);	
    }

    public function form_p_servicio_transporte($p){
    	$this->view('/gtransporte/p_servicio_transporte.php', $p);	
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

    public function scm_scm_home_delivery_unidades($p){
        $rs = $this->objDatos->scm_home_delivery_unidades($p);
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

    public function scm_scm_home_delivery_servicios($p){
        $rs = $this->objDatos->scm_home_delivery_servicios($p);
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

    public function scm_scm_home_delivery_paradas($p){
        $rs = $this->objDatos->scm_home_delivery_paradas($p);
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

    public function scm_scm_home_delivery_unidad_gps($p){
        $rs = $this->objDatos->scm_home_delivery_unidad_gps($p);
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

    public function scm_scm_home_delivery_add_ruta($p){
        if((int)$p['vp_transferencia']==0){
            $rs = $this->objDatos->scm_home_delivery_add_ruta($p);
        }else{
            $json_ = str_replace('\\', '', trim($p['vp_json']));
            $json = json_decode($json_);
            $error = 0;
            foreach($json as $index => $value){
                $p['vp_id_age'] = $value->vp_id_agencia;
                $rs = $this->objDatos->scm_home_delivery_add_ruta($p);
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
    public function scm_home_delivery_pendientes($p){
        $rs = $this->objDatos->scm_home_delivery_pendientes($p);
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

    public function scm_scm_home_delivery_upd_origen($p){
        $rs = $this->objDatos->scm_home_delivery_upd_origen($p);
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

    public function scm_scm_home_delivery_lista_ruta($p){
        $rs = $this->objDatos->scm_home_delivery_lista_ruta($p);
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

    public function scm_scm_home_delivery_panel($p){
        $rs = $this->objDatos->scm_home_delivery_panel($p);
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
        return $this->setCalculoDistanciaDuracion($p,$this->objDatos->scm_home_delivery_agencia_shipper($p));
    }

    public function setCalculoDistanciaDuracion($p,$rs){
        $com="";
        $origen = $p["pos_px"].",".$p["pos_py"];
        $count=0;
        foreach ($rs as $index => $value){
            $id_agencia[$count]=$value['id_agencia'];
            $destinos .= $com.$value['dir_px'].",".$value['dir_py'];
            $com="|";$count++;
        }
        $arrayData = array();
        if(!empty($destinos)){
            $contenido = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origen."&destinations=".$destinos."&mode=driving&language=es&sensor=false");
            $array=json_decode($contenido,true);
            //var_export($contenido);
            if($array['status']=="OK"){
                $elements = $array['rows'][0]['elements'];
                //for($i=0;$i<count($elements);$i++){
                $i=0;
                foreach ($rs as $key => $value) {
                    $distance=$elements[$i]['distance'];
                    $duration=$elements[$i]['duration'];
                    if($elements[$i]['status']=='OK'){
                        //$file["id_agencia"]=$id_agencia[$i];
                        $value["dis_text"]=$distance['text'];
                        $value["dis_value"]=$distance['value'];
                        $value["dur_text"]=$duration['text'];
                        $value["dur_value"]=$duration['value'];
                        $value["time_total"] = $value["gps_time_s"] + $duration['value'];
                        $value["time_t"] = $this->toHours($value["time_total"]);
                        if(!empty($p['vp_id_agencia'])){
                            if((int)$p['vp_id_agencia']!=0){
                                $value["und_px"]=$p['und_px'];
                                $value["und_py"]=$p['und_py'];
                                $value["und_placa"]=$p['und_placa'];
                            }
                        }
                        $arrayData[] = $value;
                    }
                    $i++;
                }
            }
        }
        $record = $this->array_sort($arrayData, 'time_total', SORT_ASC);
        foreach ($record as $key => $value) {
            $records[]=$value;
        }

        $data = array(
            'success' => true,
            'total' => count($records),
            'data' => $records
        );
        return $this->response($data);
    }
    public function toHours($seconds){
        $hours = floor($seconds / (60 * 60));
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);
        $obj = array(
            "h" => (int) $hours,
            "m" => (int) $minutes,
            "s" => (int) $seconds,
        );
        $tiempo="";
        if((int)$hours>0)$tiempo=$hours."h";
        $tiempo.=" ".$minutes." min";
        $tiempo.=" ".$seconds." seg";
        return $tiempo;
    }
    public function array_sort($array, $on, $order=SORT_ASC){
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    public function scm_scm_home_delivery_unidad_gps_distance($p){
        $rs = $this->objDatos->scm_home_delivery_unidad_gps_distance($p);
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
