<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

set_include_path(
    '.' . PATH_SEPARATOR . realpath(dirname(__FILE__))
    .PATH_SEPARATOR . get_include_path()
);

require 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();