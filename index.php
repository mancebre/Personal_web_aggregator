<?php
    /* Show all errors */
    ini_set('display_errors', 1);
    error_reporting(-1);
    date_default_timezone_set('UTC');

    session_start();
    
    function __autoload($class) {
        $path = str_replace('\\', '/', $class);
        require_once $path . '.php';
    }
    
    use controllers\Controller;

    $controller = new Controller();
    $controller->handleRequest();
