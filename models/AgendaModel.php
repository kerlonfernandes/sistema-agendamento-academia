<?php

require_once('Model.php');

use Midspace\Models\Model;

class AgendaModel extends Model
{

    public array $dias_semana;

    public function __construct()
    {

        $this->dias_semana = ['segunda-feira', 'terca-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sabado', 'domingo'];

        parent::__construct('');
    }


    public function quantidade_agendamentos(string $dia, string $horario)
    {
        $disponibilidade = $this->database->execute_query(
            "SELECT COUNT(*) AS total_agendamentos FROM agendamentos_clientes
             INNER JOIN horarios ON horarios.id = agendamentos_clientes.horario_id
             WHERE horarios.dia_semana = :dia
             AND CONCAT(
                 DATE_FORMAT(horarios.horario_inicio, '%H:%i'),
                 ' – ',
                 DATE_FORMAT(horarios.horario_fim, '%H:%i')
             ) = :horario
             AND agendamentos_clientes.status_agendamento = 'agendado'",
            [
                ':dia' => $dia,
                ':horario' => $horario
            ]
        );

        return $disponibilidade;
    }

    public function horarios_disponiveis()
    {

        $horarios = $this->database->execute_query(
            "SELECT
                    h.id,
                    ac.id AS id_agendamento,
                    h.dia_semana,
                    CONCAT(
                        h.horario_inicio,
                        ' – ',
                        h.horario_fim
                    ) AS horario,
                    COUNT(ac.id) AS total_agendamentos,
                    GROUP_CONCAT(u.nome SEPARATOR ', ') AS nomes_usuarios
                FROM
                    horarios h
                LEFT JOIN agendamentos_clientes ac ON
                    h.id = ac.horario_id
                LEFT JOIN users u ON
                    ac.user_id = u.id
                WHERE
                    (ac.status_agendamento = 'agendado' 
                    OR ac.status_agendamento = 'confirmado')
                GROUP BY
                    h.dia_semana,
                    h.horario_inicio,
                    h.horario_fim
                ORDER BY CASE
                    h.dia_semana WHEN 'segunda-feira' THEN 1 WHEN 'terça-feira' THEN 2 WHEN 'quarta-feira' THEN 3 WHEN 'quinta-feira' THEN 4 WHEN 'sexta-feira' THEN 5
                END,
                h.horario_inicio;",
            []
        );

        return $horarios;
    }

    public function horarios_disponiveis_por_dia(string $dia)
    {
        $horarios = $this->database->execute_query(
            "SELECT
                    h.dia_semana,
                    h.id,
                    CONCAT(
                        DATE_FORMAT(h.horario_inicio, '%H:%i'),
                        ' – ',
                        DATE_FORMAT(h.horario_fim, '%H:%i')
                    ) AS horario,
                    COUNT(ac.id) AS total_agendamentos,
                    GROUP_CONCAT(u.nome SEPARATOR ', ') AS nomes_usuarios
                FROM
                    horarios h
                LEFT JOIN agendamentos_clientes ac ON
                    h.id = ac.horario_id
                LEFT JOIN users u ON
                    ac.user_id = u.id
                WHERE
                    (ac.status_agendamento = 'agendado' 
                    OR ac.status_agendamento = 'confirmado' OR ac.status_agendamento IS NULL)
                    AND h.dia_semana = :dia
                GROUP BY
                    h.dia_semana,
                    h.horario_inicio,
                    h.horario_fim
                ORDER BY
                    h.horario_inicio;",
            [':dia' => $dia]
        );

        return $horarios;
    }

    public function horarios_disponiveis_por_horario(string $horario)
    {
        $horarios = $this->database->execute_query(
            "SELECT
                    h.dia_semana,
                    CONCAT(
                        DATE_FORMAT(h.horario_inicio, '%H:%i'),
                        ' – ',
                        DATE_FORMAT(h.horario_fim, '%H:%i')
                    ) AS horario,
                    COUNT(ac.id) AS total_agendamentos,
                    GROUP_CONCAT(u.nome SEPARATOR ', ') AS nomes_usuarios
                FROM
                    horarios h
                LEFT JOIN agendamentos_clientes ac ON
                    h.id = ac.horario_id
                LEFT JOIN users u ON
                    ac.user_id = u.id
                WHERE
                      (ac.status_agendamento = 'agendado' 
                    OR ac.status_agendamento = 'confirmado' OR ac.status_agendamento IS NULL)
                    AND CONCAT(
                        DATE_FORMAT(h.horario_inicio, '%H:%i'),
                        ' – ',
                        DATE_FORMAT(h.horario_fim, '%H:%i')
                    ) = :horario
                GROUP BY
                    h.dia_semana,
                    h.horario_inicio,
                    h.horario_fim
                ORDER BY CASE
                    h.dia_semana WHEN 'segunda-feira' THEN 1 WHEN 'terça-feira' THEN 2 WHEN 'quarta-feira' THEN 3 WHEN 'quinta-feira' THEN 4 WHEN 'sexta-feira' THEN 5
                END;",
            [':horario' => $horario]
        );

        return $horarios;
    }

    public function get_horarios_cadastrados()
    {
        $horarios = $this->database->execute_query("SELECT * FROM horarios", []);
        return $horarios;
    }

    public function get_dias_semana()
    {
        $horarios = $this->database->execute_query("SELECT DISTINCT dia_semana FROM horarios", []);
        return $horarios;
    }

    public function get_horarios_by_nome_dia($nome_dia_semana)
    {
        $r = $this->database->execute_query('SELECT * FROM `horarios` WHERE dia_semana = :dia_semana', [':dia_semana' => $nome_dia_semana]);
        return $r;
    }

    public function horarios_disponiveis_por_dia_horario(string $dia, string $horario)
    {
        $horarios = $this->database->execute_query(
            "SELECT
                    h.dia_semana,
                    CONCAT(
                        DATE_FORMAT(h.horario_inicio, '%H:%i'),
                        ' – ',
                        DATE_FORMAT(h.horario_fim, '%H:%i')
                    ) AS horario,
                    COUNT(ac.id) AS total_agendamentos,
                    GROUP_CONCAT(u.nome SEPARATOR ', ') AS nomes_usuarios
                FROM
                    horarios h
                LEFT JOIN agendamentos_clientes ac ON
                    h.id = ac.horario_id
                LEFT JOIN users u ON
                    ac.user_id = u.id
                WHERE
                    (ac.status_agendamento = 'agendado' 
                    OR ac.status_agendamento = 'confirmado' OR ac.status_agendamento IS NULL)
                    AND h.dia_semana = :dia
                    AND CONCAT(
                        DATE_FORMAT(h.horario_inicio, '%H:%i'),
                        ' – ',
                        DATE_FORMAT(h.horario_fim, '%H:%i')
                    ) = :horario
                GROUP BY
                    h.dia_semana,
                    h.horario_inicio,
                    h.horario_fim;",
            [
                ':dia' => $dia,
                ':horario' => $horario
            ]
        );

        return $horarios;
    }

    public function verifica_usuario_existe(User $user)
    {
        $user_data = $this->get_user_data_by_email($user);
        if ($user_data->affected_rows <= 0 || $user_data->status != 'success') {
            return false;
        }
        return true;
    }

    public function get_user_data_by_email(User $user)
    {
        $user_data = $this->database->execute_query("SELECT * FROM users WHERE email = :email", [
            ':email' => $user->getEmail()
        ]);

        return $user_data;
    }


    public function add_user(User $user)
    {
        $user_data = $this->database->execute_query("INSERT INTO users (email, nome, telefone, vinculo) VALUES (:email, :name, :telefone, :vinculo)", [
            ':email' => $user->getEmail(),
            ':name' => $user->getName(),
            ':telefone' => $user->getTelefone(),
            ':vinculo' => $user->getVinculo()
        ]);

        return $user_data;
    }


    /**
     * Retorna o ID do horário com base no horário e no dia
     * @param string $horario
     * @param string $dia
     * @return int|false
     */
    public function get_horario_id(string $horario, string $dia)
    {
        $horarios_string = explode(' – ', $horario);
        $horario_inicio = $horarios_string[0];
        $horario_fim = $horarios_string[1];

        $horario_id = $this->database->execute_query(
            "SELECT id FROM horarios 
             WHERE horario_inicio LIKE :horario_inicio 
             AND horario_fim LIKE :horario_fim
             AND dia_semana = :dia",
            [
                ':horario_inicio' => '%' . $horario_inicio . '%',
                ':horario_fim' => '%' . $horario_fim . '%',
                ':dia' => $dia
            ]
        );

        if ($horario_id->affected_rows <= 0 || $horario_id->status != 'success') {
            return false;
        }

        return $horario_id->results[0]->id;
    }

    /**
     * Executa o agendamento
     * 
     * TODO: Melhorar depois, transformando em funções menores e mais específicas
     * 
     * @param Agendamento $agendamento
     * @param User $user
     * @return object
     */
    public function executa_agendamento(Agendamento $agendamento, User $user, int $limite_agendamento = 10)
    {

        // if (!$this->verifica_usuario_existe($user)) {
        //     $this->add_user($user);
        // }
        $user_data = $this->get_user_data_by_email($user);

        if ($user_data->affected_rows <= 0 || $user_data->status != 'success') {
            return (object) [
                "status" => "error",
                "message" => "Ocorreu um erro ao agendar o horário. Por favor, tente novamente."
            ];
        }

        $user->setId($user_data->results[0]->id);

        $disponibilidade = $this->quantidade_agendamentos($agendamento->getDiaSemana(), $agendamento->getHorario());

        if ($disponibilidade->affected_rows <= 0 || $disponibilidade->status != 'success') {

            return (object) [
                "status" => "error",
                "message" => "Ocorreu um erro ao agendar o horário. Por favor, tente novamente."
            ];
        }


        // verifica se o usuário tem algum agendamento naquele dia da semana..
        $agendamento_dia_semana = $this->agendamento_existe_dia_semana($user->getId(), $agendamento->getDiaSemana());

        if ($agendamento_dia_semana->results[0]->cliente_dia_semana > 0) {
            return (object) [
                "status" => "error",
                "message" => "<div class='alert alert-danger'><small>Não foi possível fazer o agendamento: <strong>" . $agendamento->getHorario() . " no dia " . $agendamento->getDiaSemana() . "</strong>, pois é apenas é permitido um dia da semana por pessoa.</small></div>"
            ];
        }

        // TODO: adicionar o limite dinâmico das configurações
        // Feito.
        if ($disponibilidade->results[0]->total_agendamentos >= $limite_agendamento) {
            return (object) [
                "status" => "error",
                "message" => "Não há mais vagas disponíveis para o horário de " . $agendamento->getHorario() . " no dia " . $agendamento->getDiaSemana() . "."
            ];
        }

        $verificar_agendamento_existente = $this->verificar_agendamento_existente($user->getEmail(), $agendamento->getHorario(), $agendamento->getDiaSemana());

        $user_id = $user->getId();
        $horario_id = $this->get_horario_id($agendamento->getHorario(), $agendamento->getDiaSemana());

        if ($verificar_agendamento_existente->affected_rows > 0) {
            return (object) [
                "status" => "error",
                "message" => "O horário já está agendado."
            ];
        }

        $insert_agendamento = $this->database->execute_query("INSERT INTO agendamentos_clientes (horario_id, user_id, status_agendamento, observacoes) VALUES (:horario_id, :user_id, :status_agendamento, :observacoes)", [
            ':horario_id' => $horario_id,
            ':user_id' => $user_id,
            ':status_agendamento' => 'agendado',
            ':observacoes' => $agendamento->getObservacoes(),
        ]);

        if ($insert_agendamento->affected_rows <= 0 || $insert_agendamento->status != 'success') {

            return (object) [
                "status" => "error",
                "message" => "Ocorreu um erro ao agendar o horário. Por favor, tente novamente."
            ];
        }

        return (object) [
            "status" => "success",
            "message" => "Agendamento realizado com sucesso."
        ];
    }

    public function verificar_agendamento_existente(string $email, string $horario, string $dia)
    {
        $disponibilidade = $this->database->execute_query(
            "SELECT horarios.*, horarios.id as horario_id, users.id as user_id
             FROM agendamentos_clientes
             INNER JOIN users ON agendamentos_clientes.user_id = users.id
             INNER JOIN horarios ON agendamentos_clientes.horario_id = horarios.id
             WHERE users.email = :email 
             AND horarios.dia_semana = :dia
             AND CONCAT(
                 DATE_FORMAT(horarios.horario_inicio, '%H:%i'),
                 ' – ',
                 DATE_FORMAT(horarios.horario_fim, '%H:%i')
             ) = :horario
             AND   (ac.status_agendamento = 'agendado' 
                    OR ac.status_agendamento = 'confirmado')",
            [
                ':email' => $email,
                ':dia' => $dia,
                ':horario' => $horario
            ]
        );

        return $disponibilidade;
    }

    public function get_agendamentos_qtd()
    {
        $disponibilidade = $this->database->execute_query(
            "SELECT * FROM agendamentos_clientes WHERE status_agendamento != 'cancelado' OR status_agendamento != 'inativo' ",
            []
        );

        return $disponibilidade;
    }

    public function get_agendamentos()
    {
        $agendamentos = $this->database->execute_query(
            "SELECT 
                users.id as user_id,
                users.nome,
                users.email,
                users.telefone,
                COUNT(agendamentos_clientes.id) as total_horarios,
                GROUP_CONCAT(
                    CONCAT(
                        horarios.dia_semana,
                        ' - ',
                        DATE_FORMAT(horarios.horario_inicio, '%H:%i'),
                        ' – ',
                        DATE_FORMAT(horarios.horario_fim, '%H:%i')
                    ) SEPARATOR '|'
                ) as horarios_agendados
            FROM users 
            LEFT JOIN agendamentos_clientes ON users.id = agendamentos_clientes.user_id
            LEFT JOIN horarios ON agendamentos_clientes.horario_id = horarios.id
            WHERE (agendamentos_clientes.status_agendamento = 'agendado' 
         OR agendamentos_clientes.status_agendamento = 'confirmado') AND users.status = 1
            GROUP BY users.id, users.nome, users.email, users.telefone
            ORDER BY total_horarios DESC",
            []
        );

        return $agendamentos;
    }

    public function get_user_horarios($id)
    {
        $user_horarios = $this->database->execute_query(
            "SELECT horarios.*, users.*, agendamentos_clientes.*, agendamentos_clientes.id as agendamento_id FROM agendamentos_clientes
            LEFT JOIN horarios ON agendamentos_clientes.horario_id = horarios.id
            LEFT JOIN users ON agendamentos_clientes.user_id = users.id
            WHERE user_id = :id",
            [
                ':id' => $id
            ]
        );
        return $user_horarios;
    }

    public function user_horarios_client($id)
    {
        $user_horarios = $this->database->execute_query(
            "SELECT 
            horarios.id,
            horarios.dia_semana,
            horarios.horario_inicio,
            horarios.horario_fim,
            users.*,
            agendamentos_clientes.*,
            agendamentos_clientes.id as agendamento_id,
            instrutor.id as instrutor_id,
            SUBSTRING_INDEX(instrutor.nome, ' ', 1) as instrutor_primeiro_nome,
            instrutor.nome as instrutor_nome_completo,
            instrutor.profile_img as instrutor_profile_img,
            GROUP_CONCAT(
                CONCAT(
                    DATE_FORMAT(horarios.horario_inicio, '%H:%i'),
                    ' – ',
                    DATE_FORMAT(horarios.horario_fim, '%H:%i')
                ) SEPARATOR '|'
            ) as horarios_formatados
        FROM agendamentos_clientes
        LEFT JOIN horarios ON agendamentos_clientes.horario_id = horarios.id
        LEFT JOIN users ON agendamentos_clientes.user_id = users.id
        LEFT JOIN instrutor_horario ih ON horarios.id = ih.horario_id
        LEFT JOIN users instrutor ON ih.user_id = instrutor.id
        WHERE agendamentos_clientes.user_id = :id
        GROUP BY agendamentos_clientes.id, instrutor.id",
            [
                ':id' => $id
            ]
        );

        return $user_horarios;
    }

    public function get_user_by_id($id)
    {
        $user = $this->database->execute_query("SELECT * FROM users WHERE id = :id", [
            ':id' => $id
        ]);
        return $user;
    }

    public function editar_agendamento(Agendamento $agendamento, int $horario_id)
    {

        $results = $this->database->execute_query("UPDATE agendamentos_clientes SET horario_id = :horario_id, status_agendamento = :status_agendamento, observacoes = :observacoes WHERE id = :id", [
            ':horario_id' => $horario_id,
            ':status_agendamento' => $agendamento->getStatusAgendamento(),
            ':observacoes' => $agendamento->getObservacoes(),
            ':id' => $agendamento->getId()
        ]);

        return $results;
    }

    public function agendamento_existe_dia_semana(int $cliente_id, string $dias_semana)
    {
        $results = $this->database->execute_query(
            'SELECT 
                    COUNT(*) AS cliente_dia_semana
                FROM 
                    users u
                JOIN 
                    agendamentos_clientes ac ON ac.user_id = u.id
                JOIN 
                    horarios h ON ac.horario_id = h.id
                WHERE 
                    u.id = :user_id
                    AND h.dia_semana = :dia_semana',
            [
                ":user_id" => $cliente_id,
                ":dia_semana" => $dias_semana
            ]
        );

        return $results;
    }

    public function get_recent_appointments($id_horario)
    {
        $query = 'SELECT 
            users.id AS user_id, 
            users.nome, 
            users.telefone,
            horarios.dia_semana, 
            horarios.horario_inicio, 
            horarios.horario_fim,
            agendamentos_clientes.status_agendamento,
            agendamentos_clientes.created_at AS data_agendamento
        FROM agendamentos_clientes
        INNER JOIN users ON users.id = agendamentos_clientes.user_id
        INNER JOIN horarios ON horarios.id = agendamentos_clientes.horario_id
        WHERE agendamentos_clientes.horario_id = :horario_id
        AND (agendamentos_clientes.status_agendamento = "agendado" 
             OR agendamentos_clientes.status_agendamento = "confirmado")';

        $params = [":horario_id" => $id_horario];

        $results = $this->database->execute_query($query, $params);

        return $results;
    }

    public function get_horario_by_id(int $id)
    {
        return $this->selectFrom('horarios', '*', [], ['id' => $id], [], 1);
    }

    public function get_agendamento_by_id($id)
    {
        return $this->database->execute_query(
            'SELECT horarios.dia_semana, horarios.horario_inicio, horarios.horario_fim, agendamentos_clientes.status_agendamento, agendamentos_clientes.user_id, agendamentos_clientes.observacoes, agendamentos_clientes.motivo_cancelamento, agendamentos_clientes.created_at 
        FROM agendamentos_clientes
        LEFT JOIN horarios ON horarios.id = horario_id
        WHERE agendamentos_clientes.id = :id',
            [':id' => $id]
        );
    }
    public function get_agendamentos_do_dia_atual()
    {
        // Obtém o dia da semana atual em português (igual ao formato do seu banco)
        $dia_semana_atual = strtolower(date('l'));
        $dias_semana_map = [
            'monday' => 'segunda-feira',
            'tuesday' => 'terca-feira',
            'wednesday' => 'quarta-feira',
            'thursday' => 'quinta-feira',
            'friday' => 'sexta-feira',
            'saturday' => 'sabado',
            'sunday' => 'domingo'
        ];
        $dia_hoje = $dias_semana_map[$dia_semana_atual] ?? '';

        if (empty($dia_hoje)) {
            return (object) [
                "status" => "error",
                "message" => "Não foi possível determinar o dia da semana"
            ];
        }

        $query = "SELECT 
                    ac.id as agendamento_id,
                    u.id as user_id,
                    u.nome as cliente_nome,
                    u.email as cliente_email,
                    u.telefone as cliente_telefone,
                    h.dia_semana,
                    DATE_FORMAT(h.horario_inicio, '%H:%i') as horario_inicio,
                    DATE_FORMAT(h.horario_fim, '%H:%i') as horario_fim,
                    ac.status_agendamento,
                    ac.observacoes,
                    ac.created_at as data_agendamento
                FROM 
                    agendamentos_clientes ac
                INNER JOIN 
                    users u ON ac.user_id = u.id
                INNER JOIN 
                    horarios h ON ac.horario_id = h.id
                WHERE 
                    h.dia_semana = :dia_semana
                    AND (ac.status_agendamento = 'agendado' 
                    OR ac.status_agendamento = 'confirmado' OR ac.status_agendamento IS NULL)
                ORDER BY 
                    h.horario_inicio ASC";

        $params = [":dia_semana" => $dia_hoje];

        return $this->database->execute_query($query, $params);
    }

    public function get_ultimos_agendamentos($limit = 10)
    {
        $limit = (int)$limit;

        $query = "SELECT 
                ac.id as agendamento_id,
                u.nome as cliente_nome,
                u.email as cliente_email,
                u.telefone as cliente_telefone,
                h.dia_semana,
                DATE_FORMAT(h.horario_inicio, '%H:%i') as horario_inicio,
                DATE_FORMAT(h.horario_fim, '%H:%i') as horario_fim,
                ac.status_agendamento,
                ac.observacoes,
                DATE_FORMAT(ac.created_at, '%d/%m/%Y %H:%i') as data_agendamento_formatada,
                ac.created_at
            FROM 
                agendamentos_clientes ac
            INNER JOIN 
                users u ON ac.user_id = u.id
            INNER JOIN 
                horarios h ON ac.horario_id = h.id
                WHERE (ac.status_agendamento = 'agendado' 
                    OR ac.status_agendamento = 'confirmado' OR ac.status_agendamento IS NULL)
            ORDER BY 
                ac.created_at DESC
            LIMIT " . $limit;

        return $this->database->execute_query($query, []);
    }

    public function get_instructor_horarios(int $id)
    {
        return $this->database->execute_query(
            "SELECT 
            h.id,
            h.dia_semana,
            DATE_FORMAT(h.horario_inicio, '%H:%i') as horario_inicio,
            DATE_FORMAT(h.horario_fim, '%H:%i') as horario_fim,
            CONCAT(
                DATE_FORMAT(h.horario_inicio, '%H:%i'),
                ' – ',
                DATE_FORMAT(h.horario_fim, '%H:%i')
            ) as horario_formatado,
            COUNT(ac.id) as total_agendamentos,
            SUM(CASE WHEN ac.status_agendamento = 'agendado' THEN 1 ELSE 0 END) as agendamentos_ativos,
            SUM(CASE WHEN ac.status_agendamento = 'confirmado' THEN 1 ELSE 0 END) as agendamentos_confirmados,
            SUM(CASE WHEN ac.status_agendamento = 'cancelado' THEN 1 ELSE 0 END) as agendamentos_cancelados
        FROM 
            instrutor_horario ih
        JOIN 
            horarios h ON ih.horario_id = h.id
        LEFT JOIN 
            agendamentos_clientes ac ON h.id = ac.horario_id
        WHERE 
            ih.user_id = :instructor_id
        GROUP BY 
            h.id, h.dia_semana, h.horario_inicio, h.horario_fim
        ORDER BY 
            CASE h.dia_semana 
                WHEN 'segunda-feira' THEN 1 
                WHEN 'terca-feira' THEN 2 
                WHEN 'quarta-feira' THEN 3 
                WHEN 'quinta-feira' THEN 4 
                WHEN 'sexta-feira' THEN 5 
                WHEN 'sabado' THEN 6 
                WHEN 'domingo' THEN 7 
            END,
            h.horario_inicio",
            [':instructor_id' => $id]
        );
    }
}
