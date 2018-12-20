<?php

/**
    
 *
 * @link    https://github.com/JimmyAnthony/
 * @author  Jimmy Anthony @JimAntho (https://twitter.com/JimAntho)
 * @version 2.0
 */
error_reporting(NULL);
set_time_limit(1000);
ini_set('memory_limit','30M');

class novedadesController extends AppController {

    private $objDatos;
    private $arrayMenu;
    private $valida;
    private $file_nombre;
    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new novedadesModels();
    }
    /*
        +---------------+
        |    Add View   |
        +---------------+
    */
    public function form_panel($p){        
        $this->view('novedades/form_panel.php', $p);
    }
    public function get_panel_estado($p){        
        $this->view('novedades/form_panel_estado.php', $p);
    }
    /*
        +----------------------+
        |    Add Function  Get |
        +----------------------+
    */
    public function get_scm_lista_novedades($p){
        $p['front']=($p['front']=='')?0:$p['front'];
        if((int)$p['front']!=1){
            $rs = $this->objDatos->get_scm_lista_novedades($p);
        }else{
            $rs = $this->objDatos->get_scm_lista_novedades_front($p);
        }
        $array = array();
        if (count($rs) > 0){$x=1;
            foreach($rs as $index => $value){
                $value['msn_texto']=trim($value['msn_texto']);
                if($value['linea']=='LL')$value['class_line']='databox_nodedad_ll';
                if($value['linea']=='MS')$value['class_line']='databox_nodedad_mv';
                if($value['linea']=='VA')$value['class_line']='databox_nodedad_va';

                $value['class_estado']=((int)$value['estado_vt']==1)?'databox_status_on':'databox_status_off';
                $value['val_estado']=((int)$value['estado_vt']==1)?'Nuevo':'';
                $value['class_cerrado']=((int)$value['nov_estado']==2)?'databox_cerrado':'';
                $value['val_cerrado']=((int)$value['nov_estado']==2)?'Cerrado':'';
                $value['doc_numero']=trim($value['doc_numero']);
                $value['usr_codigo']=utf8_encode($value['usr_codigo']);
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
    public function get_scm_lista_comentarios($p){
        $rs = $this->objDatos->get_scm_lista_comentarios($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $value['msn_texto']=trim($value['msn_texto']);
                $value['class_download']= ((int)$value['id_file']!=0)?'dbx_dwl_':'';
                $value['class_visto']= ((int)$value['visto']!=0)?'databox_status_msn_on':'';
                $value['value_visto']= ((int)$value['visto']!=0)?'Nuevo':'';
                $value['class_elimina']= ((int)$value['elimina']!=0)?'dbx_dwl__':'';
                $value['class_user']= ((int)$value['msn_publico']!=0)?'dbx_dwl_more_user':'dbx_dwl_user';
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
    public function get_scm_linea($p){
        $rs = $this->objDatos->get_scm_linea($p);
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
    public function get_scm_check_novedad($p){
        $rs = $this->objDatos->get_scm_check_novedad($p);
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
    public function get_scm_motivo($p){
        $rs = $this->objDatos->get_scm_motivo($p);
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
    public function get_scm_provincias($p){
        $rs = $this->objDatos->get_scm_provincias($p);
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
    public function get_scm_shipper($p){
        $rs = $this->objDatos->get_scm_shipper($p);
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
    public function get_extension($filename){
        $path_parts = pathinfo($filename);
        return '.'.$path_parts['extension'];
    }  
    public function get_forzar_descarga($p){
        $rs = $this->objDatos->set_scm_registro_descarga($p);
        if( (int)$rs[0]['error_sql']>=0){
            $n=$rs[0]['file_nombre'];
            $f = PATH.'public_html/archivo_novedad/'.$n;
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$n\"\n");
            chmod($f, 0750);
            $fp=fopen("$f", "r");
            fpassthru($fp);
        }
    }
    /*
        +----------------------+
        |    Add Function  Set |
        +----------------------+
    */
    public function set_scm_registro($p){

        if($this->set_valida_file($p))return $this->response($this->valida);
        $p['vp_msn_texto'] = trim($this->set_replace_specials_char($p['vp_msn_texto']));
        $p['vp_file_nombre'] = $this->file_nombre;

        $rs = $this->objDatos->set_scm_registro($p);
        if((int)$rs[0]['error_sql']>0)$this->set_file_novedad($p);
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
    public function set_scm_registro_comentario($p){
        if($this->set_valida_file($p))return $this->response($this->valida);
        $p['vp_msn_texto'] = trim($this->set_replace_specials_char($p['vp_msn_texto']));
        $p['vp_file_nombre'] = $this->file_nombre;

        $rs = $this->objDatos->set_scm_registro_comentario($p);
        if((int)$rs[0]['error_sql']>0)$this->set_file_novedad($p);
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
    public function set_scm_registro_cierre($p){
        if($this->set_valida_file($p))return $this->response($this->valida);
        $p['vp_msn_texto'] = trim($this->set_replace_specials_char($p['vp_msn_texto']));
        $p['vp_file_nombre'] = $this->file_nombre;

        $rs = $this->objDatos->set_scm_registro_cierre($p);
        if((int)$rs[0]['error_sql']>0)$this->set_file_novedad($p);
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
    public function set_scm_elimina_comentario($p){
        $rs = $this->objDatos->set_scm_elimina_comentario($p);
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
    /*
        +----------------------+
        |    Add Valide  Set |
        +----------------------+
    */
    public function set_valida_file($p){
        $nombre_file = $_FILES["nov-form-file"]['name'];
        if(!empty($nombre_file)){
            $file_size = $_FILES['nov-form-file']['size'];
            if ((intval($file_size) > 2976912)){
                $this->valida = array(
                    'success' => true,'total' => count($array),'data' => array('error_sql'=>-1,
                    'error_info'=>'El tamaño del archivo supera los 3Mb, por favor seleccione Otro Archivo')
                );
                return 1;
            }else{
                $prefijo = substr(md5(uniqid(rand())),0,100);
                $nombre = 'Urbano_x'.str_replace("/", '', date('d/m/Y')).str_replace(":", '', date('h:i:s'));
                $this->file_nombre = trim($nombre).$this->get_extension($nombre_file);
                return 0;
            }
        }else{
            return 0;
        }
    }
    public function set_file_novedad($p){
        if(trim($this->file_nombre)!='')move_uploaded_file($_FILES['nov-form-file']['tmp_name'], PATH.'public_html/archivo_novedad/'.$this->file_nombre);
    }
    public function set_replace_specials_char($cadena) {
        return eregi_replace("[\n|\r|\n\r]", " ", $cadena);
    }
}