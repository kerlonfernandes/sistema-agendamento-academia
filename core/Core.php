<?php
class Core
{
    public function run($routes): void
    {
        $url = "/";

        isset($_GET['url']) ? $url .= $_GET['url'] : "";

        $url = ($url !== "/") ? rtrim($url, "/") : $url;

        $routerFound = false;

        foreach ($routes as $path => $controller) {

            // Substituir todos os placeholders `{param}` por regex para capturar caracteres especiais
            $pattern = '#^' . preg_replace('/{(\w+)}/', '([a-zA-Z0-9=\/\-\_]+)', $path) . '$#';

            if (preg_match($pattern, $url, $matches)) {

                array_shift($matches); // Remove o primeiro elemento, que é o URL completo

                $routerFound = true;

                [$currentController, $action] = explode('@', $controller);

                require_once __DIR__ . "/../controllers/$currentController.php";

                $newController = new $currentController();
                $newController->$action(...$matches); // Passar os parâmetros capturados como argumentos
                return;
            }
        }

        if (!$routerFound) {
            require_once __DIR__ . "/../controllers/NotFoundController.php";
            $controller = new NotFoundController();
            $controller->index();
        }
    }
}
