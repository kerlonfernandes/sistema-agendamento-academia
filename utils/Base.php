<?php

require_once('./classes/Helpers.class.php');

use HelpersClass\Helpers;
use Midspace\Models\Model;

require_once("models/InternModel.php");

class Base extends RenderView
{

    public SessionHelper $sessionHelper;
    public Helpers $helpers;
    private InternModel $internModel;

    public function __construct()
    {

        $this->sessionHelper = new SessionHelper();
        $this->helpers = new Helpers();
        $this->internModel = new InternModel();

        $this->checkGeral();
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

    public function accessLevel() {
        if(isset($_SESSION['accessLevel']) && !empty($_SESSION['accessLevel'])) {
            return $this->getAcess();
        }
        return null;
    }

    public function getAcess() {
        return $_SESSION['accessLevel'];
    }

    public function checkAcess(int $level, callable $callback) {
        $acesso = $this->accessLevel();
    
        if($acesso != null) {
            if(!($acesso >= $level)) {
                call_user_func($callback, $acesso);
            }
        }
    }

    private function checkGeral() {
        $this->setConfirmation();
    }

    private function setConfirmation() {

        $config = $this->internModel->get_configuracoes()->results[0];
        $limite_agendamentos = $config->limite_agendamento;

        $metade = floor($limite_agendamentos / 2);
 
        if ($limite_agendamentos / 2 > $metade) {
            $metade = ceil($limite_agendamentos / 2);
        }

        $horarios_limite = $this->internModel->get_ids_agendamentos_em_horarios_com_excesso($limite_agendamentos);


        
        if($horarios_limite->affected_rows > 0) {
            foreach($horarios_limite->results as $limites) {
                $this->internModel->updateTable('agendamentos_clientes', ['status_agendamento' => "confirmado"], ['id' => $limites->agendamento_id]);
            }

        }
    }
}


