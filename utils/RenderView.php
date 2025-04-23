<?php


class RenderView
{

    public function view($view, $args)
    {

        extract($args);

        require_once __DIR__ . "/../public/views/$view.view.php";
    }

    public function viewFromDir($dir, $view, $args)
    {
        $dir = trim($dir, '/');

        extract($args);
        require_once __DIR__ . "/../public/$dir/$view.view.php";
    }
}
