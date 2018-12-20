<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class finderController extends AppController {

    private $objDatos;
    private $arrayMenu;
    private $id_user;

    public function __construct(){
        if(empty($_SERVER['HTTP_REFERER'])){
            header("Location: /public_html/template/error.php");
            exit();
        }
        $this->objDatos = new finderModels();
        $this->validates($_REQUEST);
    }
    public function validates($p){
        $p['ip'] = Common::get_Ip();
        $rs = $this->objDatos->usr_sis_login($p);
        $rs = $rs[0];
        if (intval($rs['sql_error']) >= 0 ){
            $this->id_user=intval($rs['id_user']);
        }else{
            header("Location: /public_html/template/error.php");
            exit();
        }
    }
    public function addreess($p){
        $this->view('address_finder/address_finder.php', $p);
    }

    public function get_gis_busca_distrito($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }

    public function get_scm_tabla_detalle($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }

    public function get_gis_busca_via($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }

    public function get_gis_busca_via_segmentos($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }

    public function get_gis_busca_via_grupoviviendas($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }

    public function get_gis_busca_grupoviviendas($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }

    public function get_gis_busca_via_numero_lote($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }

    public function get_gis_busca_manzanas($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }

    public function get_gis_busca_lotes($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }
    public function get_gis_export_puerta($p){
        $p['vp_id_user']=$this->id_user;
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
            'data' => $array//,
            //'debug' => $debug
        );
        return $this->response($data);
    }
}