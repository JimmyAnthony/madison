<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class shipperController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new shipperModels();
    }

    public function index($p){        
        $this->view('shipper/form_index.php', $p);
    }


    public function get_list_shipper($p){
        $rs = $this->objDatos->get_list_shipper($p);
        //var_export($rs);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['shi_codigo'] = intval($value['shi_codigo']);
                $value_['shi_nombre'] = utf8_encode(trim($value['shi_nombre']));
                $value_['shi_logo'] = (trim($value['shi_logo']) == '') ? 'default.png' : $value['shi_logo'];
                $value_['campanas'] = intval($value['campanas']);
                $value_['fec_ingreso'] = trim($value['fec_ingreso']) ;
                $value_['shi_estado'] = trim($value['shi_estado']) ;
                $value_['id_user'] = trim($value['id_user']) ;
                $value_['fecha_actual'] = trim($value['fecha_actual']) ;
                $array[]=$value_;
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

    public function get_list_permisos_mac($p){
        $rs = $this->objDatos->get_list_permisos_mac($p);
        //var_export($rs);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['mac_ip'] = trim($value['mac_ip']);
                $value_['mac_vence'] = trim($value['mac_vence']);
                $value_['mac_estado'] = trim($value['mac_estado']) ;
                $value_['id_mac'] = trim($value['id_mac']) ;
                $array[]=$value_;
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
    public function setChangeEstado($p){
        header('Content-Type: application/json');
        $rs = $this->objDatos->setChangeEstado($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
        }else{
            //unlink($dir);
            $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";    
        }
        return $men;
    }

    public function setRegisterShipper($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $target_path = basename( $_FILES['uploadedfile']['name']);

        if(!empty($_FILES['uploadedfile']['name'])){
            $aleatorio = rand();
            $narchivo = explode('.', $_FILES['uploadedfile']['name']);
            $nombre_archivo = 'shipper_'.$aleatorio.'.'.$narchivo[1];
            $dir = "shipper/" . $nombre_archivo;
            $p['vp_shi_logo']=$nombre_archivo;

            if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$dir)) {
                $rs = $this->objDatos->setRegisterShipper($p);
                $rs = $rs[0];
                //var_export($rs);
                if ($rs['status'] == 'OK' ){
                    $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
                }else{
                    unlink($dir);
                    $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";    
                }
            } else{
                $men =  "{success: true,error:1, errors: 'No se logro subir la imagen al servidor',close:0}";
            }
        }else{
            $rs = $this->objDatos->setRegisterShipper($p);
            $rs = $rs[0];
            //var_export($rs);
            if ($rs['status'] == 'OK' ){
                $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
            }else{
                //unlink($dir);
                $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";    
            }
        }
        return $men;
    }

    public function get_list_campana_shipper($p){
        $rs = $this->objDatos->get_list_campana_shipper($p);
        //var_export($rs);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['cod_cam'] = intval($value['cod_cam']);
                $value_['nombre'] = utf8_encode(trim($value['nombre']));
                $value_['descripcion'] = utf8_encode(trim($value['descripcion']));
                $value_['imagen'] = (trim($value['imagen']) == '') ? 'default.png' : $value['imagen'];
                $array[]=$value_;
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
}