<?php

require_once('Model.php');

use Midspace\Models\Model;

class InternModel extends Model
{
    public function __construct()
    {
        parent::__construct('');
    }

    public function estados()
    {
        $result = $this->database->execute_query('SELECT * FROM estados', []);

        return $result ?: null;
    }


    public function get_configuracoes()
    {
        $result = $this->database->execute_query('SELECT * FROM configuracoes', []);
        return $result ?: null;
    }

    public function update_form_image(string $image_path)
    {
        $result = $this->database->execute_non_query('UPDATE configuracoes SET imagem_formulario = :image_path', [':image_path' => $image_path]);
        return $result ?: null;
    }

    public function publish_form(int $publish = 0 | 1)
    {
        $result = $this->database->execute_non_query('UPDATE configuracoes SET formulario_ativo = :publish', [':publish' => $publish]);
        return $result ?: null;
    }

    public function get_form_image()
    {
        $result = $this->database->execute_query('SELECT imagem_formulario FROM configuracoes', []);
        return $result ?: null;
    }

    public function get_form_publish()
    {
        $result = $this->database->execute_query('SELECT formulario_ativo FROM configuracoes', []);
        return $result ?: null;
    }

    public function update_primary_color(string $color)
    {
        $result = $this->database->execute_non_query('UPDATE configuracoes SET cor_primaria = :color', [':color' => $color]);
        return $result ?: null;
    }

    public function get_primary_color()
    {
        $result = $this->database->execute_query('SELECT cor_primaria FROM configuracoes', []);
        return $result ?: null;
    }

    public function update_aviso_formulario(string $aviso)
    {
        $result = $this->database->execute_non_query('UPDATE configuracoes SET texto_aviso_formulario = :aviso', [':aviso' => $aviso]);
        return $result ?: null;
    }

    public function get_aviso_formulario()
    {
        $result = $this->database->execute_query('SELECT texto_aviso_formulario FROM configuracoes', []);
        return $result ?: null;
    }

    public function add_vinculo(string $vinculo)
    {
        $result = $this->database->execute_non_query('UPDATE configuracoes SET vinculos = :vinculo', [':vinculo' => $vinculo]);
        return $result ?: null;
    }

    public function get_vinculo_formulario()
    {
        $result = $this->database->execute_query('SELECT vinculos FROM configuracoes', []);
        return $result ?: null;
    }



    public function update_dias_funcionamento(array $dias)
    {

        // ' \'{ "domingo": 0, "segunda-feira": 0, "terca-feira": 0, "quarta-feira": 0, "quinta-feira": 0, "sexta-feira": 0, "sabado": 0 } \' '


        // $result = $this->database->execute_query('UPDATE configuracoes SET dias_funcionamento = :dias', [':dias' => $dias]);
        // return $result ?: null;
    }

    public function get_dias_funcionamento()
    {
        $result = $this->database->execute_query('SELECT dias_funcionamento FROM configuracoes', []);
        return $result ?: null;
    }

    public function update_limite_agendamentos(int $limite)
    {
        $result = $this->database->execute_query('UPDATE configuracoes SET limite_agendamento = :limite', [':limite' => $limite]);
        return $result ?: null;
    }


    public function update_configuracoes(
        int $limit_agendamento = null,
        int $formulario_agendamento_ativo = null | 0 | 1,
        string $cor_primaria = null,

    ) {
        $result = $this->database->execute_query('UPDATE configuracoes SET ? WHERE id = 1', []);

        return $result ?: null;
    }

    public function add_horario(string $dia_semana, string $horario_inicio, string $horario_fim)
    {

        $results = $this->insertInto('horarios', ['dia_semana', 'horario_inicio', 'horario_fim'], [$dia_semana, $horario_inicio, $horario_fim]);

        return $results;
    }

    public function get_users()
    {
        $results = $this->selectFrom('users', '*', [], ['status' => "1"], ['created_at' => 'desc']);
        return $results;
    }

    public function get_usuarios_do_mes()
    {
        $results = $this->database->execute_query(
            'SELECT 
                    id, 
                    nome, 
                    cpf, 
                    telefone, 
                    email, 
                    vinculo, 
                    nivel_acesso, 
                    status, 
                    created_at
                FROM 
                    users
                WHERE 
                    MONTH(created_at) = MONTH(CURRENT_DATE()) 
                    AND YEAR(created_at) = YEAR(CURRENT_DATE())
                ORDER BY 
                    created_at DESC;', []
        );
        return $results;
    }

    public function get_ids_agendamentos_em_horarios_com_excesso(int $quantidade_maxima)
    {
        return $this->database->execute_query(
            "SELECT 
                ac.id AS agendamento_id
            FROM 
                agendamentos_clientes ac
            INNER JOIN (
                SELECT 
                    h.id AS horario_id
                FROM 
                    horarios h
                LEFT JOIN 
                    agendamentos_clientes ac ON h.id = ac.horario_id 
                    AND (ac.status_agendamento = 'agendado' OR ac.status_agendamento = 'confirmado')
                GROUP BY 
                    h.id
                HAVING 
                    COUNT(ac.id) >= :quantidade_maxima
            ) AS horarios_com_excesso ON ac.horario_id = horarios_com_excesso.horario_id
            WHERE 
                ac.status_agendamento = 'agendado' OR ac.status_agendamento = 'confirmado'
            ORDER BY 
                ac.horario_id, ac.id",
            [':quantidade_maxima' => $quantidade_maxima]
        );
    }

    public function get_pendencias_financeiras(int $user_id) {
        
        $financeiro = new stdClass();
        
        $pendencias = $this->database->execute_query(
            "SELECT 
                p.id,
                p.user_id,
                p.valor,
                p.data_vencimento,
                p.observacoes,
                p.status,
                p.created_at
            FROM pendencias_financeiras p
            WHERE p.user_id = :user_id AND p.status = 'pendente'",
            [':user_id' => $user_id]
        );


        $total = $this->database->execute_query(
            "SELECT SUM(p.valor) AS total FROM pendencias_financeiras p WHERE p.user_id = :user_id AND p.status = 'pago'",
            [':user_id' => $user_id]
        );

        $total_pendente = $this->database->execute_query(
            "SELECT SUM(p.valor) AS total FROM pendencias_financeiras p WHERE p.user_id = :user_id AND p.status = 'pendente'",
            [':user_id' => $user_id]
        );

        $total_cancelados = $this->database->execute_query(
            "SELECT SUM(p.valor) AS total FROM pendencias_financeiras p WHERE p.user_id = :user_id AND p.status = 'cancelado'",
            [':user_id' => $user_id]
        );
        

        $historico = $this->database->execute_query(
            "SELECT * FROM pendencias_financeiras WHERE user_id = :user_id",
            [':user_id' => $user_id]
        );

        $financeiro->pendencias = $pendencias->results;
        $financeiro->total = $total->results[0]->total;
        $financeiro->historico = $historico->results;
        $financeiro->total_pendente = $total_pendente->results[0]->total;
        $financeiro->total_cancelados = $total_cancelados->results[0]->total;
        
        return $financeiro;
    }
}
