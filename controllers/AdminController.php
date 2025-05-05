<?php

use Midspace\Operations\Operations;

require_once("classes/Operations.class.php");
require_once("models/UserModel.php");
require_once("models/InternModel.php");
require_once("models/AgendaModel.php");

class AdminController extends Base
{

    public InternModel $internModel;
    public UserModel $userModel;
    public AgendaModel $agendaModel;
    public function __construct()
    {
        parent::__construct();
        $this->isAdminAuth();
        $this->internModel = new InternModel();
        $this->userModel = new UserModel();
        $this->agendaModel = new AgendaModel();
    }

    /** 
     * 
     * AŔEA PARA AS VIEWS
     * 
     */

    public function index()
    {
        $horario_proximo = $this->encontrar_horario_atual();
        $horario_inicio = $horario_proximo['horario']->horario_inicio;
        $horario_fim = $horario_proximo['horario']->horario_fim;
        $agendamentos_qtd = $this->agendaModel->get_agendamentos_qtd()->affected_rows;
        $total_usuarios = $this->internModel->get_users()->affected_rows;
        $users_do_mes = $this->internModel->get_usuarios_do_mes()->affected_rows;
        $agendamentos_hoje = $this->agendaModel->get_agendamentos_do_dia_atual();
        $ultimos_agendamentos = $this->agendaModel->get_ultimos_agendamentos(10);

        $this->view('admin/index', [
            'title' => "Admin | Dashboard",
            'proximo_horario' => "$horario_inicio - $horario_fim",
            'agendamentos_ativos' => $agendamentos_qtd,
            'total_usuarios' => $total_usuarios,
            'novos_usuarios' => $users_do_mes,
            'total_agendamentos_hoje' => $agendamentos_hoje->affected_rows,
            'agendamentos_hoje' => $agendamentos_hoje,
            'ultimos_agendamentos' => $ultimos_agendamentos
        ]);
    }

    // APPLICAÇÃO
    
    public function resolver_agendamentos() {

        $horarios_cadastrados = $this->agendaModel->get_dias_semana();

        $this->view('admin/resolver', [
            "title" => "Admin | Resolver horários",
            "horarios" => $horarios_cadastrados
        ]);
    }
    public function instrutores() {

        $horarios_cadastrados = $this->agendaModel->get_dias_semana();

        $this->view('admin/instrutores', [
            "title" => "Admin | Instrutores",
            "horarios" => $horarios_cadastrados
        ]);
    }

    public function configuracoes_view()
    {

        $this->checkAcess(4, function() {
            $this->helpers->redirect(SITE."/admin");
        });        

        $configuracoes          = $this->internModel->get_configuracoes()->results[0];
        $imagem_formulario      = $configuracoes->imagem_formulario;
        $publish_form           = $configuracoes->formulario_ativo;
        $primary_color          = $configuracoes->cor_primaria;
        $aviso_formulario       = $configuracoes->texto_aviso_formulario;
        $limite_agendamentos    = $configuracoes->limite_agendamento;
        $vinculo_formulario     = json_decode($configuracoes->vinculos);
        $dias_funcionamento     = json_decode($configuracoes->dias_funcionamento);
        $formulario_ativo       = $configuracoes->formulario_ativo;

         $metade = floor($limite_agendamentos / 2);
 
         if ($limite_agendamentos / 2 > $metade) {
             $metade = ceil($limite_agendamentos / 2);
         }
 
        $horarios_funcionamento = $this->agendaModel->get_horarios_cadastrados()->results;

        $this->view('admin/configuracoes', [
            'imagem_formulario'      => $imagem_formulario,
            'publish_form'           => $publish_form,
            'cor_primaria'           => $primary_color,
            'aviso_formulario'       => $aviso_formulario,
            'vinculo_formulario'     => $vinculo_formulario,
            'dias_funcionamento'     => $dias_funcionamento,
            'limite_agendamentos'    => $limite_agendamentos,
            'formulario_ativo'       => $formulario_ativo,
            'horarios_funcionamento' => $horarios_funcionamento,
            'metade'                 => $metade,
            'title'                  => "Configurações"
        ]);
    }

    public function relatorio_view()
    {
        $this->checkAcess(4, function() {
            $this->helpers->redirect(SITE."/admin");
        });    

        $configuracoes          = $this->internModel->get_configuracoes()->results[0];
        $dias_funcionamento     = $this->internModel->get_dias_funcionamento()->results[0];
        $array_dias_funcionamento = json_decode($dias_funcionamento->dias_funcionamento ?? '{}', true) ?: [];
        $horarios               = $this->agendaModel->get_horarios_cadastrados();
        $data_inicio            = $this->agendaModel->selectFrom('configuracoes', 'build', [], [])->results[0]->build;

        $this->view('admin/relatorios', [
            'title' => "UNIVC | Relatórios",
            'dias_semana' => $array_dias_funcionamento,
            'horarios' => $horarios,
            'data_inicio' => $data_inicio

        ]);
    }

    public function post_configuracoes()
    {

        verify_post_method();

        $post = Post();

        $params = array_filter([
            'texto_aviso_formulario' => $post->texto_aviso ?? null,
            'limite_agendamento'     => $post->limite_agendamentos ?? null,
            'cor_primaria'           => $post->cor_primaria ?? null,
        ], fn($value) => $value !== null);


        if (!empty($_FILES['imagem_formulario']['name'])) {
            $imagem_formulario = $_FILES['imagem_formulario'];
            $nomeImagem     = $imagem_formulario['name'];
            $tipoImagem     = $imagem_formulario['type'];
            $tamanhoImagem  = $imagem_formulario['size'];
            $tempImagem     = $imagem_formulario['tmp_name'];

            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($tipoImagem, $tiposPermitidos)) {
                $_SESSION['alert_message'] = [
                    'type' => 'danger',
                    'title' => 'Erro!',
                    'message' => 'Tipo de arquivo não permitido. Apenas imagens JPEG, PNG e GIF são aceitas.',
                    'dismissible' => true
                ];
                $this->helpers->redirect(url: SITE . "/admin/configuracoes");
                return;
            }

            $tamanhoMaximo = 5 * 1024 * 1024; // 5MB
            if ($tamanhoImagem > $tamanhoMaximo) {
                $_SESSION['alert_message'] = [
                    'type' => 'danger',
                    'title' => 'Erro!',
                    'message' => 'Arquivo muito grande. O tamanho máximo permitido é 5MB.',
                    'dismissible' => true
                ];
                $this->helpers->redirect(url: SITE . "/admin/configuracoes");
                return;
            }

            $diretorioDestino = 'public/src/images/';
            if (!is_dir($diretorioDestino)) {
                if (!mkdir($diretorioDestino, 0777, true)) {
                    $_SESSION['alert_message'] = [
                        'type' => 'danger',
                        'title' => 'Erro!',
                        'message' => 'Falha ao criar diretório para upload.',
                        'dismissible' => true
                    ];
                    $this->helpers->redirect(url: SITE . "/admin/configuracoes");
                    return;
                }
            }

            $nomeUnico = uniqid('imagem_', true) . '.' . pathinfo($nomeImagem, PATHINFO_EXTENSION);
            $caminhoCompleto = $diretorioDestino . $nomeUnico;

            if (!move_uploaded_file($tempImagem, $caminhoCompleto)) {
                $_SESSION['alert_message'] = [
                    'type' => 'danger',
                    'title' => 'Erro!',
                    'message' => 'Falha ao fazer upload da imagem.',
                    'dismissible' => true
                ];
                $this->helpers->redirect(url: SITE . "/admin/configuracoes");
                return;
            }

            $params['imagem_formulario'] = $nomeUnico;
        }

        $sql = "UPDATE configuracoes SET ";
        $updates = [];

        if ($params['imagem_formulario'] !== null) {
            $updates[] = "imagem_formulario = :imagem_formulario";
        }
        if ($params['texto_aviso_formulario'] !== null) {
            $updates[] = "texto_aviso_formulario = :texto_aviso_formulario";
        }
        if ($params['limite_agendamento'] !== null) {
            $updates[] = "limite_agendamento = :limite_agendamento";
        }
        if ($params['cor_primaria'] !== null) {
            $updates[] = "cor_primaria = :cor_primaria";
        }

        if (empty($updates)) {
            $_SESSION['alert_message'] = [
                'type' => 'warning',
                'title' => 'Alerta!',
                'message' => 'Nenhum dado foi fornecido para atualização!',
                'dismissible' => true
            ];
            $this->helpers->redirect(url: SITE . "/admin/configuracoes");
            return;
        }

        $sql .= implode(', ', $updates);

        $result = $this->internModel->database->execute_non_query($sql, $params);

        if ($result->status == 'error' && $result->affected_rows <= 0) {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'Não foi possível atualizar as configurações!',
                'dismissible' => true
            ];
        } else if ($result->affected_rows <= 0 && $result->status != 'error') {
            $_SESSION['alert_message'] = [
                'type' => 'warning',
                'title' => 'Alerta!',
                'message' => 'Nenhum dado foi fornecido para atualização!',
                'dismissible' => true
            ];
        } else {
            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'message' => 'Configurações atualizadas com sucesso!',
                'dismissible' => true
            ];
        }

        $this->helpers->redirect(url: SITE . "/admin/configuracoes");
    }

    public function post_dias_funcionamento()
    {
        verify_post_method();

        $post = Post();
    }
    public function post_vinculo_formulario()
    {
        verify_post_method();

        $post = Post();

        if (empty($post->novo_vinculo)) {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'O nome do vínculo não pode estar vazio.',
                'dismissible' => true
            ];
            $this->helpers->redirect(url: SITE . "/admin/configuracoes");
            return;
        }

        $novo_vinculo = trim($post->novo_vinculo);

        $vinculos = $this->internModel->get_vinculo_formulario()->results[0];
        $arrayVinculos = json_decode($vinculos->vinculos ?? '{}', true) ?: [];

        if (array_key_exists($novo_vinculo, $arrayVinculos)) {
            $_SESSION['alert_message'] = [
                'type' => 'warning',
                'title' => 'Aviso!',
                'message' => 'Este vínculo já está cadastrado.',
                'dismissible' => true
            ];
            $this->helpers->redirect(url: SITE . "/admin/configuracoes");
            return;
        }

        $arrayVinculos[$novo_vinculo] = $novo_vinculo;

        $result = $this->internModel->database->execute_non_query(
            "UPDATE configuracoes SET vinculos = :vinculos WHERE id = 1",
            ['vinculos' => json_encode($arrayVinculos, JSON_UNESCAPED_UNICODE)]
        );

        if ($result->affected_rows > 0) {
            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'message' => 'Vínculo adicionado com sucesso!',
                'dismissible' => true,
                'open_colapse' => true
            ];
        } else {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'Não foi possível adicionar o vínculo.',
                'dismissible' => true,
                'open_colapse' => true
            ];
        }

        $this->helpers->redirect(url: SITE . "/admin/configuracoes#vinculos_area");
    }

    public function remover_vinculo()
    {
        verify_get_method();

        $get = Get();
        $vinculo = $get->vinculo;

        $vinculos = $this->internModel->get_vinculo_formulario()->results[0];
        $arrayVinculos = json_decode($vinculos->vinculos ?? '{}', true) ?: [];

        if (array_key_exists($vinculo, $arrayVinculos)) {
            unset($arrayVinculos[$vinculo]);
        }

        $result = $this->internModel->database->execute_non_query(
            "UPDATE configuracoes SET vinculos = :vinculos WHERE id = 1",
            ['vinculos' => json_encode($arrayVinculos, JSON_UNESCAPED_UNICODE)]
        );

        if ($result->affected_rows > 0) {
            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'message' => 'Vínculo removido com sucesso!',
                'dismissible' => true,
                'open_colapse' => true
            ];
        } else {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'Não foi possível remover o vínculo.',
                'dismissible' => true,
                'open_colapse' => true
            ];
        }

        $this->helpers->redirect(url: SITE . "/admin/configuracoes#vinculos_area");
    }

    public function dias_funcionamento()
    {
        verify_get_method();

        $get = Get();
        $dia = $get->dia;
        $valor = $get->valor;

        $dias_funcionamento = $this->internModel->get_dias_funcionamento()->results[0];
        $arrayDiasFuncionamento = json_decode($dias_funcionamento->dias_funcionamento ?? '{}', true) ?: [];

        $arrayDiasFuncionamento[$dia] = $valor;

        $result = $this->internModel->database->execute_non_query(
            "UPDATE configuracoes SET dias_funcionamento = :dias_funcionamento WHERE id = 1",
            ['dias_funcionamento' => json_encode($arrayDiasFuncionamento, JSON_UNESCAPED_UNICODE)]
        );

        if ($result->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Dias de funcionamento atualizados com sucesso!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Não foi possível atualizar os dias de funcionamento!'
            ]);
        }
    }

    public function adicionar_horario()
    {
        verify_post_method();

        $post = Post();

        if (!isset($post->dia_semana) || !isset($post->horario_inicio) || !isset($post->horario_fim)) {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'Os campos devem ser todos preenchidos corretamente.',
                'dismissible' => true,
                'open_colapse' => true
            ];

            $this->helpers->redirect(url: SITE . "/admin/configuracoes#configuracoes_horarios");
        }

        $dia = $post->dia_semana;
        $horario_inicio = $post->horario_inicio;
        $horario_fim = $post->horario_fim;

        if (
            !preg_match('/^\d{2}:\d{2}:\d{2}$/', $horario_inicio) ||
            !preg_match('/^\d{2}:\d{2}:\d{2}$/', $horario_fim)
        ) {

            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Formato inválido!',
                'message' => 'Os horários devem estar no formato HH:MM:SS',
                'dismissible' => true,
                'open_colapse' => true
            ];

            $this->helpers->redirect(url: SITE . "/admin/configuracoes#configuracoes_horarios");
        }

        $horario_inicio_formatado = $horario_inicio;
        $horario_fim_formatado = $horario_fim;

        $verify = $this->internModel->selectFrom('horarios', '*', [], ['dia_semana' => $dia, 'horario_inicio' => $horario_inicio, 'horario_fim' => $horario_fim]);

        if ($verify->affected_rows >= 1) {
            $_SESSION['alert_message'] = [
                'type' => 'warning',
                'title' => 'Alerta!',
                'message' => 'Esse horário já foi cadastrado.',
                'dismissible' => true,
                'open_colapse' => true
            ];

            $this->helpers->redirect(url: SITE . "/admin/configuracoes#configuracoes_horarios");
        }

        $this->internModel->add_horario($dia, $horario_inicio_formatado, $horario_fim_formatado);

        $_SESSION['alert_message'] = [
            'type' => 'success',
            'title' => 'Sucesso!',
            'message' => 'Novo horário adicionado com sucesso!',
            'dismissible' => true,
            'open_colapse' => true
        ];

        $this->helpers->redirect(url: SITE . "/admin/configuracoes#configuracoes_horarios");
    }

    public function deleta_horario()
    {

        $get = Get();
        $id = $get->id;

        $results = $this->internModel->deleteFrom('horarios', ['id' => $id]);

        if ($results->status == 'error') {

            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'Não foi possível deletar um horário.',
                'dismissible' => true,
                'open_colapse' => true
            ];

            $this->helpers->redirect(url: SITE . "/admin/configuracoes#configuracoes_horarios");
        }

        $_SESSION['alert_message'] = [
            'type' => 'success',
            'title' => 'Sucesso!',
            'message' => 'Horário deletado com sucesso!',
            'dismissible' => true,
            'open_colapse' => true
        ];

        $this->helpers->redirect(url: SITE . "/admin/configuracoes#configuracoes_horarios");
    }

    public function editar_usuario()
    {

        verify_post_method();

        $post = Post();

        $results = $this->agendaModel->updateTable(
            'users',
            [
                'nome' => trim($post->nome),
                'cpf' => desformatarCPF(trim($post->cpf)),
                'telefone' => trim($post->telefone),
                'email' => trim($post->email),
                'vinculo' => trim($post->vinculo),
                'nivel_acesso' => $post->nivel_acesso
            ],
            ['id' => $post->id]
        );


        if ($results->affected_rows <= 0 && $results->status == 'success') {
            $_SESSION['alert_message'] = [
                'type' => 'warning',
                'title' => 'Aviso!',
                'message' => 'Nenhuma alteração foi feita.',
                'dismissible' => true,
            ];

            $this->helpers->redirect($post->back_url);
        }
        if ($results->affected_rows <= 0 && $results->status == 'error') {
            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Error!',
                'message' => 'Ocorreu um erro ao alterar os dados do usuário.',
                'dismissible' => true,
            ];

            $this->helpers->redirect($post->back_url);
        }

        $_SESSION['alert_message'] = [
            'type' => 'success',
            'title' => 'Sucesso!',
            'message' => 'Usuário editado com sucesso.',
            'dismissible' => true,
        ];

        $this->helpers->redirect($post->back_url);
    }


    public function acompanhamento_view()
    {
        $get = Get();
    
        $h = $this->agendaModel->horarios_disponiveis();
        $horarios = ($h->affected_rows > 0 && $h->status != 'error') ? $h->results : null;
        
        // Encontra o horário mais próximo (próximo agendamento)
        $horario_proximo = $this->encontrar_horario_atual();
    
        $horarios_atuais = [];
        $horario_inicio = null;
        $horario_fim = null;
    
        // verifica se veio o id do horário o 'h'
        if (!isset($get->h) || empty($get->h)) {
            // Se tiver horário próximo disponível
            if (!empty($horario_proximo['horario'])) {
                $horario_id = $horario_proximo['horario']->id;
                $horarios_atuais = $this->agendaModel->get_recent_appointments($horario_id);
                $horario_inicio = $horario_proximo['horario']->horario_inicio;
                $horario_fim = $horario_proximo['horario']->horario_fim;
            }
        } 
        else {
            $horario_id = $get->h;
            $horarios_atuais = $this->agendaModel->get_recent_appointments($horario_id);
            
            $hnow = $this->agendaModel->get_horario_by_id($horario_id);
            if ($hnow->affected_rows > 0) {
                $horario_inicio = $hnow->results[0]->horario_inicio;
                $horario_fim = $hnow->results[0]->horario_fim;
            }
        }
    
        $this->view('admin/acompanhamento', [
            'title'           => "Acompanhamento dos horários",
            'horario_proximo' => $horario_proximo,
            'horarios_atuais' => $horarios_atuais,
            'horario_inicio'  => $horario_inicio,
            'horario_fim'     => $horario_fim,
            'horarios'        => $horarios
        ]);
    }
    public function encontrar_horario_atual()
    {
        try {
            $horaAtual = date('H:i:s');
            $diaSemanaAtual = strtolower(date('l'));
            $diaSemanaAtual = $this->traduzir_dia_semana($diaSemanaAtual);

            $query = "SELECT id, dia_semana, horario_inicio, horario_fim 
                  FROM horarios 
                  WHERE dia_semana = :dia_semana
                  AND :hora_atual BETWEEN horario_inicio AND horario_fim
                  LIMIT 1";

            $params = [
                ':dia_semana' => $diaSemanaAtual,
                ':hora_atual' => $horaAtual
            ];

            $result = $this->agendaModel->database->execute_query($query, $params);

            if ($result->status === 'success' && !empty($result->results)) {
                $horario = $result->results[0];
                $agendamentos = $this->verificar_agendamentos($horario->id);

                return [
                    'horario' => $horario,
                    'agendamentos' => $agendamentos,
                    'tipo' => 'atual'
                ];
            }

            return $this->buscar_proximo_horario($diaSemanaAtual, $horaAtual);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function buscar_proximo_horario($diaSemanaAtual, $horaAtual)
    {
        $query = "SELECT id, dia_semana, horario_inicio, horario_fim 
              FROM horarios 
              WHERE dia_semana = :dia_semana
              AND horario_inicio > :hora_atual
              ORDER BY horario_inicio ASC
              LIMIT 1";

        $result = $this->agendaModel->database->execute_query($query, [
            ':dia_semana' => $diaSemanaAtual,
            ':hora_atual' => $horaAtual
        ]);

        if ($result->status === 'success' && !empty($result->results)) {
            $horario = $result->results[0];
            $agendamentos = $this->verificar_agendamentos($horario->id);

            return [
                'horario' => $horario,
                'agendamentos' => $agendamentos,
                'tipo' => 'proximo',
                'aviso' => 'Próximo horário: ' . $horario->horario_inicio
            ];
        }

        return $this->buscar_primeiro_horario_proximo_dia();
    }

    private function traduzir_dia_semana($diaIngles)
    {
        $traducao = [
            'monday' => 'segunda-feira',
            'tuesday' => 'terça-feira',
            'wednesday' => 'quarta-feira',
            'thursday' => 'quinta-feira',
            'friday' => 'sexta-feira',
            'saturday' => 'sábado',
            'sunday' => 'domingo'
        ];

        return $traducao[strtolower($diaIngles)] ?? $diaIngles;
    }

    private function buscar_primeiro_horario_proximo_dia()
    {
        $diasSemana = [
            'segunda-feira',
            'terça-feira',
            'quarta-feira',
            'quinta-feira',
            'sexta-feira',
            'sábado',
            'domingo'
        ];

        $diaAtual = date('N');

        for ($i = 1; $i <= 7; $i++) {
            $proximoDia = ($diaAtual + $i - 1) % 7;
            $diaSemana = $diasSemana[$proximoDia];

            $query = "SELECT id, dia_semana, horario_inicio, horario_fim 
                 FROM horarios 
                 WHERE dia_semana = :dia_semana
                 ORDER BY horario_inicio ASC
                 LIMIT 1";

            $result = $this->agendaModel->database->execute_query($query, [':dia_semana' => $diaSemana]);

            if ($result->status === 'success' && !empty($result->results)) {
                $horario = $result->results[0];
                $agendamentos = $this->verificar_agendamentos($horario->id);

                return [
                    'horario' => $horario,
                    'agendamentos' => $agendamentos,
                    'aviso' => 'Próximo horário disponível: ' . $diaSemana
                ];
            }
        }

        return ['aviso' => 'Nenhum horário disponível nos próximos dias'];
    }

    private function verificar_agendamentos($horario_id)
    {
        $query = "SELECT COUNT(*) as total, status_agendamento 
              FROM agendamentos_clientes 
              WHERE horario_id = :horario_id
              GROUP BY status_agendamento";

        $result = $this->agendaModel->database->execute_query($query, [':horario_id' => $horario_id]);

        if ($result->status === 'success') {
            return $result->results;
        }

        return [];
    }

    public function usuarios_view()
    {

        $usuarios = $this->internModel->get_users();

        $this->view('admin/usuarios', [
            'title' => "Acompanhamento dos horários",
            'usuarios' => $usuarios
        ]);
    }

    public function usuario_set_status()
    {
        $post = Post();

        if (empty($post->id ?? null)) {
            echo TreatedJson([
                'status' => 'error',
                'title' => 'Erro',
                'message' => 'ID do usuário não informado',
                'dismissible' => true
            ]);

            return;
        }

        $allowed_status = [0, 1, 2];
        if (!isset($post->status)) {
            echo TreatedJson([
                'status' => 'error',
                'title' => 'Erro',
                'message' => 'Status inválido ou não informado',
                'dismissible' => true
            ]);

            return;
        }

        try {
            $results = $this->internModel->updateTable('users', ['status' => $post->status], ['id' => $post->id]);

            if ($results->status != "success") {
                echo TreatedJson([
                    'status' => 'error',
                    'title' => 'Erro',
                    'message' => 'Ocorreu um erro ao deletar o cliente.',
                    'dismissible' => true
                ]);
            }

            echo TreatedJson([
                'status' => 'success',
                'title' => 'Sucesso!',
                'message' => 'Operação realizada com sucesso!',
                'dismissible' => true,
            ]);

            return;
        } catch (Exception $e) {
            echo TreatedJson([
                'status' => 'error',
                'title' => 'Erro no sistema',
                'message' => 'Ocorreu um erro: ' . $e->getMessage(),
                'dismissible' => true
            ]);
            return;
        }
    }

    public function deleta_agendamento()
    { {

            $get = Get();
            $id = $get->id;
            $back_url = $get->back_url;

            $results = $this->internModel->deleteFrom('agendamentos_clientes', ['id' => $id]);

            if ($results->status == 'error') {

                $_SESSION['alert_message'] = [
                    'type' => 'danger',
                    'title' => 'Erro!',
                    'message' => 'Não foi possível deletar o agendamento.',
                    'dismissible' => true,
                    'open_colapse' => true
                ];

                $this->helpers->redirect($back_url);
            }

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'message' => 'Agendamento deletado com sucesso!',
                'dismissible' => true,
                'open_colapse' => true
            ];

            $this->helpers->redirect($back_url);
        }
    }

    public function instrutor_remover_horario() {
        $get = Get();
        $id = $get->id;
        $back_url = $get->bkurl;

        $results = $this->internModel->deleteFrom('instrutor_horario', ['horario_id' => $id]);

        if ($results->status == 'error') {

            $_SESSION['alert_message'] = [
                'type' => 'danger',
                'title' => 'Erro!',
                'message' => 'Não foi possível remover o horário da sua lista de horários.',
                'dismissible' => true,
                'open_colapse' => true
            ];

            $this->helpers->redirect($back_url);
        }

        $_SESSION['alert_message'] = [
            'type' => 'success',
            'title' => 'Sucesso!',
            'message' => 'Horário removido com sucesso!',
            'dismissible' => true,
            'open_colapse' => true
        ];

        $this->helpers->redirect($back_url);
    }

}
