<?php

/**
    
 *
 * @link    https://github.com/JimmyAnthony/
 * @author  Jimmy Anthony @JimAntho (https://twitter.com/JimAntho)
 * @version 2.0
 * @date 15092014
 */
error_reporting(NULL);
set_time_limit(1000);
ini_set('memory_limit','30M');

class solicitar_enviosController extends AppController {

    private $objDatos;
    private $arrayMenu;
    private $valida;
    private $file_nombre;
    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new solicitar_enviosModels();
    }
    /*
        +---------------+
        |    Add View   |
        +---------------+
    */
    public function form_index($p){        
        $this->view('solicitar_envios/form_index.php', $p);
    }
    /*
        +----------------------+
        |    Add Function  Get |
        +----------------------+
    */
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
    public function get_scm_tipo_servicios($p){
        $rs = $this->objDatos->get_scm_tipo_servicios($p);
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
    public function get_scm_agencia_shipper($p){
        $rs = $this->objDatos->get_scm_agencia_shipper($p);
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
    public function get_scm_contactos($p){
        $rs = $this->objDatos->get_scm_contactos($p);
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
    public function get_scm_cliente_frecuente($p){
        $rs = $this->objDatos->get_scm_cliente_frecuente($p);
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
    public function get_scm_busca_destinatario($p){
        $rs = $this->objDatos->get_scm_busca_destinatario($p);
        
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
    public function get_scm_ciudad($p){
        $rs = $this->objDatos->get_scm_ciudad($p);

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
    public function generate_pdf($p){
        set_time_limit(180);
        ini_set("memory_limit", "-1");

        //$records = json_decode(stripslashes($p['recordsx']));
        
        $path = '/sistemas/weburbano/apps';
        $modulo = '/modules/gestion/views/re_impresion/python/';
        $file = 'rpt_ge.py';
        $file_fisico = 'rpt_ge_'.rand().'.pdf';
        $params = base64_encode($p['recordsx'].'&'.$path.'&Reporte'.'&'.$file_fisico.'&'.USR_ID);

        //echo "python2.7 ".$path.$modulo.$file." ".$params; die();

        $output = array();
        exec("python2.7 ".$path.$modulo.$file." ".$params, $output);
        // var_export($output);

        $archivo = '/sistemas/weburbano/public_html/tmp/'.$file_fisico;

        $path = $archivo;
        $type = '';

        if (is_file($path)) {
            $size = filesize($path);
            
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $file_fisico . '"');
            header('Content-Transfer-Encoding: binary');
            header("Content-Length: " . $size);
            header('Pragma: public');
            header('Cache-control: public');
            readfile($path);
        } else {
            die("File not exist !!");
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
    public function get_scm_detalle_tabla($p){
        $rs = $this->objDatos->get_scm_detalle_tabla($p);
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
    public function get_scm_ss_lista_ge_generadas($p){
        $rs = $this->objDatos->get_scm_ss_lista_ge_generadas($p);
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
    public function get_gis_busca_distrito($p){
        $rs = $this->objDatos->gis_busca_distrito($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }

        /**
         * For debugging
         */
        $debug = $array[count($array) - 1];
        unset($array[count($array) - 1]);

        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array,
            'debug' => $debug
        );
        return $this->response($data);
    }
    public function get_scm_producto_sku($p){
        $rs = $this->objDatos->get_scm_producto_sku($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }

        /**
         * For debugging
         */
        $debug = $array[count($array) - 1];
        unset($array[count($array) - 1]);

        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array,
            'debug' => $debug
        );
        return $this->response($data);
    }
    /*
        +----------------------+
        |    Add Valide  Set   |
        +----------------------+
    */
    public function set_scm_registra($p){
        $rs = $this->objDatos->set_scm_registra($p);

        if((int)$rs[0]['error_sql']==1){
            $p['vp_gui_numero'] = $rs[0]['gui_numero'];

            $records = json_decode(stripslashes($p['vp_recordsToSend'])); //parse the string to PHP objects
            if(isset($records)){
                foreach($records as $record){ 
                    if(isset($record->doc_numero)){ 
                        $p['vp_doc_numero']=$record->doc_numero;
                        $p['vp_tipo_doc']=$record->tipo_doc;
                        $rss = $this->objDatos->set_scm_ss_add_ge_acuse($p);
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
    public function set_scm_ss_registra_cliente($p){
        $rs = $this->objDatos->set_scm_ss_registra_cliente($p);
        
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
    public function rep_exportarPDF(){

    }
}