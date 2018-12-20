<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class preclamosController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new preclamosModels();
    }

    public function index($p){        
        $this->view('preclamos/form_index.php', $p);
    }

    public function form_carga($p){
        $this->view('preclamos/form_carga.php', $p);
    }

    public function form_control_impresiones($p){
        $this->view('preclamos/form_control_impresiones.php', $p);
    }

    public function get_usr_sis_provincias($p){
        $rs = $this->objDatos->usr_sis_provincias($p);
        if (!isset($p['all']))
            $array = array(
                array('prov_codigo' => 0, 'prov_nombre' => '[ Todos ]', 'prov_sigla' => '')
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

    public function get_scm_reclamo_panel($p){
        $rs = $this->objDatos->scm_reclamo_panel($p);
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

    public function upload_file($p){
        set_time_limit(180);
        ini_set("memory_limit", "-1");

        sleep(1);
        $nombre_archivo = $_FILES['cargaReclamo-file']['name'];
        $tipo_archivo = $_FILES['cargaReclamo-file']['type'];
        $tamano_archivo = $_FILES['cargaReclamo-file']['size'];

        if ( trim($nombre_archivo) != '' ){
            $extFile = strtolower(trim($tipo_archivo));
            $setTypeFile = array(
                'xls'=>'application/vnd.ms-excel',
                'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            );
            $getTypeFile = array(
                'application/vnd.ms-excel'=>'xls',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'=>'xlsx'
            );
            $aleatorio = rand();
            $narchivo = explode('.', $nombre_archivo);
            $nombre_archivo = $narchivo[0].'_'.$aleatorio.'.'.$narchivo[1];
            $directorio = "uploads/" . $nombre_archivo;

            if ( in_array($extFile, array( $setTypeFile['xls'], $setTypeFile['xlsx'] )) ){
                if (move_uploaded_file($_FILES['cargaReclamo-file']['tmp_name'], $directorio)){

                    $params = base64_encode(trim($nombre_archivo).'&'.USR_ID.'&'.$aleatorio.'&'.intval($p['vp_linea']));

                    $comando = "python2.7 /sistemas/weburbano/apps/modules/gestion/views/preclamos/python/carga_masiva.py ".$params;

                    $output = array();
                    exec("python2.7 /sistemas/weburbano/apps/modules/gestion/views/preclamos/python/carga_masiva.py ".$params, $output);

                    $file = array( 'error' => '', 'msg' => '' );
                }
            }
        } else{
            $file = array( 'error' => '1', 'msg' => 'Error no existe nombre del archivo !!' );
        }
        $data = array(
            'success' => true,
            'file' => $file,
            'output' => $output,
            'comando' => $comando
        );
        return json_encode($data);
    }

    public function set_reclamo_masivo($p){
        $rs = Common::UTF8($this->objDatos->reclamos_erroneos($p));
        $error = 0;
        if (count($rs) > 0){
            $p['vp_id_libro'] = '0';
            foreach($rs as $index => $value){
                if (intval($value['error_sql']) == 0){
                    $p['vp_guia'] = intval($value['guia']);
                    $p['vp_mot_rec'] = trim($value['mot_reclamo']);
                    $p['vp_det_rec'] = trim($value['det_reclamo']);
                    $data = $this->objDatos->scm_reclamo_grabar($p);
                    if (intval($data[0]['error_sql']) != 0)
                        $error = 1;
                    $p['vp_id_libro'] = $data[0]['id_libro'];
                }
            }
        }
        $array = array('error' => $error);
        return json_encode($array);
    }

    public function get_reclamos_erroneos($p){
        $rs = Common::UTF8($this->objDatos->reclamos_erroneos($p));
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                if (intval($value['error_sql']) != 0)
                    $array[] = $value;
            }
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return json_encode($data);
    }

    public function get_scm_reclamo_lista_impresiones($p){
        $rs = $this->objDatos->scm_reclamo_lista_impresiones($p);
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

    public function get_scm_reclamo_impresion($p){
        $rs = $this->objDatos->scm_reclamo_impresion($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }
        return $this->response($array[0]);
    }

    public function get_rpt_auditoria($p){
        set_time_limit(180);
        ini_set("memory_limit", "-1");

        $aleatorio = rand();

        $params = base64_encode(trim($p['vp_prov']).'&'.trim($p['vp_id_imp']).'&'.trim($p['vp_fecini']).'&'.trim($p['vp_id_tipo']).'&'.USR_ID.'&'.$aleatorio.'&'.$p['vp_linea'].'&'.$p['vp_nivel']);

        //echo "python2.7 /sistemas/weburbano/apps/modules/gestion/views/preclamos/python/rpt_auditoria.py ".$params; die();

        $output = array();
        exec("python2.7 /sistemas/weburbano/apps/modules/gestion/views/preclamos/python/rpt_auditoria.py ".$params, $output);
        
        $file = 'rpt_auditoria-' . $aleatorio.'.pdf';
        $archivo = PATH.'public_html/tmp/reclamos/'.$file;
        //echo $file;
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
        } else {
            die("File not exist !!");
        }
    }

    public function get_rpt_etiqueta($p){
        set_time_limit(180);
        ini_set("memory_limit", "-1");

        $aleatorio = rand();

        $params = base64_encode(trim($p['vp_prov']).'&'.trim($p['vp_id_imp']).'&'.trim($p['vp_fecini']).'&'.trim($p['vp_id_tipo']).'&'.USR_ID.'&'.$aleatorio.'&'.$p['vp_linea'].'&'.$p['vp_nivel']);

     //  echo "python2.7 /sistemas/weburbano/apps/modules/gestion/views/preclamos/python/rpt_etiquetas_30.py ".$params; die();

        $output = array();
        exec("python2.7 /sistemas/weburbano/apps/modules/gestion/views/preclamos/python/rpt_etiquetas_30.py ".$params, $output);

        $file = 'rpt_etiquetas-' . $aleatorio.'.pdf';
        $archivo = PATH.'public_html/tmp/reclamos/'.$file;

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
        } else {
            die("File not exist !!");
        }
    }

    public function form_aureclamo($p){
        $this->view('preclamos/form_aureclamo.php', $p);
    }

    public function get_scm_reclamo_en_ld($p){
        $rs = $this->objDatos->scm_reclamo_en_ld($p);
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

    public function get_scm_reclamo_qry_auditorias($p){
        $rs = $this->objDatos->scm_reclamo_qry_auditorias($p);
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

    public function get_scm_lista_personal($p){
        $rs = $this->objDatos->scm_lista_personal($p);
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

    public function get_scm_reclamo_audi_check($p){
        $rs = $this->objDatos->scm_reclamo_audi_check($p);
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

    public function get_scm_reclamo_audi_editar($p){
        $rs = $this->objDatos->scm_reclamo_audi_editar($p);
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

    public function form_nuevo_manifiesto($p){
        $this->view('preclamos/form_nuevo_manifiesto.php', $p);
    }

    public function get_barra($p){
        if (intval($p['vp_man_id']) == 0){
            $rs = $this->objDatos->scm_reclamo_audi_nuevo($p);
            $p['vp_man_id'] = $rs[0]['man_id'];
        }
        $rs = $this->objDatos->scm_reclamo_audi_scaneo($p);
        $rs[0]['man_id'] = $p['vp_man_id'];
        $rs = $rs[0];
        return json_encode($rs);
    }

    public function set_scm_reclamo_audi_graba($p){
        $rs = $this->objDatos->scm_reclamo_audi_graba($p);
        return $this->response($rs[0]);
    }

    public function set_scm_reclamo_audi_anular($p){
        $rs = $this->objDatos->scm_reclamo_audi_anular($p);
        return $this->response($rs[0]);
    }

    public function form_descarga_man($p){
        $this->view('preclamos/form_descarga_man.php', $p);
    }

    public function get_scm_reclamo_qry_persona_aud($p){
        $rs = $this->objDatos->scm_reclamo_qry_persona_aud($p);
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

    public function set_graba_man($p){
        $array = array();
        $rs = $this->objDatos->scm_reclamo_descarga_grabar($p);
        // var_export($rs);
        $array[] = $rs[0];
        if (intval($rs[0]['error_sql']) >= 0){
            if (intval($p['vp_chk_id']) == 2){
                $rs01 = $this->objDatos->scm_reclamo_descarga_grabar_entrevista($p);
                $array[] = $rs01[0];
            }else{
                $array[] = array(
                    'error_sql' => 0, 'error_info' => ''
                );
            }
        }
        return json_encode($array);
    }

    public function get_scm_reclamo_descarga_scaneo($p){
        $rs = $this->objDatos->scm_reclamo_descarga_scaneo($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $params = base64_encode(trim($value['path_cargo']).'&'.trim($value['path_imagen']).'&'.trim($value['reclamo']).'&'.trim($value['man_id']).'&'.ID_USUARIO);
                // echo "python2.7 /sistemas/weburbano2.5/apps/modules/seguimiento_consultas/views/preclamos/python/imagen_tif_ftp.py ".$params; die();
                exec("python2.7 /sistemas/weburbano/apps/modules/gestion/views/preclamos/python/imagen_tif_ftp.py ".$params, $output);
                $value['exec'] = "python2.7 /sistemas/weburbano/apps/modules/gestion/views/preclamos/python/imagen_tif_ftp.py ".$params;
                $value['exists_cargo'] = intval($output[0]);
                $value['exists_imagen'] = intval($output[1]);

                $array[] = $value;
            }
        }
        return json_encode($array[0]);
    }

    public function form_audescarga($p){
        $this->view('preclamos/form_audescarga.php', $p);
    }

    public function get_scm_reclamo_audi_cabecera($p){
        $rs = $this->objDatos->scm_reclamo_audi_cabecera($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $array[] = $value;
            }
        }
        return json_encode($array[0]);
    }

    public function get_scm_reclamo_audi_detalle($p){
        $rs = $this->objDatos->scm_reclamo_audi_detalle($p);
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

    public function get_scm_lista_chk_motivos($p){
        $rs = $this->objDatos->scm_lista_chk_motivos($p);
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

    public function set_scm_reclamo_audi_verifica($p){
        $rs = $this->objDatos->scm_reclamo_audi_verifica($p);
        return json_encode($rs[0]);
    }

    public function getRptTxt($p){
        set_time_limit(180);
        ini_set("memory_limit", "-1");

        $aleatorio = rand();

        $params = base64_encode(trim($p['vp_prov']).'&'.trim($p['vp_id_imp']).'&'.trim($p['vp_fecini']).'&'.trim($p['vp_id_tipo']).'&'.USR_ID.'&'.$aleatorio.'&'.$p['vp_linea'].'&'.$p['vp_nivel']);

        $comando = "python2.7 " . PATH . "apps/modules/gestion/views/preclamos/python/rpt_auditoria_txt.py ".$params;
        
        $output = array();
        exec($comando, $output);

        $file = 'rpt_auditoria-' . $aleatorio.'.txt';
        $archivo = PATH.'public_html/tmp/reclamos/'.$file;

        $path = $archivo;
        $type = '';

        if (is_file($path)) {
            $size = filesize($path);

            header('Content-type: application/force-download');
            header('Content-Disposition: inline; filename="' . $file . '"');
            header('Content-Transfer-Encoding: binary');
            header("Content-Length: " . $size);
            header('Pragma: public');
            header('Cache-control: public');
            readfile($path);
        } else {
            die("File not exist !!");
        }
    }

    public function form_auditando($p){
        $this->view('preclamos/form_auditando.php', $p);
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

}