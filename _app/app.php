<?php

class App
{

    public function __construct() {}

    public static function modoManutencao()
    {
        require('./public/views/site/manutencao.php');
        exit;
    }

    public static function showErrors()
    {
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }
}
