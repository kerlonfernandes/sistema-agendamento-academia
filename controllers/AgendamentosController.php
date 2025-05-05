<?php

use Midspace\Operations\Operations;

require_once("classes/Operations.class.php");
require_once("models/UserModel.php");
require_once("models/AgendaModel.php");
require_once("models/InternModel.php");
require_once("entities/agendamento.entity.php");
require_once("entities/user.entity.php");

class AgendamentosController extends Base
{
    public UserModel $userModel;
    private int $userId;
    public AgendaModel $agendaModel;
    public array $dias_semana;
    public InternModel $internModel;
    public function __construct()
    {
        parent::__construct();

        $this->userModel   = new UserModel();
        $this->internModel = new InternModel();
        $this->userId      = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
        $this->agendaModel = new AgendaModel();
        $this->dias_semana = ['segunda-feira', 'terca-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira'];
    }

    // views
    public function index()
    {
        $this->isAuth();

        $this->view('agendamentos', [
            'title' => "UNIVC | Agendamento",
      
        ]);
    }

    public function clientes_view()
    {

        $agendamentos = $this->agendaModel->get_agendamentos();
        
        $this->view('admin/clientes', [
            'title'        => "UNIVC | Agendamento",
            'agendamentos' => $agendamentos
        ]);
    }

    public function detalhes_usuario($id)
    {

        $this->isAdminAuth();
        
        $usuario       = $this->agendaModel->get_user_by_id( $id );
        $horarios      = $this->agendaModel->get_user_horarios( $id );
        $configuracoes = $this->internModel->get_configuracoes()->results[0];
        $vinculos      = $this->internModel->get_vinculo_formulario()->results[0];
        $instrutor     = null;
        $i             = null;

        $arrayVinculos = json_decode( $vinculos->vinculos ?? '{}', true ) ?: [];


        if ( $usuario->status != 'success' || $usuario->affected_rows <= 0 ) {
            $this->helpers->redirect( SITE . "/admin/usuarios" );
        }

        if($usuario->results[0]->nivel_acesso == 2) {
            $instrutor = $this->agendaModel->get_instructor_horarios($id);
        }


        $this->view( 'admin/detalhe-usuario', [
            'title'     => "UNIVC | Detalhes do Usuário",
            'client_id' => $id,
            'usuario'   => $usuario->results[0],
            'horarios'  => $horarios,
            'vinculos'  => $arrayVinculos,
            'instrutor' => $instrutor
        ]);
    }
    // fim das views



    // CRUD
    public function agenda_horario_post()
    {

        verify_post_method();

        $is_client_auth = $this->isAuthClient();
        $post           = Post();

        // caso o usuário não esteja logado, vou setar uma sessão para informar o front que ele não está logado
        if( !$is_client_auth && !empty( $post->agendamentos )) {
            
            $_SESSION['last_form']   = $post;
            $_SESSION['effect']      = "body-slide-in-right"; // efeito de transição para a tela de login
            $_SESSION['after_login'] = "send_form"; // ação após o usuário logar
            $_SESSION['timestamp']   = time();

            // aviso pro front  
            $response = [
                "status"    =>  "warning",
                "message"   =>  "Você não está logado.",
                "icon"      =>  "warning",
                "title"     =>  "Aviso!",
                "cause"     =>  'not_logged_in',
                "_d"          =>  $this->helpers->encodeURL(json_encode($post))
            ];

            echo TreatedJson( $response );
            return;
        }

        if (
            !empty( $post->is_multiple ) &&
            !empty( $post->agendamentos ) &&
            $post->is_multiple == 'true'
        ) {
            $this->agendar_horario_multiple( $post );
            return;
        }


        $response = [
            "status"    =>  "success",
            "message"   => "Horário agendado com sucesso!",
            "icon"      =>    "success",
            "title"     =>   "Sucesso!"
        ];

        echo TreatedJson( $response );
    }


    private function agendar_horario_multiple( mixed $post )
    {

        if ( isset( $_SESSION['timestamp'] ) ) {
            $agora          = time();
            $timestampSalvo = $_SESSION['timestamp'];
        
            if (($agora - $timestampSalvo) > 300) { 
                echo json_encode([
                    'status'     => 'error',
                    'cause'      => 'not_logged_in',
                    'resultados' => [
                        [
                            'status'   => 'error',
                            'dia'      => '',
                            'horario'  => '',
                            'mensagem' => 'Sessão de agendamento expirada. Tente novamente.'
                        ]
                    ]
                ]);
                return;
            }
        }
            
        $configuracoes = $this->internModel->get_configuracoes()->results[0];

        $agendamentos_data = json_decode($post->agendamentos);
        $resultados = [];

        foreach ($agendamentos_data as $dados) {
            try {
                // definindo uma entidade agendamento

                $agendamento = new Agendamento();
                $agendamento->setDiaSemana($dados->dia);
                $agendamento->setHorario($dados->horario);
                $agendamento->setStatusAgendamento('ativo');
                $agendamento->setObservacoes($dados->restricoes ?? '');
                $agendamento->setCreatedAt(new DateTime());
                $agendamento->setUpdatedAt(new DateTime());

                // definindo uma entidade usuário vindos do post

                $user = new User(
                    $dados->nome,
                    $dados->telefone,
                    $dados->email,
                    $dados->vinculo
                );

                // registra o agendamento no banco de dados, com todas as regras
                $resultado = $this->agendaModel->executa_agendamento($agendamento, $user, $configuracoes->limite_agendamento);

                if ($resultado->status == 'error') {
                    $resultados[] = [
                        'status' => 'error',
                        'dia' => $dados->dia,
                        'horario' => $dados->horario,
                        'mensagem' => $resultado->message
                    ];
                } else {
                    $resultados[] = [
                        'status' => 'success',
                        'dia' => $dados->dia,
                        'horario' => $dados->horario,
                        'mensagem' => 'Horário agendado com sucesso'
                    ];
                }
            } catch (Exception $e) {
                // Se deu erro, adiciona erro
                $resultados[] = [
                    'status' => 'error',
                    'dia' => $dados->dia,
                    'horario' => $dados->horario,
                    'mensagem' => 'Não foi possível agendar: ' . $e->getMessage()
                ];
            }
        }

        // Retorna o resultado
        echo json_encode([
            'status' => 'success',
            'resultados' => $resultados
        ]);
        return;
    }

    /**
     * Função para consultar os horários disponíveis
     */
    public function horarios_disponiveis()
    {
        $dia                 = null;
        $horario             = null;
        $get                 = Get();
        $dia                 = $get->dia;
        $configuracoes       = $this->internModel->get_configuracoes()->results[0];
        $limite_agendamentos = $configuracoes->limite_agendamento;
        $metade              = floor($limite_agendamentos / 2);

        if ($limite_agendamentos / 2 > $metade) {
            $metade = ceil($limite_agendamentos / 2);
        }

        $um_a_menos = $limite_agendamentos - 1;
 
        if ($dia === 'todos-dias') {
            $todos_horarios = [];

            foreach ($this->dias_semana as $dia) {

                $resultado = $this->agendaModel->horarios_disponiveis_por_dia($dia);


                if ( $resultado && isset( $resultado->results ) ) {
                    
                    foreach ( $resultado->results as $horario ) {
                        $horario->dia_semana = $dia;
                        $todos_horarios[] = $horario;
                    }
                
                }
            }

            $this->view( 'load/horarios_todos' , [
                'horarios'            => $todos_horarios,
                'horario_selecionado' => $horario,
                'limite_agendamentos' => $limite_agendamentos,
                'metade'              => $metade,
                'um_a_menos'          => $um_a_menos
            ]);

            return;
        }

        $horarios = $this->agendaModel->horarios_disponiveis_por_dia( $dia );

        $this->view('load/horarios', [
            'horarios'            => $horarios->results,
            'horario_selecionado' => $horario,
            'limite_agendamentos' => $limite_agendamentos,
            'metade'              => $metade,
            'um_a_menos'          => $um_a_menos
        ]);
    }

    public function editar_agendamento()
    {
        verify_post_method();

        $post        = Post();
        $back_url    = $_SERVER['HTTP_REFERER'];
        $agendamento = new Agendamento();
        $horario_id  = $post->horario_id;
        
        $agendamento->setId( $post->id );
        $agendamento->setStatusAgendamento( $post->status_agendamento );
        $agendamento->setObservacoes( $post->observacoes );

        $editar_agendamento =  $this->agendaModel->editar_agendamento( $agendamento, $horario_id );

        if ($editar_agendamento->status == 'error') {
            $_SESSION['alert_message'] = [
                'type'        => 'danger', // ou 'error', 'warning', 'info'
                'title'       => 'Erro!',
                'message'     => 'Não foi possível editar o agendamento!',
                'dismissible' => true // se pode ser fechado ou não
            ];
            $this->helpers->redirect( $back_url );
        }

        // Quando quiser mostrar uma mensagem
        $_SESSION['alert_message'] = [
            'type'        => 'success', // pode ser: success, danger, warning, info, etc.
            'title'       => 'Sucesso!',
            'message'     => 'Agendamento editado com sucesso!',
            'dismissible' => true // se pode ser fechado ou não
        ];


        $this->helpers->redirect( $back_url );
    }


    public function formulario_ativo()
    {
        $get    = Get();
        $status = $get->status;

        if ( $status == '1' ) {
            $this->internModel->publish_form( 1 );
        } else {
            $this->internModel->publish_form( 0 );
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Formulário ativo alterado com sucesso!'
        ]);
    }

    public function limite_agendamentos()
    {
        $get     = Get();
        $limite  = $get->limite;
        $results = $this->internModel->update_limite_agendamentos( $limite );

        if ( $results->status == 'error' ) {
            echo TreatedJson([
                'status' => 'error',
                'message' => 'Não foi possível atualizar o limite de agendamentos!'
            ]);
            return;
        }
        echo TreatedJson([
            'status' => 'success',
            'message' => 'Limite de agendamentos atualizado com sucesso!'
        ]);
    }

    public function status_agendamento() {
        $this->isAuth();
    
        $post = Post();
    
        if(!isset($post->id) || empty($post->id)) {
            echo TreatedJson([
                'status' => "error",
                'message' => "ID do agendamento não informado"
            ]);
            return;
        }
    
        $id = $this->helpers->decodeURL($post->id);
        $motivo = $post->motivo ?? null;
        $status = $post->status;
    
        $params = [];
    
        if($motivo != null) {
            $params["motivo_cancelamento"] = htmlspecialchars($post->motivo);
        }
        
        $params['status_agendamento'] = $post->status;
        $params['updated_at'] = date('Y-m-d H:i:s'); 
        $r = $this->agendaModel->updateTable('agendamentos_clientes', $params, ['id' => $id]);
    
        if($r->status != 'error' && $r->affected_rows > 0) {
            echo TreatedJson([
                'status' => "success",
                'message' => "O Agendamento foi cancelado com sucesso!",
                'new_status' => $status,
                'motivo' => $motivo
            ]);
        } else {
            echo TreatedJson([
                'status' => "error",
                'message' => "Falha ao atualizar o agendamento",
                'error_details' => $r->message ?? "Erro desconhecido"
            ]);
        }
    }

}

