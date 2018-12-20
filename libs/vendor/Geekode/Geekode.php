<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

namespace Geekode;

use Geekode\Http\Response;

class Geekode{

    public function __construct(){
        
    }

    public function set_headers($name, $value){
        return Response::headers($name, $value);
    }

    public function getData($data){
        return Response::getData($data);
    }
    
}