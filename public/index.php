<?php
    $view = $_GET['p'] ?: 'home';

    $scripts = array(
        'http://code.jquery.com/jquery.js',
        'bootstrap/js/bootstrap.min.js'
    );

    $stylesheets = array(
        'bootstrap/css/bootstrap.min.css',
        'http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css',
        'css/style.css',
        'css/history.css'
    );
    
    if ($_GET['format'] === 'json') {
        if ($view) {
            include '../views/' . $view . '.html'; 
        }
    } else {
        include "../layout/layout.html";
    }

    function __autoload($class_name) {
        include '../modules/' . $class_name . '.php';
    }
?>
