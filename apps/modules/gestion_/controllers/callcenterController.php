<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class callcenterController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        /**
         * Solo incluir en caso se manejen sessiones
         */
        $this->valida();

        $this->objDatos = new callcenterModels();
    }

    public function index($p){        
        $this->view('callcenter/form_index.php', $p);
    }

    public function get_scm_call_gestionistas($p){
        $rs = $this->objDatos->scm_call_gestionistas($p);
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

    public function get_scm_call_buzon_gestiones($p){
        $rs = $this->objDatos->scm_call_buzon_gestiones($p);
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

    public function form_detalle_gestion($p){
        $this->view('callcenter/form_detalle_gestion.php', $p);
    }

    public function get_scm_call_buzon_detalle($p){
        $rs = $this->objDatos->scm_call_buzon_detalle($p);
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

    public function get_gis_busca_via($p){
        $rs = $this->objDatos->gis_busca_via($p);
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

    public function get_gis_busca_via_segmentos($p){
        $rs = $this->objDatos->gis_busca_via_segmentos($p);
        $array = array();
        if (count($rs) > 0){
            foreach($rs as $index => $value){
                $value['inicio_px'] = floatval($value['inicio_px']);
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

    public function get_gis_busca_via_grupoviviendas($p){
        $rs = $this->objDatos->gis_busca_via_grupoviviendas($p);
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

    public function get_gis_busca_grupoviviendas($p){
        $rs = $this->objDatos->gis_busca_grupoviviendas($p);
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

    public function get_gis_busca_via_numero_lote($p){
        $rs = $this->objDatos->gis_busca_via_numero_lote($p);
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

    public function get_gis_busca_manzanas($p){
        $rs = $this->objDatos->gis_busca_manzanas($p);
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

    public function get_gis_busca_lotes($p){
        $rs = $this->objDatos->gis_busca_lotes($p);
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
    public function get_gis_export_puerta($p){
        $rs = $this->objDatos->get_gis_export_puerta($p);
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
}