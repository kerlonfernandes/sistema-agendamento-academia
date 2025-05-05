<?php
session_start();

require "../_app/Configurations.php";
require "../classes/Helpers.class.php";
require "../_app/functions.php";
require "../classes/Database.class.php";
require "../classes/Operations.class.php";

use Midspace\Database;
use HelpersClass\Helpers;
use Midspace\Operations\Operations;

$helpers = new Helpers();
$op = new Operations();

if ($_SESSION['loggedUser'] != 1 || !isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado']);
    exit;
}

$agendamento_id = $_POST['agendamento_id'] ?? null;
$action = $_POST['action'] ?? 'confirmar';
$instrutor_id = $_SESSION['userId'];

if (!$agendamento_id) {
    echo json_encode(['success' => false, 'message' => 'Agendamento não especificado']);
    exit;
}

// 1. Primeiro verifica se o agendamento já está com outro instrutor
$verifica_instrutor = $op->database->execute_query(
    "SELECT ih.user_id 
     FROM instrutor_horario ih
     JOIN agendamentos_clientes ac ON ih.horario_id = ac.horario_id
     WHERE ac.id = :agendamento_id AND ih.user_id != :instrutor_id
     LIMIT 1",
    [
        ':agendamento_id' => $agendamento_id,
        ':instrutor_id' => $instrutor_id
    ]
);

if ($verifica_instrutor->status === 'success' && $verifica_instrutor->affected_rows > 0) {
    echo json_encode([
        'success' => false, 
        'message' => 'Este agendamento já está sendo gerenciado por outro instrutor'
    ]);
    exit;
}

// Define o status baseado na ação
$status = ($action === 'confirmar') ? 'confirmado' : 'cancelado';

// 2. Atualiza o status do agendamento
$result = $op->database->execute_query(
    "UPDATE agendamentos_clientes 
     SET status_agendamento = :status, 
         updated_at = NOW() 
     WHERE id = :id",
    [
        ':status' => $status,
        ':id' => $agendamento_id
    ]
);

if ($result->status !== 'success') {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status do agendamento']);
    exit;
}

// 3. Se for confirmação, associa o instrutor ao horário
if ($action === 'confirmar') {
    // Obtém o horario_id do agendamento
    $agendamento = $op->database->execute_query(
        "SELECT horario_id FROM agendamentos_clientes WHERE id = :id",
        [':id' => $agendamento_id]
    );

    if ($agendamento->status !== 'success' || $agendamento->affected_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Agendamento não encontrado']);
        exit;
    }

    $horario_id = $agendamento->results[0]->horario_id;

    // Verifica se já existe uma associação para este instrutor e horário
    $verifica_associacao = $op->database->execute_query(
        "SELECT id FROM instrutor_horario 
         WHERE user_id = :user_id AND horario_id = :horario_id
         LIMIT 1",
        [
            ':user_id' => $instrutor_id,
            ':horario_id' => $horario_id
        ]
    );

    if ($verifica_associacao->status === 'success' && $verifica_associacao->affected_rows === 0) {
        // Se não existe, cria a associação
        $associacao = $op->database->execute_query(
            "INSERT INTO instrutor_horario (user_id, horario_id) 
             VALUES (:user_id, :horario_id)",
            [
                ':user_id' => $instrutor_id,
                ':horario_id' => $horario_id
            ]
        );

        if ($associacao->status !== 'success') {
            echo json_encode(['success' => false, 'message' => 'Erro ao associar instrutor ao horário']);
            exit;
        }
    }
}

echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso']);
?>