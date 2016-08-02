<?php

// Directory separator is set up here because separators are different on different operating systems
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
$url = '';
if(isset($_GET['url'])){
    $url = $_GET['url'];
}

require_once(ROOT . DS . 'core' . DS . 'bootstrap.php');
