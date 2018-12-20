<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class formularioGestionController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new formularioGestionModels();
    }

    public function index($p){        
        $this->view('formularioGestion/form_index.php', $p);
    }

    public function get_list_formularios($p){
        $rs = $this->objDatos->get_list_formularios($p);
        //var_export($rs);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['cod_form'] = intval($value['cod_form']);
                $value_['nombre'] = utf8_encode(trim($value['nombre']));
                $value_['descripcion'] = utf8_encode(trim($value['descripcion']));
                $value_['estado'] = intval($value['estado']);
                $value_['fec_crea'] = trim($value['fec_crea']) ;
                $value_['fec_mod'] = trim($value['fec_mod']) ;
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
    public function get_list_formulario_componente($p){
        $rs = $this->objDatos->get_list_formulario_componente($p);
        //var_export($rs);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['cod_form_comp'] = intval($value['cod_form_comp']);
                $value_['cod_form'] = intval($value['cod_form']);
                $value_['cod_comp'] = intval($value['cod_comp']);
                $value_['orden'] = intval($value['orden']);
                $value_['value'] = utf8_encode(trim($value['value']));
                $value_['cod_type'] = intval($value['cod_type']);
                $value_['name'] = utf8_encode(trim($value['name']));
                $value_['nameview'] = utf8_encode(trim($value['nameview']));
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
    public function get_formulario_detalle($p){
        $rs = $this->objDatos->get_formulario_detalle($p);
        //var_export($rs);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['id_det'] = intval($value['id_det']);
                $value_['cod_form_comp'] = intval($value['cod_form_comp']);
                $value_['cod_form'] = intval($value['cod_form']);
                $value_['cod_comp_estr'] = intval($value['cod_comp_estr']);
                $value_['name'] = utf8_encode(trim($value['name']));
                $value_['value'] = utf8_encode(trim($value['value']));
                $value_['nivel'] = intval($value['nivel']);
                $value_['cod_relacion'] = intval($value['cod_relacion']);
                $value_['cod_padre'] = intval($value['cod_padre']);
                $value_['estado'] = intval($value['estado']);
                $value_['mant'] = 'E'; 
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
    public function set_insert_formulario_detalle($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_insert_formulario_detalle($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
        }else{
            unlink($dir);
            $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";   
        }
        return $men;
    }
    public function set_insert_only_one_formulario_detalle($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_insert_only_one_formulario_detalle($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
        }else{
            $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";   
        }
        return $men;
    }
    public function set_insert_chk_rdb_only_one_formulario_detalle($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_insert_chk_rdb_only_one_formulario_detalle($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
        }else{
            $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";   
        }
        return $men;
    }
    public function set_update_only_one_formulario_detalle($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_update_only_one_formulario_detalle($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
        }else{
            $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";   
        }
        return $men;
    }
    public function set_delete_only_one_formulario_detalle($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_delete_only_one_formulario_detalle($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
        }else{
            $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";   
        }
        return $men;
    }
    public function set_delete_chk_rdb_only_one_formulario_detalle($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_delete_chk_rdb_only_one_formulario_detalle($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
        }else{
            $men =  "{success: true,error:1, errors: 'Error al registrar la información',close:0}";   
        }
        return $men;
    }
    public function set_delete_formulario_detalle($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_delete_formulario_detalle($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se elimino correctamente',close:0}";
        }else{
            unlink($dir);
            $men =  "{success: true,error:1, errors: 'Error al eliminar registro',close:0}";   
        }
        return $men;
    }
    /*
        +----------------------+
        |    Add Valide  Set   |
        +----------------------+
    */
    public function set_update_formulario_detalle($p){
        $records = json_decode(stripslashes($p['vp_recordsToSend'])); //parse the string to PHP objects
        if(isset($records)){
            foreach($records as $record){ 
                $p['id_det']=$record->id_det;
                $p['cod_form_comp']=$record->cod_form_comp;
                $p['cod_form']=$record->cod_form;
                $p['cod_comp_estr']=$record->cod_comp_estr;
                $p['name']=$record->name;
                $p['value']=$record->value;
                $p['nivel']=$record->nivel;
                $p['cod_padre']=$record->cod_padre;
                $p['estado']=$record->estado;
                $rs = $this->objDatos->set_update_formulario_detalle($p);
            }
            if ($rs[0]['status'] == 'OK' ){
                $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
            }else{
                $men =  "{success: true,error:1, errors: 'Error al guardar la información',close:0}";   
            }
        }else{
            $men =  "{success: true,error:1, errors: 'No existen registros que guardar',close:0}";   
        }
        return $men;
    }


    public function set_formulario($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_formulario($p);
        $rs = $rs[0];
        //var_export($rs);
        if ($rs['status'] == 'OK' ){
            $men = "{success: true,error:0,data:'Información se guardo correctamente',close:0}";
        }else{
            unlink($dir);
            $men =  "{success: true,error:1, errors: 'Error al guardar el registro',close:0}";   
        }
        return $men;
    }

    public function get_list_componente($p){
        $rs = $this->objDatos->get_list_componente($p);
        //var_export($rs);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['cod_comp'] = intval($value['cod_comp']);
                $value_['cod_comp'] = intval($value['cod_comp']);
                $value_['name'] = utf8_encode(trim($value['name']));
                $value_['nameview'] = utf8_encode(trim($value['nameview']));
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
                unlink($dir);
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