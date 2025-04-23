<?php

use Midspace\Operations\Operations;

require_once("classes/Operations.class.php");
require_once("models/UserModel.php");
require_once("models/InternModel.php");
class MainController extends Base
{
    public UserModel $userModel;
    private int $userId;
    public AgendaModel $agendaModel;
    public InternModel $internModel;
    public function __construct()
    {
        parent::__construct();
        $this->userModel    = new UserModel();
        $this->userId       = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
        $this->internModel  = new InternModel();
        $this->agendaModel  = new AgendaModel();  
    }

    public function index()
    {
        // $this->isAuth();

        $userData = null;

        // meu deus
        if(isset($_SESSION['loggedUser']) && !empty($_SESSION['loggedUser']) && isset($_SESSION['userId']) &&  !empty($_SESSION['userId'])) {
            $user     = new UserModel($_SESSION['userId']);
            $userData = $user->getData();
            
            if($userData->affected_rows > 0) {
                $_SESSION['user'] = $userData->results[0];
                $userData         = $userData->results[0];
            }

        }

        $configuracoes = $this->internModel->get_configuracoes()->results[0];

        $imagem_formulario  = $configuracoes->imagem_formulario;
        $vinculos           = json_decode($configuracoes->vinculos);
        $formulario_ativo   = $configuracoes->formulario_ativo;
        $aviso_formulario   = $configuracoes->texto_aviso_formulario;
        $cor_primaria       = $configuracoes->cor_primaria;

        // limite de agendamentos em cada dia da semana
        $limite_agendamentos = $configuracoes->limite_agendamento;

        // calcula a metade arredondando para baixo
        $metade = floor($limite_agendamentos / 2);

        // se a metade for ímpar (como 9/2 = 4.5 que vira 4), ajusta para 5
        if ($limite_agendamentos / 2 > $metade) {
            $metade = ceil($limite_agendamentos / 2);
        }

        // vvalor um a menos que o total
        $um_a_menos = $limite_agendamentos - 1;

        $this->view('agendamento', [
            'title' => "UNIVC | Agendamento",

            'imagem_formulario'   => $imagem_formulario,
            'vinculos'            => $vinculos,
            'limite_agendamentos' => $limite_agendamentos,
            'metade'              => $metade,
            'um_a_menos'          => $um_a_menos,
            'aviso_formulario'    => $aviso_formulario,
            'cor_primaria'        => $cor_primaria,
            'formulario_ativo'    => $formulario_ativo,
            'user'                => $userData
        ]);
    }

    public function authUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            $this->helpers->redirect(SITE . "/login");
        }

        if(isset($_GET['keep']) && !empty($_GET['keep'])) {
            $_SESSION['last_form'] = json_decode($this->helpers->decodeURL($_GET['keep']));
            $_SESSION['after_login'] = 'send_form';
        }

        try {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = htmlspecialchars('Campos de Email e senha são obrigatórios.');
                $_SESSION['erro'] = true;
                $this->helpers->redirect(SITE . "/login");
                return;
            }

            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

            $password = $_POST['password'];

            $db = $this->userModel->database;

            $result = $db->execute_query(
                "SELECT * 
                 FROM users 
                 WHERE users.email = :email",
                [":email" => $email]
            );

            if (!$result || empty($result->results)) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = htmlspecialchars('Nenhuma conta existente associada a esse email.');
                $_SESSION['erro'] = true;
                $_SESSION['retry'] = $_POST['email'];

                $this->helpers->redirect(SITE . "/login");
                return;
            }

            $user = $result->results[0];

            if (!password_verify($password, $user->password)) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = htmlspecialchars('Email ou senha incorretos!');
                $_SESSION['erro'] = true;
                $_SESSION['retry'] = $_POST['email'];
                $this->helpers->redirect(SITE . "/login");
                return;
            }

            // Definição da sessão do usuário autenticado
            if ($user->nivel_acesso >= 4) {
                $_SESSION['admin'] = true;
                $_SESSION['loggedAdmin'] = true;

            }

            $_SESSION['userId'] = $user->id;
            $_SESSION['loggedUser'] = true;
            $_SESSION['nome'] = $user->nome;
            $_SESSION['nivel'] = $user->nivel_acesso;

            // Definição de cookies seguros
            setcookie('userId', $user->id, [
                'expires' => time() + 3600,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            setcookie('user_name', htmlspecialchars($user->nome), [
                'expires' => time() + 3600,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => false,
                'samesite' => 'Strict'
            ]);

            $redirect_url = isset($_GET['redirect_back']) 
            ? $_GET['redirect_back'] 
            : SITE ;
            // . ($user->nivel_acesso >= 4 ? "/admin" : ""); caso seja logado como admin, redirecionar pro painel. Se quiser que redirecione, é só remover o comentário
            
            $this->helpers->redirect($redirect_url);
        } catch (Exception $e) {
            error_log($e->getMessage());

            $_SESSION['status'] = 'error';
            $_SESSION['message'] = htmlspecialchars('Ocorreu um erro interno no servidor.');
            $_SESSION['erro'] = true;

            $this->helpers->redirect(SITE . "/login");
        }
    }


    public function login()
    {

        $this->view('login', [
            'title' => "Login"
        ]);
    }

    public function vizualizar_horarios()
    {   

        $this->isAuth();
        $id = null;

        if(isset($_SESSION['userId']) || !empty($_SESSION['userId'])) {
            $id = $_SESSION['userId'];
        }

        $horarios = $this->agendaModel->user_horarios_client( $id );

        $this->view('vizualizar-horarios', [
            'title' => "Vizualização dos horários",
            'horarios' => $horarios
        ]);
    }

    public function cadastrar()
    {
        $nome = null;
        $email = null;
        $telefone = null;

        if ( isset($_SESSION['last_form']) || !empty($_SESSION['last_form']) ) {
            $nome     = json_decode($_SESSION['last_form']->agendamentos)[0]->nome;
            $email    = json_decode($_SESSION['last_form']->agendamentos)[0]->email;
            $telefone = json_decode($_SESSION['last_form']->agendamentos)[0]->telefone;
        }

        $this->view('cadastrar', [
            'title'    => "Cadastro",
            'nome'     => $nome,
            'email'    => $email,
            'telefone' => $telefone
        ]);
    }

    public function cadastrar_post()
    {
        $post = Post();
        $get = Get();

        $keep = isset($get->keep) ? $get->keep : "";
        $name = trim($post->name ?? '');
        $email = trim($post->email ?? '');
        $password = trim($post->password ?? '');
        $cpf = preg_replace('/[^0-9]/', '', $post->cpf ?? '');
        $phone = trim($post->phone ?? '');

        if (empty($name) || empty($email) || empty($password) || empty($cpf)) {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'Todos os campos obrigatórios devem ser preenchidos.',
                'dismissible' => true
            ];

            $_SESSION['last_try'] = compact('email', 'name', 'phone');
            $this->helpers->redirect(SITE . "/cadastrar".(isset($_GET['keep']) ? "?keep=".$_GET['keep'] : ""));

        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'E-mail inválido.',
                'dismissible' => true
            ];

            $_SESSION['last_try'] = compact('email', 'name', 'phone');
            $this->helpers->redirect(SITE . "/cadastrar".(isset($_GET['keep']) ? "?keep=".$_GET['keep'] : ""));

        }

        if (!isValidCPF($cpf)) {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'CPF inválido.',
                'dismissible' => true
            ];

            $_SESSION['last_try'] = compact('email', 'name', 'phone');
            $this->helpers->redirect(SITE . "/cadastrar".(isset($_GET['keep']) ? "?keep=".$_GET['keep'] : ""));
            return;

        }

        $user = $this->userModel->getUserByEmail($email);
        if ($user->affected_rows > 0) {
            $_SESSION['alert_message'] = [
                'type' => 'warning',
                'title' => 'Não foi possível realizar o cadastro:',
                'message' => 'Este e-mail já está cadastrado.',
                'dismissible' => true
            ];

            $_SESSION['last_try'] = compact('email', 'name', 'phone');
            $this->helpers->redirect(SITE . "/cadastrar".(isset($_GET['keep']) ? "?keep=".$_GET['keep'] : ""));

        }

        $r = $this->userModel->createUser(
            $name,
            desformatarCPF($cpf),
            $email,
            $phone,
            $password
        );

        if ($r->affected_rows <= 0 || $r->status != 'success') {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Não foi possível realizar o cadastro:',
                'message' => 'Ocorreu um erro ao tentar realizar o cadastro. Tente novamente.',
                'dismissible' => true
            ];

            $_SESSION['last_try'] = compact('email', 'name', 'phone');
            $this->helpers->redirect(SITE . "/cadastrar".(isset($_GET['keep']) ? "?keep=".$_GET['keep'] : ""));

        }

        $_SESSION['alert_message'] = [
            'type' => 'success',
            'title' => 'Sucesso!',
            'message' => 'Usuário cadastrado com sucesso. Faça login!',
            'dismissible' => true
        ];
        $this->helpers->redirect(SITE . "/login".(isset($_GET['keep']) ? "?keep=".$_GET['keep'] : ""));
    }

    public function vizualizar_horarios_usuario() {

        $this->isAuth();

        $get = Get();

        if(!isset($get->id) || empty($get->id)) return; 

        $user_id = null;
        $horario = $this->helpers->decodeURL($get->id);

        if(isset($_SESSION['userId']) || !empty($_SESSION['userId'])) {
            $user_id = $_SESSION['userId'];
        }

        $horario = $this->agendaModel->get_agendamento_by_id($horario);

        if($horario->affected_rows <= 0 ) {
            return;
        }

        $this->view('load/agendamentos-usuario', [
            "horario" => $horario->results[0]
        ]);

    }


    public function logout()
    {
        $_SESSION = [];
        $_SESSION['loggedUser'] = false;
        session_destroy();
        if (isset($_COOKIE['userId'])) {
            setcookie('userId', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['user_name'])) {
            setcookie('user_name', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['user_acesso'])) {
            setcookie('user_acesso', '', time() - 3600, '/');
        }
        header("Location: ./login");
        exit;
    }
}
