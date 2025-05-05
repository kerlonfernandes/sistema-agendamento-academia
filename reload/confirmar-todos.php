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

// Verifica autenticação
if ($_SESSION['loggedUser'] != 1 || !isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado']);
    exit;
}

$horario_id = $_POST['horario_id'] ?? null;
$instrutor_id = $_SESSION['userId']; // ID do instrutor logado

if (!$horario_id) {
    echo json_encode(['success' => false, 'message' => 'Horário não especificado']);
    exit;
}

// 1. Verifica se já existe um instrutor para este horário
$verifica_instrutor = $op->database->execute_query(
    "SELECT user_id FROM instrutor_horario WHERE horario_id = :horario_id",
    [':horario_id' => $horario_id]
);

if ($verifica_instrutor->status === 'success' && $verifica_instrutor->affected_rows > 0) {
    // Se já existe um instrutor, verifica se é o mesmo
    if ($verifica_instrutor->results[0]->user_id != $instrutor_id) {
        echo json_encode([
            'success' => false, 
            'message' => 'Este horário já está sendo gerenciado por outro instrutor'
        ]);
        exit;
    }
    // Se for o mesmo instrutor, não precisa fazer nova associação
} else {
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

// 2. Atualiza todos os agendamentos do horário
$result = $op->database->execute_query(
    "UPDATE agendamentos_clientes 
     SET status_agendamento = 'confirmado', 
         updated_at = NOW() 
     WHERE horario_id = :horario_id
     AND status_agendamento != 'confirmado'",
    [':horario_id' => $horario_id]
);

if ($result->status !== 'success') {
    echo json_encode(['success' => false, 'message' => 'Erro ao confirmar agendamentos']);
    exit;
}

$agendamentosConfirmados = $result->affected_rows;

$message = $agendamentosConfirmados > 0 
    ? "{$agendamentosConfirmados} agendamentos confirmados e você foi associado ao horário!" 
    : "Todos os agendamentos já estavam confirmados e você foi associado ao horário";

echo json_encode(['success' => true, 'message' => $message]);
?>