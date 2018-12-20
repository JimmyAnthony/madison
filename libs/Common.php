<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class Common {

    /**
     * Convertir de minutos a hora
     */
    public static function convert_minute_to_hour($minute){
        $hora = 0;
        $res = '';
        if ($minute >= 60){
            $hora = $minute / 60;
            $minute = $minute % 60;
            if ($minute == 0 )
                $res = $hora . ' Hrs.';
            else
                $res = intval($hora) . ' Hrs. ' . $minute . ' Min.';
        }else{
            $res = $minute . ' Min.';
        }
        return $res;
    }

    /**
     * Transforma fecha a formati 'd/m/Y'
     */
    public static function getFormatDMY($_date, $separator = '/') {
        $f = explode($separator, $_date);
        return $f[2] . '/' . $f[1] . '/' . $f[0];
    }
    
    /**
     * Obtienes el nombre del mes.
     */
    public static function getNombreMes($mes) {
        $a = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');
        return $a[intval($mes) - 1];
    }

    /**
     * Convierte a minúsculas indices de un Array
     */
    public static function iaLower($array_) {
        $array = array();
        foreach($array_ as $index => $value){
            foreach($value as $index01 => $value01){
                $array[$index][strtolower($index01)] = $value01;
            }
        }
        return $array;
    }

    /**
     * Se encarga de leer el archivo de configuracion para los envios de correo
     */
    public static function read_ini_contacts($file_, $_opcion = ''){
        $_CONF = new Zend_Config_Json($file_, $_opcion);
        $array = array();
        $column = array();
        foreach($_CONF as $index => $value){
            $column['address_name'] = $value->nombre;
            $column['address_email'] = $value->email;
            $array[] = $column;
        }
        return $array;
    }
    
    /**
     * Parsear un archivo .ini
     */
    public static function read_ini($file_, $_server = '') {
        $array = parse_ini_file($file_, true);
        $aServer = array();
        if ( trim($_server) != '' ){
            foreach( $array as $index => $value ){
                if ( trim($index) == $_server ){
                    $aServer = $value;
                }
                $aServer[] = $value;
            }
        }else{
            $aServer = $array;
        }
        return $aServer;
    }

    /**
     * Verifica formato de fechas.
     */
    public static function check_date($str){
        return (strlen($str) == 10 && count(explode('-', $str)) == 3) ? Common::getFormatDMY($str, '-') : $str;
    }

    /**
     * Codifica valores de un arreglo bidimensional a UTF8
     */
    public static function UTF8($_array, $noInclude = array()){
        $rs = array();
        foreach($_array as $index => $value){
            foreach($value as $index01 => $value01 ){
                if (!in_array($index01, $noInclude))
                    $rs[$index][$index01] = utf8_encode(trim(Common::check_date($value01)));
            }
        }
        return $rs;
    }

    /**
     * Obtiene la ip real del cliente.
     */
    public static function get_Ip() { 
        $ip = ""; 
        if(isset($_SERVER)) { 
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
                $ip=$_SERVER['HTTP_CLIENT_IP']; 
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
                $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; 
            } else { 
                $ip=$_SERVER['REMOTE_ADDR']; 
            } 
        } else { 
            if ( getenv( 'HTTP_CLIENT_IP' ) ) { 
                $ip = getenv( 'HTTP_CLIENT_IP' ); 
            } elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) { 
                $ip = getenv( 'HTTP_X_FORWARDED_FOR' ); 
            } else { 
                $ip = getenv( 'REMOTE_ADDR' ); 
            } 
        } 
        if(strstr($ip,',')) { 
            $ip = array_shift(explode(',',$ip)); 
        } 
        return $ip;
    }

}
