<?php

use HelpersClass\Helpers;
use Midspace\Models\Model;

require_once('./models/Model.php');
require_once('./classes/Helpers.class.php');


class UserModel extends Model
{
    public int $id;
    public Helpers $helpers;
    public function __construct(int $id = 0)
    {
        $this->id = $id;
        $this->helpers = new Helpers();
        parent::__construct('clientes');
    }
    /**
     * - Metodo para verificar se o cpf está cadastrado no painel de um usuário específico.
     * - Não verifica se o cpf está cadastrado no sistema como um todo, apenas de um cliente de um usuário específico
     * @param mixed $cpf
     * @param mixed $userId
     * @return stdClass
     */
    public function verificarCpf(string $cpf): stdClass
    {
        $cpfCheckSql = "SELECT id FROM clientes WHERE cpf = :cpf AND user_id = :user_id";
        $params = [':cpf' => $cpf, ":user_id" => $this->id];

        $cpfResult = $this->database->execute_query($cpfCheckSql, $params);

        return $cpfResult;
    }

    public function getData(): stdClass
    {
        $sql = "SELECT users.id AS user_id, users.* FROM `users` WHERE users.id = :id;";
        return $this->database->execute_query($sql, [':id' => $this->id]);
    }

    public function getClientCount(): stdClass
    {
        $cadastros = $this->database->execute_query(
            "SELECT COUNT(id) AS qtd_clientes FROM clientes WHERE user_id = :user_id",
            [":user_id" => $this->id]
        );

        return $cadastros;
    }

    public function getClientDocsCount(): stdClass
    {
        $documentos = $this->database->execute_query(
            'SELECT COUNT(id) as qtd_documentos FROM documentos WHERE user_id = :user_id',
            [":user_id" => $this->id]
        );

        return $documentos;
    }

    public function getByCpf(string $cpf): stdClass
    {
        $cpfCheckSql = "SELECT * FROM users WHERE cpfcnpj = :cpf";
        $params = [':cpf' => $cpf];

        $cpfResult = $this->database->execute_query($cpfCheckSql, $params);

        return $cpfResult;
    }

    public function createUser(string $nome, string $cpfCnpj, string $email, string $telefone, string $senha)
    {
        $sql = "INSERT INTO `users` 
            (`nome`, `cpf`, `email`, `telefone`, `password`, `vinculo`, `nivel_acesso`, `status`, `created_at`) 
            VALUES 
            (:nome, :cpf, :email, :telefone, :senha, :vinculo, :nivel_acesso, :status, :created_at)";
    
        $params = [
            ':nome'         => $nome,
            ':cpf'          => $cpfCnpj,
            ':email'        => $email,
            ':telefone'     => $telefone,
            ':senha'        => $this->helpers->hashPassword($senha),
            ':vinculo'      => 'Estudante', // ajuste conforme sua regra de negócio
            ':nivel_acesso' => 1,         // por padrão 1 = usuário comum
            ':status'       => 1,         // 1 = ativo
            ':created_at'   => date('Y-m-d H:i:s')
        ];
    
        return $this->database->execute_non_query($sql, $params);
    }
    
    public function addAccessLevel(int $userId, int $level)
    {
        $sql = "INSERT INTO `nivel_acesso` (`id_user`, `nivel_acesso`, `acesso`) VALUES (:id_user, :nivel_acesso, :acesso);";

        $acesso = '';

        switch ($level) {
            case 1:
                $acesso = "USUARIO";
                break;
            case 2:
                $acesso = "SUBUSUARIO";
                break;
            case 3:
                $acesso = "RESTRITO";
                break;
            case 4:
                $acesso = "ADMINISTRADOR";
                break;
            default:
                $level = 1;
                $acesso = "USUARIO";

                break;
        }

        $params = [
            ':id_user' => $userId,
            ':nivel_acesso' => $level,
            ':acesso' => $acesso,
        ];

        $result = $this->database->execute_non_query($sql, $params);
        return $result;
    }

    public function createPermissions($userId)
    {

        $sql = "INSERT INTO `permissoes` 
        (`user_id`, `geral`, `cadastrar_cliente`, `editar_cliente`, `deletar_cliente`, `acessar_cliente`, `area_arquivos_documentos`, `gerar_relatorio`, `lixeira`, `criado_em`, `atualizado_em`) 
        VALUES (:user_id, :geral, :cadastrar_cliente, :editar_cliente, :deletar_cliente, :acessar_cliente, :area_arquivos_documentos, :gerar_relatorio, :lixeira, :criado_em, :atualizado_em)";

        $params = [
            ':user_id' => $userId,
            ':geral' => '0',
            ':cadastrar_cliente' => '0',
            ':editar_cliente' => '0',
            ':deletar_cliente' => '0',
            ':acessar_cliente' => '0',
            ':area_arquivos_documentos' => '0',
            ':gerar_relatorio' => '0',
            ':lixeira' => '0',
            ':criado_em' => date('Y-m-d H:i:s'),
            ':atualizado_em' => date('Y-m-d H:i:s')
        ];


        return $this->database->execute_non_query($sql, $params);
    }

    public function updateCadastrarPainelFieldsValue($params): stdClass {
        $sql = "UPDATE `cadastro_clientes` SET `indicado`= :indicado,`trouxe`= :trouxe,`categoria`= :categoria,`senha_portal` = :senha_portal WHERE panel_id = :panel_id";

        return $this->database->execute_non_query($sql, $params);
    }

    public function getPanel() {
        return $this->select('*', "configuracoes_painel", 'user_id = :user_id', [":user_id" => $this->id]);
    }

    public function getCadastrarPainelFields($painelID) {
        return $this->select('*', "cadastro_clientes", 'panel_id = :panel_id', [":panel_id" => $painelID]);
    }

    public function createPanelConfig($userId) {
        $sql = "INSERT INTO configuracoes_painel (user_id) VALUES (:user_id)";

        $params = [
            ':user_id' => $userId,
        ];

        $result = $this->database->execute_non_query($sql, $params);

        return $result;
    }

    public function createCadastroClienteConfig($panelId) {
        $sql = "INSERT INTO cadastro_clientes (panel_id, indicado, trouxe, categoria, senha_portal) VALUES (:panel_id, 0,0,0,0)";

        $params = [":panel_id" => $panelId];

        return $this->database->execute_non_query($sql, $params);
    }

    public function categorias(): stdClass {
        $sql = "SELECT * FROM categorias WHERE creator_id = :user_id";
        $params = [":user_id" => $this->id];

        return $this->database->execute_query($sql, $params);
    }

    public function getUserByEmail(string $email) {
        return $this->selectFrom('users', "*", [], ['email' => $email], [], 1);
    }

}
