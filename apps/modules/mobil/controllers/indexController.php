<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */
error_reporting(NULL);
set_time_limit(1000);
ini_set('memory_limit','30M');

class indexController extends AppController {

    private $objDatos;
    private $arrayMenu;
    private $coma='';
    private $tree = array();
    private $index = array();
    private $cont = 0;
    private $component = 0;
    function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->objDatos = new indexModels();
    }
    public function valida_mobil($p){
        header("Content-Type: text/plain");
        //$p['ip'] = Common::get_Ip();
        //echo sha1(trim($rs[0]['key']))."___";
        //echo $p["key"]."-".sha1(trim($rs[0]['key_urb'])."".sha1(trim($rs[0]['key'])));
        $rs = $this->objDatos->usr_sis_login_mac($p);
        //var_export($rs);
        if (intval($rs['sql_error']) >= 0 ){
            if(!($p["key"] == sha1(trim($rs[0]['key_urb'])."".sha1(trim($rs[0]['key']))))){
                echo $men =  "{success: true,error:1, errors: 'Su sessión a expirado',close:1}";
                exit();
            }
        }else{
            echo "{success: false, errors: '".trim($rs['msn_error'])."',close:0}";
            exit();
        }
    }

    public function get_list_shipper($p){
        $rs = $this->objDatos->get_list_shipper($p);
        //var_export($this->arrayMenu);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['shi_codigo'] = intval($value['shi_codigo']);
                $value_['shi_nombre'] = trim($value['shi_nombre']) ;
                $value_['shi_logo'] = (trim($value['shi_logo']) == '') ? 'default.png' : $value['shi_logo'];
                $value_['campanas'] = intval($value['campanas']);
                $value_['json']='';
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
    public function get_ext_list_campana_shipper($p){
        $rs = $this->objDatos->get_ext_list_campana_shipper($p);
        //var_export($this->arrayMenu);
        $array = array();
        foreach ($rs as $index => $value){
                $value_['cod_cam'] = intval($value['cod_cam']);
                $value_['id_user'] = intval($value['id_user']);
                $value_['shi_codigo'] = intval($value['shi_codigo']);
                $value_['nombre'] = utf8_encode(trim($value['nombre']));
                $value_['descripcion'] = utf8_encode(trim($value['descripcion']));
                $value_['estado'] = trim($value['estado']) ;
                $value_['imagen'] = (trim($value['imagen']) == '') ? 'default.png' : $value['imagen'];
                $vp['vp_cod_camp']=$value_['cod_cam'];
                $value_['json'] = str_replace('"', "'", $this->get_list_pasos_formulario($vp));
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
    public function PA_tree_Lista($p){
        $result=$this->objDatos->get_list_component_estructura($p);
        
        $data = array();
        $meno=0;
        foreach($result  as $row){
        //foreach ($result as $index => $row){
            //echo $row["idtab"]."<-->".$row["idpadre"]."<br>";
            $data=array(
                    "id" => trim($row["cod_comp_estr"]),
                    "idparent" => trim($row["cod_padre"]),
                    $row["name"] => trim(utf8_encode($row["value"]),$row["cod_comp"])
            );
            $this->addChild($data,$data["idparent"]);

            
        }
        return $this->toJson();
    }

    public function get_list_pasos_formulario($p){
        $rs = $this->objDatos->get_campana_formulario($p);
        $coma="";
        $i=1;
        $count = count($rs);
        if($count!=0){
            $data = "{";
            $data .= '"count":"'.$count.'",';
            foreach ($rs as $index => $value){
                $vp['cod_form']=$value['cod_form'];
                //$vp['cod_padre']='0';
                
                $data .= $coma.'"step'.$i.'":{';
                    $data .= '"fields":[';
                        $data .= $this->get_list_formulario_componente($vp);
                    $data .= '],';
                    $data .= '"title":"'.$value['nombre'].'"';
                    $i++;
                    if($count<$i){
                    }else{
                        $data .= ',"next":"step'.$i.'"';
                    }
                    
                $data .= "}";
                $coma=',';
            }
            $data .= "}";
            header('Content-Type: application/json');
            return $data;
        }else{
            return "";
        }
    }

    public function get_list_formulario_componente($p){
        $rs = $this->objDatos->get_list_formulario_componente($p);
        $coma='';
        foreach ($rs as $index => $value){
            $vp['cod_form']=$value['cod_form'];
            $vp['cod_form_comp']=$value['cod_form_comp'];
            $vp['cod_padre']='0';
            $data .= $coma."{";
            //$data .= '"type:"'.$value['name'].'",';
            $data .= $this->get_formulario_detalle_compomente($vp);
            $data .= "}";
            $coma=',';
        }
        header('Content-Type: application/json');
        return $data;
    }
    public function get_formulario_detalle_compomente($p){
        $rs = $this->objDatos->get_formulario_detalle_compomente($p);
        $coma="";
        $com="";
        $c="";
        $rel=1;
        $cant = count($rs);
        $count=1;
        foreach ($rs as $index => $value){
            $vp['cod_form']=$p['cod_form'];
            $vp['cod_form_comp']=$value['cod_form_comp'];
            $vp['cod_padre']=$value['id_det'];
            $s .=$this->get_formulario_detalle_compomente($vp);
            if(!empty($s)){
                if (trim($value['name']) =='values' || trim($value['name']) =='options'){
                    $data .= ',"'.$value['name'].'":'."[".$s."]";
                }else{
                    $data .= ',"'.$value['name'].'":'."{".$s."}";
                }
            }else{
                if((int)$value['relacion']==0){
                    if((int)$value['cod_relacion']==24){
                        $data .=($coma.'"'.$value['value'].'"');
                    }else{
                        $data .=($coma.'"'.$value['name'].'":"'.$value['value'].'"');
                    }
                }else{
                    if(((int)$value['cod_relacion']==32 && $count==2) || ((int)$value['cod_relacion']==36 && $count==3) ){
                        $datat .=$com.('"'.$value['name'].'":"'.$value['value'].'"');
                        $data .=$c.'{'.$datat.'}';
                        $datat='';
                        $count=1;
                        $com='';
                        $c=',';
                    }else{
                        $datat .=($com.'"'.$value['name'].'":"'.$value['value'].'"');
                        $count=$count+1;
                        $com=',';
                    }
                }
            }
            $s='';
            $coma=',';
        }
        //$this->coma='';

        return $data;
    }
    public function valida($p){
        header("Content-Type: text/plain");
        //$p['ip'] = Common::get_Ip();
        $rs = $this->objDatos->usr_sis_register_mac($p);
        //var_export($rs);
        $rs[0]['key'] = sha1(trim($rs[0]['key']));
        $rs = $rs[0];
        if (intval($rs['sql_error']) >= 0 ){
            $men = "{success: true,error:0,data:".json_encode($rs).", errors: '".utf8_encode(trim($rs['msn_error']))."'}";
        }else{
            $men =  "{success: false, errors: '".utf8_encode(trim($rs['msn_error']))."'}";
        }
        return $men;
    }
    public function get_mobile_gps_demonio($p){
        //$this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->get_mobile_gps_demonio($p);
        $rs = $rs[0];
        if (intval($rs['error_sql']) == 1 ){
            $men = "{success: true,error:0,data:".json_encode($rs).",errors: '".trim($rs['msn_error'])."',close:0}";
        }else{
            $men =  "{success: true,error:1,errors: '".trim($rs['msn_error'])."',close:0}";
        }
        //$this->setCalculoDistanciaDuracion($p);
        return $men;
    }
    public function setCalculoDistanciaDuracion($p){
        $rs = $this->objDatos->get_scm_mobile_agencias($p);
        $com="";
        $origen = $p["pos_px"].",".$p["pos_py"];
        $count=0;
        foreach ($rs as $index => $value){
            $id_agencia[$count]=$value['id_agencia'];
            $destinos .= $com.$value['dir_px'].",".$value['dir_py'];
            $com="|";$count++;
        }
        if(!empty($destinos)){
            $contenido = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origen."&destinations=".$destinos."&mode=driving&language=es&sensor=false");
            $array=json_decode($contenido,true);
            if($array['status']=="OK"){
                $elements = $array['rows'][0]['elements'];
                for($i=0;$i<count($elements);$i++){
                    $distance=$elements[$i]['distance'];
                    $duration=$elements[$i]['duration'];
                    if($elements[$i]['status']=='OK'){
                        $p["id_agencia"]=$id_agencia[$i];
                        $p["dis_text"]=$distance['text'];
                        $p["dis_value"]=$distance['value'];
                        $p["dur_text"]=$duration['text'];
                        $p["dur_value"]=$duration['value'];
                        $this->objDatos->set_scm_mobile_upd_time_gps($p);
                    }
                }
            }
        }
    }
    public function getDataMenuView($p){
        //$this->valida_mobil($p);
        //session_start();
        $_SESSION['sis_id'] = $p['sis_id'];

        $this->objDatos->usr_sis_change_first_sistema($p);
        $p['vp_mod_id'] = 0;
        $p['vp_menu_id'] = 0;
        // $this->objServicios = $this->objDatos->usr_sis_servicios($p);

        $this->arrayMenu = $this->objDatos->usr_sis_menus($p);
        //var_export($this->arrayMenu);
        $array = array();
        foreach ($this->arrayMenu as $index => $value){//var_dump($value);
                $p['vp_mod_id'] = 0;
                $p['vp_menu_id'] = intval($value['id_menu']);

                $value_['id_menu'] = trim($value['id_menu']);
                $value_['orden'] = trim($value['orden']);
                $value_['nombre'] = utf8_encode(trim($value['nombre']));
                $value_['url'] = trim($value['url']);
                $value_['nivel'] = trim($value['nivel']);
                $value_['icono'] = (trim($value['icono']) == '' || trim($value['icono']) == './') ? 'form.png' : $value['icono'];
                $value_['menu_class'] = (trim($value['menu_class']) == '.') ? '' : trim($value['menu_class']);
                //$value['permisos'] = $this->objDatos->usr_sis_servicios($p);
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
    public function get_CuadroMotivosView($p){
        $this->valida_mobil($p);
        //session_start();
        $rs=$this->objDatos->get_CuadroMotivosView($p);

        $array = array();$array_ = array();
        foreach ($rs as $index => $value){
                $array_['mot_id'] = intval($value['mot_id']);
                $array_['chk_id'] = intval($value['chk_id']);
                $array_['chk'] = utf8_encode(trim($value['chk']));
                $array_['chk_descri'] = utf8_encode(trim($value['chk_descri']));
                $array_['orden'] = intval($value['orden']);
                $array[]=$array_;
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

    public function get_servicio($p){
        $this->valida_mobil($p);
        //session_start();
        $rs=$this->objDatos->get_servicio($p);
        $array = array();$array_ = array();
        foreach ($rs as $index => $value){
                $array_['error_sql'] = trim($value['error_sql']);
                $array_['error_info'] = utf8_encode($value['error_info']);
                $array_['id_servicio'] = intval($value['id_servicio']);
                $array_['tipo'] = trim($value['tipo']);
                $array_['secuencia'] = intval(trim($value['secuencia']));
                $array_['direccion'] = utf8_encode(trim($value['direccion'])); 
                $array_['geo_px'] = trim($value['geo_px']); 
                $array_['geo_py'] = trim($value['geo_py']);
                $array_['distrito'] = utf8_encode(trim($value['distrito']));
                $array_['shipper'] = utf8_encode(trim($value['shipper']));
                $array_['contacto'] = utf8_encode(trim($value['contacto']));
                $array_['telefono'] = trim(trim($value['telefono']));
                $array_['peso'] = trim(trim($value['peso']));
                $array_['piezas'] = intval(trim($value['piezas']));
                $array_['horario'] = trim(trim($value['horario']));
                $array_['medio_pago'] = utf8_encode(trim($value['medio_pago']));
                $array_['id_medio_pago'] = intval($value['id_medio_pago']);
                $array_['importe'] = trim(trim($value['importe']));
                $array_['anotaciones'] = utf8_encode(trim($value['anotaciones']));
                $array_['chk'] = utf8_encode(trim($value['chk']));
                $array_['id_novedad'] = intval(trim($value['id_novedad']));
                $array_['guia'] = utf8_encode(trim($value['guia']));
                $array_['shi_codigo'] = intval(trim($value['shi_codigo']));
                $array_['id_cliente'] = intval(trim($value['id_cliente']));
                $array[]=$array_;
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
    public function set_servicio($p){
        $this->valida_mobil($p);
        $rs = $this->objDatos->set_servicio($this->getCalculoDistanciaDuracion($p));
        $rs = $rs[0];
        header("Content-Type: text/plain");
        if (intval($rs['error_sql']) == 1 ){
            $men = "{success: true,error:0,data:".json_encode($rs).",errors: '".trim($rs['error_info'])."',error_info: '".trim($rs['error_info'])."',close:0}";
        }else{
            $men =  "{success: true,error:1,errors: '".trim($rs['error_info'])."',error_info: '".trim($rs['error_info'])."',close:0}";
        }
        return $men;
    }
    public function getCalculoDistanciaDuracion($p){
        $dis_value="1000";
        $dur_value="300";
        $origen = $p["pos_px"].",".$p["pos_py"];
        $destinos = $p["geo_px_ser"].",".$p["geo_py_ser"];

        /*TMP NONE CALCULATE*/
        $p["dis_text"]="";
        $p["dis_value"]=$dis_value;
        $p["dur_text"]="";
        $p["dur_value"]=$dur_value;
        return $p;


        if((float)$p["pos_px"]==0 || (float)$p["pos_py"]==0){
            $p["dis_text"]="";
            $p["dis_value"]=$dis_value;
            $p["dur_text"]="";
            $p["dur_value"]=$dur_value;
            return $p;
        }
        if((float)$p["geo_px_ser"]==0 || (float)$p["geo_py_ser"]==0){
            $p["dis_text"]="";
            $p["dis_value"]=$dis_value;
            $p["dur_text"]="";
            $p["dur_value"]=$dur_value;
            return $p;
        }

        if(!empty($destinos)){
            $contenido = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origen."&destinations=".$destinos."&mode=driving&language=es&sensor=false");
            $array=json_decode($contenido,true);
            if($array['status']=="OK"){
                $elements = $array['rows'][0]['elements'];
                for($i=0;$i<count($elements);$i++){
                    $distance=$elements[$i]['distance'];
                    $duration=$elements[$i]['duration'];
                    if($elements[$i]['status']=='OK'){
                        $p["dis_text"]=$distance['text'];
                        $p["dis_value"]=$distance['value'];
                        $p["dur_text"]=$duration['text'];
                        $p["dur_value"]=$duration['value'];
                        return $p;
                    }else{
                        $p["dis_text"]="";
                        $p["dis_value"]=$dis_value;
                        $p["dur_text"]="";
                        $p["dur_value"]=$dur_value;
                        return $p;
                    }
                }
            }else{
                $p["dis_text"]="";
                $p["dis_value"]=$dis_value;
                $p["dur_text"]="";
                $p["dur_value"]=$dur_value;
                return $p;
            }
        }
    }
    public function set_scm_mobile_upd_descarga($p){
        $this->valida_mobil($p);
        header("Content-Type: text/plain");
        $rs = $this->objDatos->set_scm_mobile_upd_descarga($p);
        $rs = $rs[0];
        if (intval($rs['error_sql']) == 1 ){
            $men = "{success: true,error:0,data:".json_encode($rs).",errors: '".trim($rs['error_info'])."',error_info: '".trim($rs['error_info'])."',close:0}";
        }else{
            $men =  "{success: true,error:1,errors: '".trim($rs['error_info'])."',error_info: '".trim($rs['error_info'])."',close:0}";
        }
        return $men;
    }
    public function set_upload($p){
        $this->valida_mobil($p);
        header("Content-Type: text/plain");
        $target_path = basename( $_FILES['uploadedfile']['name']);

        
        $fecha=(!empty($p['fecha']))?trim($p['fecha']):date('d/m/Y');
        $fecha=explode("/",$fecha);
        $dir = "fotos_iridio/".$fecha[2].$fecha[1].$fecha[0];
        if(!file_exists($dir))mkdir ($dir);
        $dir.='/'.$_FILES['uploadedfile']['name'];
        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$dir)) {
            $rs = $this->objDatos->set_scm_mobile_upd_imagen($p);
            $rs = $rs[0];
            if (intval($rs['error_sql']) == 1 ){
                $men = "{success: true,error:0,data:'Archivo subido correctamente',close:0}";
            }else{
                unlink($dir);
                $men =  "{success: true,error:1, errors: 'Error al registrar la imagen en la bd',close:0}";    
            }
        } else{
            $men =  "{success: true,error:1, errors: 'No se logro subir la imagen al servidor',close:0}";
        }
        return $men;
    }

    /**/
    public function addChild($child,$parentKey = null,$comp){
        $key = isset($child["id"])?$child["id"]:'item_'.$this->cont;
        $child["leaf"] = true;
        if($this->containsKey($parentKey)){
            $this->index[$key] =& $child;
            $parent =& $this->index[$parentKey];
            if(isset($parent["children"])){
                $parent["children"][] =& $child;
            }else{
                $parent["leaf"] = false;
                $parent["children"] = array();
                $parent["children"][] =& $child;
            }
        }else{
            //$this->index[$key] =& $child;
            if($this->component!==$comp){
                $this->index[$key] =& $child;
                $this->tree[] =& $child;
                $this->component=$comp;
            }else{
                array_push($this->index[$key],$child);
                array_push($this->tree,$child);
            }
        }
        $this->cont++;
    }
    public function getNode($key){
        return $this->index[key];
    }
    public function removeNode($key){
        //unset($this->index[key]);
    }
    public function containsKey($key){
        return isset($this->index[$key]);
    }
    public function toJson(){
        return json_encode($this->tree);
    }
}