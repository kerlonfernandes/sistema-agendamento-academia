<?php

require_once 'utils/RouteLoader.php';

$routeLoader = new RouteLoader(__DIR__ . '/routes');

$routes = $routeLoader->load();
