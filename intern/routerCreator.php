<?php

class Router
{
    private static $routes = [];

    // Método para criar uma nova rota
    public static function create($path, $action)
    {
        self::$routes[$path] = $action;
    }

    // Método para processar a rota atual
    public static function handle($requestPath)
    {
        if (isset(self::$routes[$requestPath])) {
            $action = self::$routes[$requestPath];

            // Dividir o controlador e o método
            [$controller, $method] = explode('@', $action);

            // Verificar se a classe e o método existem
            if (class_exists($controller) && method_exists($controller, $method)) {
                $controllerInstance = new $controller();
                return $controllerInstance->$method();
            } else {
                http_response_code(404);
                return "Erro 404: Controlador ou método não encontrado.";
            }
        } else {
            http_response_code(404);
            return "Erro 404: Rota não encontrada.";
        }
    }

    // Método para exibir as rotas registradas
    public static function getRoutes()
    {
        return self::$routes;
    }
}
