<?php

require_once('./classes/Helpers.class.php');

use HelpersClass\Helpers;
use Midspace\Models\Model;

class Base extends RenderView
{

    public SessionHelper $sessionHelper;
    public Helpers $helpers;


    public function __construct()
    {

        $this->sessionHelper = new SessionHelper();
        $this->helpers = new Helpers();
    }

    public function isAuth()
    {
        if ((!isset($_SESSION['userId']) || empty($_SESSION['userId'])) || $_SESSION['loggedUser'] != true) {
            header("Location:" . SITE . "/login");
            exit;
        }
    }

    public function isAuthClient()
    {
        if ((!isset($_SESSION['userId']) || empty($_SESSION['userId'])) || $_SESSION['loggedUser'] != true) {
            return false;
        }

        return true;
    }

    public function isAdminAuth()
    {
        // Verifique se 'loggedAdmin' e 'admin' são definidos corretamente
        if ((!isset($_SESSION['userId']) || empty($_SESSION['userId'])) ||
            ($_SESSION['loggedAdmin'] ?? false) !== true ||
            ($_SESSION['admin'] ?? false) !== true
        ) {
            header('Location:' . SITE . "/login");
            exit;
        }
    }

    public function acessoNegadoView($args): void
    {

        $this->view("site/acesso-negado", args: $args);
    }

    public function insertPost(Model $modelInstance, $tableName, $post, bool $encodedKey = false)
    {
        if (empty($post)) {
            return false; // Evita erro ao tentar inserir um array vazio
        }

        $postData = [];
        $columns = [];
        $handleParams = [];

        if ($encodedKey && isset($this->helpers)) {
            foreach ($post as $key => $data) {
                $postData[$this->helpers->decodeURL($key)] = $data;
            }
        } else {
            $postData = $post;
        }

        foreach ($postData as $key => $data) {
            $columns[] = $key;
            $handleParams[":$key"] = htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); // Sanitiza input
        }

        return $modelInstance->insertInto($tableName, $columns, $handleParams);
    }
}


