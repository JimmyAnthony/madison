<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class gestorArchivoController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new gestorArchivoModels();
    }

    public function index($p){        
        $this->view('gestorArchivo/form_index.php', $p);
    }

    public function get_usr_sis_shipper($p){
        $rs = $this->objDatos->usr_sis_shipper($p);
        if (!isset($p['all']))
            $array = array(
                //array('shi_codigo' => 0, 'shi_nombre' => '[ Todos ]', 'shi_id' => '')//carga de archivo
            );
        else
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

    public function get_scm_tabla_detalle($p){
        $rs = $this->objDatos->scm_tabla_detalle($p);
        $array = array(
            array('descripcion' => '[ Todos ]', 'id_elemento' => 0, 'des_corto' => '')
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
            //array('id' => 0, 'nombre' => '[ Todos ]')//carga de archivo
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
        $array = array(
           // array('id_pro' => 0, 'pro_nombre' => '[ Todos ]')
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

    public function form_carga_ftp($p){
        $this->view('gestorArchivo/form_carga_ftp.php', $p);
    }

    public function set_file_ftp($p){
        set_time_limit(1800);
        ini_set("memory_limit", "-1");

        sleep(1);
        $nombre_archivo = $_FILES['gCargaFtp-file']['name'];
        $tipo_archivo = $_FILES['gCargaFtp-file']['type'];
        $tamano_archivo = $_FILES['gCargaFtp-file']['size'];

        $error = 0;
        $error_info = '';

        if ( trim($nombre_archivo) != '' ){
            $extFile = strtolower(trim($tipo_archivo));
            $setTypeFile = array(
                'xls'=>'application/vnd.ms-excel',
                'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            );
            $aleatorio = rand();

            $p['vp_file'] = $nombre_archivo;

            $rs = $this->objDatos->scm_gestor_ftp_upfile($p);
            $sql = $rs[1]['sql'];
            $rs = $rs[0];

            if (intval($rs['error_sql']) >= 0){
                $narchivo = explode('.', $nombre_archivo);
                $nombre_archivo = trim($rs['id_bd']).'.'.$narchivo[1];
                $directorio = "uploads/" . $nombre_archivo;

                if ( in_array($extFile, array( $setTypeFile['xls'], $setTypeFile['xlsx'] )) ){
                    if (move_uploaded_file($_FILES['gCargaFtp-file']['tmp_name'], $directorio)){

                        $file_name = $nombre_archivo;

                        $params = base64_encode(intval($rs['id_bd']).'&'.trim($rs['server_ftp']).'&'.trim($rs['user_ftp']).'&'.trim($rs['key_ftp']).'&'.trim($rs['path_ftp']).'&'.$file_name);

                        $comando = "python2.7 ".PATH."apps/modules/gestion/views/gestorArchivo/python/ftp.py ".$params;
                        
                        $output = array();
                        exec($comando, $output);
                        $error = intval($output[0]);
                    }
                }
            }else{
                $error = intval($rs['error_sql']);
                $error_info = trim($rs['error_info']);
                // unlink(PATH . 'public_html/uploads/' .$nombre_archivo);
            }
        }
        if ($error == 1){
            $p['vp_id_solicitud'] = intval($rs['id_bd']);
            $rs = $this->objDatos->scm_gestor_ftp_upfile_confirma($p);
        }
        $resultado = array(
            'success' => true,
            'error' => $error,
            'error_info' => $error_info,
            'script' => $comando,
            'debug' => $sql
        );
        return $this->response($resultado);
    }

    public function get_scm_gestor_ftp_panel($p){
        $rs = $this->objDatos->scm_gestor_ftp_panel($p);
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