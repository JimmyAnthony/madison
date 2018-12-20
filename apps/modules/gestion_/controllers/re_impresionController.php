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

class re_impresionController extends AppController {

    private $objDatos;
    private $arrayMenu;
    private $valida;
    private $file_nombre;
    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new re_impresionModels();
    }
    /*
        +---------------+
        |    Add View   |
        +---------------+
    */
    public function form_index($p){        
        $this->view('re_impresion/form_index.php', $p);
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
    public function get_scm_lista_ge($p){
        $rs = $this->objDatos->get_scm_lista_ge($p);
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
        |    Add Report  Get   |
        +----------------------+
    */
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
    /*
        +----------------------+
        |    Add Valide  Set   |
        +----------------------+
    */
    
}