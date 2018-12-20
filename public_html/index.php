<?php
/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '1');

/**
 * Defined path core
 */
define('BASEPATH', '../libs/');

/**
 * Core System
 */
require_once BASEPATH . 'core.php';
