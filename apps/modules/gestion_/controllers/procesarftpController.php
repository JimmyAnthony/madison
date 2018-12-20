<?php

/**
 * @link    
 * @author  
 * @version 2.0
 */
require_once PATH . "libs/PHPExcel/PHPExcel/IOFactory.php";

class procesarftpController extends AppController {

    public $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new procesarftpModels();
    }

    public function index($p){        
        $this->view('procesar_ftp/form_index.php', $p);
    }

    public function get_usr_sis_shipper($p){
        $rs = $this->objDatos->usr_sis_shipper($p);
        $array = array(
            array('shi_codigo' => 0, 'shi_nombre' => '[ Todos ]', 'shi_id' => '')
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
            array('descripcion' => '[ Todos ]', 'id_elemento' => 100, 'des_corto' => '')
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

    public function get_usr_sis_linea_negocio($p){
        $rs = $this->objDatos->usr_sis_linea_negocio($p);
        $array = array(
            array('id' => 0, 'nombre' => '[ Todos ]')
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

    public function get_usr_sis_productos($p){
        $rs = $this->objDatos->usr_sis_productos($p);
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
    
    public function scm_gestor_consulta_ftp($p){
        $rs = $this->objDatos->gestor_consulta_ftp($p);
        
        $offset = isset($p['start']) ? $p['start'] : 0;
        $size = isset($p['limit']) ? $p['limit'] : 2;

        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => array_splice($array,$offset, $size)
        );
        return $this->response($data);
    }

    public function procesarftp_procesar_datos($p){
    
        $params = base64_encode(intval($p['id_solicitud']));
        //echo $params;die();
        $comando = "python2.7 ".PATH."apps/modules/gestion/views/procesar_ftp/python/ftp_file_procesar.py ".$params;
        //echo $comando;die();
        $output = array();
        exec($comando, $output);
        //$error = intval($output[0]);
        //echo  $error;
        
        $error = 0;
        $error_info='Archivo correctamente procesado';
        $rs = $this->objDatos->gestor_ftp_procesar($p);
       // print_r($re) ;die();
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }

        $resultado = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );

        return $this->response($resultado);
    }
    public function gestor_ftp_pu($p){
        $rs =$this->objDatos->scm_gestor_ftp_pu($p);
        $array = array();
        if (count($rs) > 0){
            foreach ($rs as $index => $value) {
                $array[]=$value;
            }
        }

        $resultado= array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($resultado);
    }
   
    public function uploadExcel($p){
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        sleep(1);
        $nombre_archivo = $_FILES['uploadExcel-file']['name'];
        $tipo_archivo = $_FILES['uploadExcel-file']['type'];
        $tamano_archivo = $_FILES['uploadExcel-file']['size'];


         if ( trim($nombre_archivo) != '' ){
              $arrayFile = explode('.', $nombre_archivo);
              $nameFile = trim($arrayFile[0]);
              $extFile = strtolower(trim($arrayFile[1]));
              $dir = "uploads/" . $nombre_archivo;
              $setTypeFile = array(
               'xls'=>'application/vnd.ms-excel',
               'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
              );

            if (in_array($tipo_archivo,array($setTypeFile['xls'], $setTypeFile['xlsx'] ))){
                if (@move_uploaded_file($_FILES['uploadExcel-file']['tmp_name'], $dir)){
                    $objPHPExcel = PHPExcel_IOFactory::load($dir);
                    $objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);//activa la primera hoja activa
                    /**/$path_temporal = $dir.'.txt';
                    $fp =fopen($path_temporal,"wb") or die("Problemas en la creacion");
                    header('content-type: text/html; charset: utf-8');
                      
                    

                    foreach ($objHoja as $iIndice => $objCelda) {

                        if ($iIndice <>1){
                            $lin = $objCelda['A'];
                            //echo $dato1.'</br>';
                          
                            $chr = array(chr(194),chr(130),chr(194),chr(160),chr(13));
                            $lin =  str_replace($chr, '', $lin);
                          //  print $lin;
                            //$text = str_pad(substr($lin, 0,50),50);
                           // $text =$lin;
                         //   $text = eregi_replace("[\n|\r|\n\r]", ' ' , $text ); 
                           // $text = utf8_decode($lin);
                           // $text = utf8_encode($text);
                        }
                       //  header('content-type: text/html; charset: utf-8');
                       // fputs($fp,$text);
                       // fputs($fp,"\n");
                    }
                    fclose($fp);
                    $file = array( 'error' => '1', 'msg' => 'Archivo Subido Correctamente..' );
                    $succes = true;
                   // unlink($dir);
                }else{
                    $file = array( 'error' => '1', 'msg' => 'Error al intentar subir archivo!!' );
                    $succes = true;
                }
            }else{
              $file = array( 'error' => '1', 'msg' => 'Extension de archivo no permitida!!' );
              $succes = true;
            }
         }else{
            $file = array( 'error' => '1', 'msg' => 'Error al intentar subir archivo!!' );
            $succes = true;
         }

          $resultado = array(
            'success' => true,
            'data' => $file
        );
        return $this->response($resultado);
    }

    public function  form_upload($p){
        $this->view('procesar_ftp/upload.php', $p);    
    }

    public function form_impresion($p){
        $this->view('procesar_ftp/imprimir.php', $p);       
    } 

    public function form_show_total_error($p){
        $this->view('procesar_ftp/total_error.php', $p);       
    } 

    public function popup_tarch_id_4($p){
        $this->view('procesar_ftp/popup_tarch_id_4.php', $p);         
    }
    public function get_scm_tabla_detalle_filtro($p){
        $rs = $this->objDatos->scm_tabla_detalle($p);
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

    public function grid_reclamos($p){
        $rs = $this->objDatos->scm_gestor_error_reporte($p);
        $offset = isset($p['start']) ? $p['start'] : 1;
        $size = isset($p['limit']) ? $p['limit'] : 100;

        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;

            }
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => array_splice($array,$offset, $size)
        );
        return $this->response($data);
    }

    public function ftp_procesar_lista_etiquetas($p){
        $rs = $this->objDatos->scm_gestor_ftp_procesar_lista_etiquetas($p);
        $array = array();
        if (count($rs) > 0){
            foreach ($rs as $index => $value) {
                $array[]=$value;
            }
        }

        $resultado= array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return $this->response($resultado);
    }

    public function get_excel_reclamos($p){
        $this->view('procesar_ftp/reportes/excel_reclamos.php', $p);         
    }

    public function restar($p){
        $this->report_python($p,'python2.7 /sistemas/weburbano/apps/modules/gestion/views/procesar_ftp/imprimir_guia/etiqueta/etiquetaX1.py');
    }

    public function report_python($p,$ruta_py){
        set_time_limit(0);
        ini_set("memory_limit", "-1");

        $params = base64_encode(trim($p['id_solicitud']).'&'.USR_ID);
        $output = array();
         //echo $ruta_py.' '.$params;die();
        exec($ruta_py.' '.$params, $output);
        $file = 'etiqueta-' . trim($p['id_solicitud']).'.pdf';
        $archivo = PATH.'public_html/tmp/etiqueta_acuse/'.$file;
        $path = $archivo;
        $type = '';
        if (is_file($path)) {
            $size = filesize($path);
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $file . '"');
            header('Content-Transfer-Encoding: binary');
            header("Content-Length: " . $size);
            header('Pragma: public');
            header('Cache-control: public');
            readfile($path);
            unlink($path);
        } else {
            die("File not exist !!");
        }
    }

    public function scm_estandar($p){
        $this->report_python($p,'python2.7 /sistemas/weburbano/apps/modules/gestion/views/procesar_ftp/imprimir_guia/etiqueta/etiquetaX64.py');
    }
    public function scm_estandar_continuo($p){
        $this->report_python($p,'python2.7 /sistemas/weburbano/apps/modules/gestion/views/procesar_ftp/imprimir_guia/etiqueta/etiquetaX1.py');
    }
    public function scm_electronica_estandar($p){
        $this->report_python($p,'python2.7 /sistemas/weburbano/apps/modules/gestion/views/procesar_ftp/imprimir_guia/acuse/guiaelectronicax2.py');
    }

}