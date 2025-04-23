<?php

namespace Midspace\Models;

require_once('classes/Operations.class.php');

use Midspace\Operations\Operations;

class Model extends Operations
{
    private string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
        parent::__construct();
    }

    public function get(): ?object
    {
        return $this->select("*", $this->table);
    }

    /**
     * Insere dados em uma tabela do banco de dados.
     *
     * Este método recebe o nome de uma tabela, uma lista de colunas e seus valores correspondentes,
     * e gera uma consulta SQL no formato `INSERT INTO` com placeholders preparados.
     * Em seguida, associa os valores dos parâmetros aos placeholders para execução segura.
     *
     * @param string $table O nome da tabela no banco de dados onde os dados serão inseridos.
     * @param array $columns Um array contendo os nomes das colunas a serem preenchidas.
     * @param array $params Um array contendo os valores correspondentes às colunas fornecidas.
     *                      A ordem dos valores deve coincidir com a ordem das colunas.
     *
     *              Para execução real, integre com PDO ou outro mecanismo de banco de dados.
     *
     * @example
     * ```php
     * $table = "users";
     * $columns = ["name", "email", "age"];
     * $params = ["John Doe", "john@example.com", 30];
     *
     * insertInto($table, $columns, $params);
     * ```
     * Resultado:
     * SQL: INSERT INTO users(name,email,age) VALUES (:name,:email,:age)
     * Parâmetros:
     * Array
     * (
     *     [:name] => John Doe
     *     [:email] => john@example.com
     *     [:age] => 30
     * )
     */
    public function insertInto(string $table, array $columns, array $params)
    {
        $sql = "INSERT INTO $table";

        // Gera a string com os nomes das colunas
        $columnsStr = '(' . implode(',', $columns) . ')';
        $sql .= $columnsStr;

        // Gera os placeholders
        $placeholders = [];
        foreach ($columns as $col) {
            $placeholders[] = ":$col";
        }

        $placeholdersStr = '(' . implode(',', $placeholders) . ')';
        $sql .= " VALUES " . $placeholdersStr;

        $mappedParams = [];
        foreach ($columns as $index => $col) {
            $mappedParams[":$col"] = $params[$index] ?? null; // Verifica se existe um valor correspondente
        }

        return $this->database->execute_non_query($sql, $mappedParams);
    }

    /**
     * Remove registros de uma tabela do banco de dados com base em condições.
     *
     * Este método recebe o nome de uma tabela, uma lista de condições (colunas) e seus valores correspondentes,
     * e gera uma consulta SQL no formato `DELETE FROM` com placeholders preparados.
     * Em seguida, associa os valores dos parâmetros aos placeholders para execução segura.
     *
     * @param string $table O nome da tabela no banco de dados onde os dados serão removidos.
     * @param array $conditions Um array associativo onde as chaves são os nomes das colunas
     *                          e os valores são os valores a serem usados nas condições.
     *
     *              Para execução real, integre com PDO ou outro mecanismo de banco de dados.
     *
     * @example
     * ```php
     * $table = "users";
     * $conditions = ["id" => 10, "status" => "inactive"];
     *
     * deleteFrom($table, $conditions);
     * ```
     * 
     * Resultado:
     * SQL: DELETE FROM users WHERE id = :id AND status = :status
     * Parâmetros:
     * Array
     * (
     *     [:id] => 10
     *     [:status] => inactive
     * )
     */
    public function deleteFrom(string $table, array $conditions)
    {
        $sql = "DELETE FROM $table";

        // Monta a cláusula WHERE com placeholders
        $whereClauses = [];
        $mappedParams = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = :$column";
            $mappedParams[":$column"] = $value;
        }

        // Adiciona a cláusula WHERE à consulta
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        // Exibe a consulta gerada e os parâmetros mapeados para depuração
        return $this->database->execute_non_query($sql, $mappedParams);
    }


    /**
     * Atualiza registros em uma tabela do banco de dados com base em condições.
     *
     * Este método recebe o nome de uma tabela, os valores a serem atualizados e as condições para identificar
     *  os registros,
     * gerando uma consulta SQL no formato `UPDATE` com placeholders preparados. 
     * Em seguida, associa os valores aos placeholders para execução segura.
     *
     * @param string $table O nome da tabela no banco de dados onde os dados serão atualizados.
     * @param array $data Um array associativo onde as chaves são os nomes das colunas a serem atualizadas
     *                    e os valores são os novos valores correspondentes.
     * @param array $conditions Um array associativo onde as chaves são os nomes das colunas
     *                          e os valores são as condições para identificar os registros.
     *
     *
     * @example
     * ```php
     * $table = "users";
     * $data = ["name" => "Jane Doe", "status" => "active"];
     * $conditions = ["id" => 10];
     *
     * updateTable($table, $data, $conditions);
     * ```
     *
     * Resultado:
     * SQL: UPDATE users SET name = :name, status = :status WHERE id = :id
     * Parâmetros:
     * Array
     * (
     *     [:name] => Jane Doe
     *     [:status] => active
     *     [:id] => 10
     * )
     */
    public function updateTable(string $table, array $data, array $conditions)
    {
        $sql = "UPDATE $table SET";

        // Monta a parte de atualização com placeholders
        $setClauses = [];
        $mappedParams = [];
        foreach ($data as $column => $value) {
            $setClauses[] = "$column = :$column";
            $mappedParams[":$column"] = $value;
        }

        $sql .= ' ' . implode(', ', $setClauses);

        // Monta a cláusula WHERE com placeholders
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = :where_$column";
            $mappedParams[":where_$column"] = $value;
        }

        // Adiciona a cláusula WHERE se existirem condições
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        return $this->database->execute_non_query($sql, $mappedParams);
    }

    /**
     * Realiza consultas SELECT no banco de dados com suporte a JOINs e outras operações.
     *
     * Este método permite criar consultas SQL dinâmicas para SELECT, com suporte para:
     * - Definir colunas a serem selecionadas.
     * - Adicionar múltiplos JOINs (LEFT, RIGHT, INNER, etc.).
     * - Adicionar cláusulas WHERE.
     * - Ordenar e limitar os resultados.
     *
     * @param string $table O nome da tabela principal.
     * @param array|string $columns Um array com as colunas a serem selecionadas (ou '*' para todas as colunas).
     * @param array $joins Um array de JOINs. Cada JOIN deve ser um array associativo no formato:
     *                     ['type' => 'INNER|LEFT|RIGHT', 'table' => 'other_table', 'on' => 'table1.column = table2.column'].
     * @param array $conditions Um array associativo para a cláusula WHERE no formato ['column' => 'value'].
     *                          Valores serão mapeados para placeholders.
     * @param array $order Um array para ordenar os resultados no formato ['column' => 'ASC|DESC'].
     * @param int|null $limit O número máximo de registros a serem retornados.
     * @param int|null $offset O deslocamento inicial para os registros (usado com LIMIT).
     *
     *
     * @example
     * ```php
     * $table = "users";
     * $columns = ["users.id", "users.name", "orders.total"];
     * $joins = [
     *     ['type' => 'INNER', 'table' => 'orders', 'on' => 'users.id = orders.user_id']
     * ];
     * $conditions = ["users.status" => "active"];
     * $order = ["users.name" => "ASC"];
     * $limit = 10;
     *
     * selectFrom($table, $columns, $joins, $conditions, $order, $limit);
     * ```
     * 
     * Resultado:
     * SQL: SELECT users.id, users.name, orders.total FROM users 
     *      INNER JOIN orders ON users.id = orders.user_id 
     *      WHERE users.status = :users_status 
     *      ORDER BY users.name ASC LIMIT 10
     * Parâmetros:
     * Array
     * (
     *     [:users_status] => active
     * )
     */
    public function selectFrom(
        string $table,
        $columns = '*',
        array $joins = [],
        array $conditions = [],
        array $order = [],
        int $limit = null,
        int $offset = null
    ) {
        // Início da consulta
        $sql = "SELECT ";

        // Seleciona as colunas
        if (is_array($columns)) {
            $sql .= implode(', ', $columns);
        } else {
            $sql .= $columns;
        }

        $sql .= " FROM $table";

        // Adiciona os JOINs
        foreach ($joins as $join) {
            $type = strtoupper($join['type']);
            $joinTable = $join['table'];
            $on = $join['on'];
            $sql .= " $type JOIN $joinTable ON $on";
        }

        // Adiciona a cláusula WHERE
        $mappedParams = [];
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $column => $value) {
                $placeholder = str_replace('.', '_', ":$column");
                $whereClauses[] = "$column = $placeholder";
                $mappedParams[$placeholder] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        // Adiciona ORDER BY
        if (!empty($order)) {
            $orderClauses = [];
            foreach ($order as $column => $direction) {
                $orderClauses[] = "$column $direction";
            }
            $sql .= " ORDER BY " . implode(', ', $orderClauses);
        }

        // Adiciona LIMIT e OFFSET
        if (!is_null($limit)) {
            $sql .= " LIMIT $limit";
            if (!is_null($offset)) {
                $sql .= " OFFSET $offset";
            }
        }

        // Exibe a consulta gerada e os parâmetros mapeados para depuração
        return $this->database->execute_query($sql, $mappedParams);

    }
}
