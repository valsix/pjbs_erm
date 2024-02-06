<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('PHPEXCEL_ROOT')) {
    define('PHPEXCEL_ROOT', dirname(__FILE__) . '/');
    require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}

class Factory extends PHPExcel_IOFactory
{

    public function __construct()
    {
    }
}