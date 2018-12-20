<?php

/**
 * @author  Robert Salvatierra (robertsalvatierraq@gmail.com)
 */
require_once PATH . "libs/PHPExcel/PHPExcel/IOFactory.php";

class georefController extends AppController {

    public $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new georefModels();
    }

    public function index($p){        
        $this->view('georef/from_index.php', $p);
    }
    
    public function show_re_imprimir($p){        
        $this->view('georef/re_impresion.php', $p);
    }
    public function scm_dowload($p){        
        $this->view('georef/dowload.php', $p);
    }    
    public function scm_dowload_all($p){        
        $this->view('georef/dowload_all.php', $p);
    }    
    public function scm_upload($p){        
        $this->view('georef/uploadxls.php', $p);
    }    


    public function get_usr_sis_provincias($p){
        $rs = $this->objDatos->usr_sis_provincias($p);
        $array = array(
            array('prov_codigo' => 0, 'prov_nombre' => '[ Todos ]', 'prov_sigla' => '')
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

    public function scm_scm_georeferenciar_select($p){
        $rs = $this->objDatos->scm_georeferenciar_select($p);
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

    public function scm_scm_georeferenciar_dowload_all($p){
        $rs = $this->objDatos->scm_georeferenciar_dowload_all($p);
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

    public function scm_scm_georeferenciar_dowload($p){
        $rs = $this->objDatos->scm_georeferenciar_dowload($p);
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

    public function uploadExcel($p){
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        sleep(1);
        $nombre_archivo = $_FILES['uploadxls-file']['name'];
        $tipo_archivo = $_FILES['uploadxls-file']['type'];
        $tamano_archivo = $_FILES['uploadxls-file']['size'];


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
                if (@move_uploaded_file($_FILES['uploadxls-file']['tmp_name'], $dir)){
                    $objPHPExcel = PHPExcel_IOFactory::load($dir);
                    //print_r($objPHPExcel);
                    $objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);//activa la primera hoja activa
                    //echo $objPHPExcel->getActiveSheet()->getCell('F2')->getValue();die();
                    //$path_temporal = $dir.'.txt';
                    //$fp =fopen($path_temporal,"wb") or die("Problemas en la creacion");
                    //header('content-type: text/html; charset: utf-8');
                    //var_export($objHoja);die();
                    $err_cnt = 0;
                    foreach ($objHoja as $iIndice => $objCelda){
                        
                        if ($iIndice <>1){
                            $h['vp_dir_id'] = $objPHPExcel->getActiveSheet()->getCell('A'.$iIndice)->getValue();//(string) $objCelda['A'];
                            $h['vp_ubigeo'] = $objPHPExcel->getActiveSheet()->getCell('F'.$iIndice)->getValue();//(string) $objCelda['F'];
                            $h['vp_x'] = $objPHPExcel->getActiveSheet()->getCell('I'.$iIndice)->getValue();//(string) trim($objCelda['I']);
                            $h['vp_y'] = $objPHPExcel->getActiveSheet()->getCell('J'.$iIndice)->getValue();//(string) trim($objCelda['J']);
                            $h['vp_err'] = $objPHPExcel->getActiveSheet()->getCell('K'.$iIndice)->getValue();//(string) trim($objCelda['K']);
                            $rs = $this->objDatos->scm_georeferenciar_update($h);
                            //echo $rs[0]['error_sql'];
                            if ($rs[0]['error_sql'] == -1){
                                $err_cnt=$err_cnt+1;
                            }
                        }
                    }
                    //echo $err_cnt;die();
                    //fclose($fp);
                    if ($err_cnt == 0){
                        $file = array( 'error' => '1', 'msg' => 'Archivo Subido Correctamente..','icon'=>'1' );
                        $succes = true;    
                    }else{
                        $file = array( 'error' => '-1', 'msg' => 'Archivo Subido Correctamente.. </br>Se Encontraron '.$err_cnt.' Ubigeos Incorrectos','icon'=>'0' );
                        $succes = true;    
                    }
                    unlink($dir);//para eliminar el execl
                }else{
                    $file = array( 'error' => '1', 'msg' => 'Error al intentar subir archivo!!','icon'=>'0' );
                    $succes = true;
                }
            }else{
              $file = array( 'error' => '1', 'msg' => 'Extension de archivo no permitida!!','icon'=>'0' );
              $succes = true;
            }
         }else{
            $file = array( 'error' => '1', 'msg' => 'Error al intentar subir archivo!!','icon'=>'0' );
            $succes = true;
         }

          $resultado = array(
            'success' => true,
            'data' => $file
        );
        return $this->response($resultado);
    }     


    
   

}