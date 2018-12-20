<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

namespace Geekode\Http;

Class Response{

    private static $content_type = '';

    public function __construct(){
        
    }

    public static function headers($name, $value = null){
        static::$content_type = $value;
        header($name . ': ' . static::$content_type);
    }

    public static function getData($data = array()){
        if (static::$content_type == 'application/json')
            return json_encode($data);
        else return $data;
    }

}