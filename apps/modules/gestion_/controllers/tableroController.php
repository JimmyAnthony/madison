<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class tableroController extends AppController {

    private $objDatos;
    private $arrayMenu;

    private $rsTree = array();
    private $rsData = array();

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new tableroModels();
    }

    public function index($p){        
        $this->view('tablero/form_index.php', $p);
    }

    public function getData_Grid_Tree($p){
        set_time_limit(1800);
        ini_set("memory_limit", "-1");
        $p['vp_id_shipp']=$this->subMatrizCmb($p['vp_id_shipp']);
        $p['vp_id_orden']=$this->subMatrizCmb($p['vp_id_orden']);
        $this->rsTree = $this->objDatos->bi_tablero_panel($p);
        // $this->rsTree = array($this->rsTree[0]);
        // var_export($this->rsTree);

        if (count($this->rsTree) > 0){
            $array = array();
            $array['text'] = '.';
            $array['expanded'] = 'false';
            $array['children'] = array();
            $children = $this->getTree_recursive($p, 1, 0);
            $array['children'] = $children;
        }
        return $this->response($array);
    }

    public function subMatrizCmb($val){
        $val_ = json_decode($val, true);
        $val = '';
        if (count($val_) > 0){
            foreach($val_ as $index => $value){
                if((int)$value!=0)$val.= $value.',';
            }
            $val = substr($val, 0, (strlen($val) - 1) );
        }
        //$val=((int)$val==0)?'':$val;
        return $val;
    }

    public function getTree_recursive($p, $nivel, $parent){
        $array = array();
        $children = array();
        $vi = 0;
        foreach($this->rsTree as $index => $value){
            if (intval($value['nivel']) == $nivel && intval($value['id_padre']) == $parent){
                $array[$vi]['id_indicador'] = intval($value['id_indicador']);
                $array[$vi]['indicador'] = trim($value['indicador']);
                $array[$vi]['nivel'] = intval($value['nivel']);
                $array[$vi]['id_padre'] = intval($value['id_padre']);
                $array[$vi]['posicion'] = intval($value['posicion']);
                $array[$vi]['um'] = trim($value['um']);
                $array[$vi]['meta'] = floatval($value['meta']);
                $array[$vi]['id_graf_1'] = trim($value['id_graf_1']);
                $array[$vi]['t_graf_1'] = trim($value['t_graf_1']);
                $array[$vi]['id_graf_2'] = trim($value['id_graf_2']);
                $array[$vi]['t_graf_2'] = trim($value['t_graf_2']);
                $array[$vi]['id_graf_3'] = trim($value['id_graf_3']);
                $array[$vi]['t_graf_3'] = trim($value['t_graf_3']);

                $p['vp_id_indicador'] = intval($value['id_indicador']);
                foreach($this->objDatos->bi_tablero_panel_valores($p) as $index01 => $value01){
                    $array[$vi]['mes-' . trim($value01['tipo_periodo']).'-'.intval($value01['id_periodo'])] = trim($value01['mes']);
                    $array[$vi]['valor-' . trim($value01['tipo_periodo']).'-'.intval($value01['id_periodo'])] = intval($value01['valor']);
                    $array[$vi]['porcentaje-' . trim($value01['tipo_periodo']).'-'.intval($value01['id_periodo'])] = floatval($value01['porcentaje']);
                    $array[$vi]['tendencia-' . trim($value01['tipo_periodo']).'-'.intval($value01['id_periodo'])] = intval($value01['tendencia']);
                    $array[$vi]['tipo_periodo-' . trim($value01['tipo_periodo']).'-'.intval($value01['id_periodo'])] = trim($value01['tipo_periodo']);
                    $array[$vi]['id_periodo-' . trim($value01['tipo_periodo']).'-'.intval($value01['id_periodo'])] = intval($value01['id_periodo']);
                }

                $array[$vi]['iconCls'] = 'gk-tree-indicador';
                $array[$vi]['cls'] = $this->get_style_nivel($value['nivel']);
                $children = $this->getTree_recursive($p, ( intval($value['nivel']) + 1 ), $value['id_indicador']);
                if (count($children) > 0 ){
                    $array[$vi]['leaf'] = 'false';
                    $array[$vi]['children'] = $children;
                }else{
                    $array[$vi]['leaf'] = 'true';
                }
                ++$vi;
            }
        }
        return $array;
    }

    public function getDynamicCharts($p){
        $p['vp_id_shipp']=$this->subMatrizCmb($p['vp_id_shipp']);
        $p['vp_id_orden']=$this->subMatrizCmb($p['vp_id_orden']);
        $rs = $this->objDatos->bsc_tablero_panel_grafico($p);

        $array = array();
        $arraySearch = array();
        $vi = 0;
        foreach($rs as $index => $value){
            $r = array_search(trim($value['indicador']), $arraySearch);
            if (!is_numeric($r)){
                $array[$vi]['indicador'] = trim($value['indicador']);
                $array[$vi]['titulo'] = trim($value['titulo']);
                $array[$vi]['tipo_grafico'] = trim($value['tipo_grafico']);
                $array[$vi]['children'] = array();
                $vj = 0;
                $array[$vi]['children'][$vj]['indicador'] = trim($value['indicador']);
                $array[$vi]['children'][$vj]['titulo'] = trim($value['titulo']);
                $array[$vi]['children'][$vj]['periodo'] = trim($value['periodo']);
                $array[$vi]['children'][$vj]['valor'] = trim($value['valor']);
                $array[$vi]['children'][$vj]['porcentaje'] = trim($value['porcentaje']);

                $arraySearch[count($arraySearch)] = trim($value['indicador']);
                ++$vi;
                
            }else{
                $cc = count($array[$r]['children']);
                $array[$r]['children'][$cc]['indicador'] = trim($value['indicador']);
                $array[$r]['children'][$cc]['titulo'] = trim($value['titulo']);
                $array[$r]['children'][$cc]['periodo'] = trim($value['periodo']);
                $array[$r]['children'][$cc]['valor'] = trim($value['valor']);
                $array[$r]['children'][$cc]['porcentaje'] = trim($value['porcentaje']);
                ++$vj;
            }
        }

        // var_export($array);

        $category = array();
        $dataset = array();
        foreach($array as $index => $value){
            $dataset[$index]['seriesname'] = trim($value['indicador']);
            $dataset[$index]['data'] = array();
            if (count($value['children']) > 0){
                foreach($value['children'] as $index01 => $value01){
                    if ($index == 0){
                        $category[] = array('label' => trim($value01['periodo']));
                    }
                    $dataset[$index]['data'][$index01]['value'] = intval($value01['valor']);
                    $dataset[$index]['data'][$index01]['showvalue'] = '1';
                    $dataset[$index]['data'][$index01]['displayValue'] = intval($value01['valor']) . ' ( ' . floatval($value01['porcentaje']) . '% )';
                }
            }
        }

        $tipo_grafico = $rs[0]['tipo_grafico'];

        switch(trim($value['tipo_grafico'])){
            case 'B':
                $data = array(
                    'chart' => array(
                        'caption' => trim($rs[0]['titulo']),
                        'yAxisName' => 'Cantidad G.E.',
                        "rotateValues" => "1",
                        "formatNumberScale" => "0",
                        "exportEnabled" => "1",
                        "legendBgColor" => "#CCCCCC",
                        "legendBgAlpha" => "20",
                        "legendBorderColor" => "#666666",
                        "legendBorderThickness" => "1",
                        "legendBorderAlpha" => "40",
                        "legendShadow" => "1",
                        "theme" => "fint"
                    ),
                    'categories' => array(
                        array('category' => $category)
                    ),
                    'dataset' => $dataset
                );
            break;
        }


        return $this->response($data);
    }

    public function get_style_nivel($nivel){
        switch(intval($nivel)){
            case 1: $cls = 'gk-tree-color-col-level1'; break;
            case 2: $cls = 'gk-tree-color-col-level2'; break;
            case 3: $cls = 'gk-tree-color-col-level3'; break;
            case 4: $cls = 'gk-tree-color-col-level4'; break;
            case 5: $cls = 'gk-tree-color-col-level5'; break;
            default: $cls = 'gk-tree-color-col-default'; break;
        }
        return $cls;
    }

    public function getAnios($p){
        $array = array();
        $anioActual = date('Y');
        for($i = 2014; $i <= $anioActual; ++$i){
            $array[] = array('anio' => ($i));
        }
        return $this->response($array);
    }

    public function get_scm_tabla_detalle($p){
        $rs = $this->objDatos->scm_tabla_detalle($p);
        /*if (isset($p['leaf'])){
            $array = array(
                array('descripcion' => '[ Todos ]', 'id_elemento' => '0', 'des_corto' => '')
            );
        }else
            $array = array();*/
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

    public function get_bsc_anio_semanas($p){
        $rs = $this->objDatos->bsc_anio_semanas($p);
        $array = array();
        if (count($rs) > 0){
            $focusId = 0;
            foreach($rs as $index => $value){
                if (intval($value['semana_actual']) == 1)
                    $focusId = $value['id_semana'];
                $array[] = $value;
            }
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array,
            'focusId' => $focusId
        );
        return $this->response($data);
    }

    public function get_usr_sis_shipper($p){
        $rs = $this->objDatos->usr_sis_shipper($p);
        // $array = array(
        //     array('shi_codigo' => '0', 'shi_nombre' => '[ Todos ]', 'shi_id' => '0')
        // );
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

    public function form_analizar($p){
        $this->view('tablero/form_analizar.php', $p);
    }

    public function get_bsc_tablero_panel_drill($p){
        set_time_limit(1800);
        ini_set("memory_limit", "-1");
        $p['vp_id_shipp']=$this->subMatrizCmb($p['vp_id_shipp']);
        $p['vp_id_orden']=$this->subMatrizCmb($p['vp_id_orden']);
        $this->rsData = $this->objDatos->bsc_tablero_panel_drill($p);
        return $this->response($this->getDataDrill());
    }

    public function getDataDrill(){
        $array = array();
        $array_search = array();
        $vi = 0;
        // $campos = array(
        //     array('text' => 'Distrito', 'dataIndex' => 'ciudad', 'width' => 0)
        // );
        foreach($this->rsData as $index => $value){
            $r = array_search(intval($value['ciu_id']), $array_search);
            if (!is_numeric($r)){
                $array[$vi]['ciu_id'] = intval($value['ciu_id']);
                $array[$vi]['ciudad'] = trim($value['ciudad']);
                $array[$vi]['mes-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = trim($value['mes']);
                $array[$vi]['valor-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = intval($value['valor']);
                $array[$vi]['porcentaje-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = floatval($value['porcentaje']);
                $array[$vi]['tendencia-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = intval($value['tendencia']);
                $array[$vi]['tipo_periodo-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = trim($value['tipo_periodo']);

                // $campo[] = array('text' => trim($value['mes']), 'dataIndex' => 'valor-' . intval($value['id_periodo']), 'width' => 70);

                $array_search[count($array_search)] = intval($value['ciu_id']);
                ++$vi;
            }else{
                $array[$r]['mes-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = trim($value['mes']);
                $array[$r]['valor-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = intval($value['valor']);
                $array[$r]['porcentaje-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = floatval($value['porcentaje']);
                $array[$r]['tendencia-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = intval($value['tendencia']);
                $array[$r]['tipo_periodo-' . intval($value['id_periodo']) . '-' . trim($value['tipo_periodo'])] = trim($value['tipo_periodo']);
            }
        }
        return array('data' => $array, 'fields' => $this->getFieldsDrill($array), 'campos' => $this->getCamposDrill($array));
    }

    public function getCamposDrill($data){
        $array = array();
        $rs = $data[0];
        $dataIndex = array_keys($data[0]);
        $campos = array();
        $vj = 0;
        $campos[] = array('text' => 'Distrito', 'dataIndex' => 'ciudad', 'width' => 0, 'align' => 'left');
        ++$vj;
        $array_search = array('X');
        $vi = 0;
        foreach($rs as $index => $value){

            if ($vi > 1){
                $ex = explode('-', $index);
                $r = array_search(trim($ex[2]), $array_search);
                if (!is_numeric($r)){
                    $campos[$vj]['text'] = trim($ex[2]) == 'M' ? 'Meses' : 'Semanas';
                    $campos[$vj]['items'] = array();
                    $vk = 0;
                    if (trim($ex[0]) == 'mes'){
                        $campos[$vj]['items'][$vk]['text'] = trim($value);
                        $campos[$vj]['items'][$vk]['dataIndex'] = 'valor-' . $ex[1] . '-' . $ex[2];
                        $campos[$vj]['items'][$vk]['width'] = 70;
                        $campos[$vj]['items'][$vk]['align'] = 'right';
                        $campos[$vj]['items'][$vk]['format'] = '0,000';
                    }else if (trim($ex[0]) == 'porcentaje'){
                        $campos[$vj]['items'][$vk]['text'] = '%';
                        $campos[$vj]['items'][$vk]['dataIndex'] = 'porcentaje-' . $ex[1] . '-' . $ex[2];
                        $campos[$vj]['items'][$vk]['width'] = 50;
                        $campos[$vj]['items'][$vk]['align'] = 'right';
                        $campos[$vj]['items'][$vk]['format'] = '0.0';
                    }

                    ++$vj;

                    $array_search[count($array_search)] = trim($ex[2]);
                }else{
                    $cc = count($campos[$r]['items']);
                    if (trim($ex[0]) == 'mes'){
                        $campos[$r]['items'][$cc]['text'] = trim($value);
                        $campos[$r]['items'][$cc]['dataIndex'] = 'valor-' . $ex[1] . '-' . $ex[2];
                        $campos[$r]['items'][$cc]['width'] = 70;
                        $campos[$r]['items'][$cc]['align'] = 'right';
                        $campos[$r]['items'][$cc]['format'] = '0,000';
                    }else if (trim($ex[0]) == 'porcentaje'){
                        $campos[$r]['items'][$cc]['text'] = '%';
                        $campos[$r]['items'][$cc]['dataIndex'] = 'porcentaje-' . $ex[1] . '-' . $ex[2];
                        $campos[$r]['items'][$cc]['width'] = 50;
                        $campos[$r]['items'][$cc]['align'] = 'right';
                        $campos[$r]['items'][$cc]['format'] = '0.0';
                    }
                }
            }
            ++$vi;
        }
        // var_export($campos);
        return $campos;
    }

    public function getFieldsDrill($data){
        $array = array();
        $data = array_keys($data[0]);
        foreach($data as $index => $value){
            $type = 'string';
            $ex = explode('-', $value);
            if (trim($ex[0]) == 'porcentaje')
                $type = 'float';
            else if (trim($ex[0]) == 'tendencia')
                $type = 'int';
            else if (trim($ex[0]) == 'tipo_periodo')
                $type = 'int';
            else if (trim($ex[0]) == 'valor')
                $type = 'int';
            $array[$index] = array('name' => $value, 'type' => $type);
        }
        return $array;
    }

    public function testGoogleMaps($p){
        $this->view('tablero/test_google.php', $p);
    }

    public function getDataGoogleMaps($p){
        $path = '/sistemas/weburbano/public_html/tmp/Valores.csv';
        $vi = 0;
        $array = array();
        if (($gestor = fopen($path, "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                if ($vi != 0){
                    $array[$vi]['latitude'] = floatval($datos[5]);
                    $array[$vi]['logitude'] = floatval($datos[6]);
                }
                ++$vi;
            }
            fclose($gestor);
        }
        return json_encode($array);
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
    public function get_scm_bsc_tablero_panel_help($p){
        $rs = $this->objDatos->get_scm_bsc_tablero_panel_help($p);
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