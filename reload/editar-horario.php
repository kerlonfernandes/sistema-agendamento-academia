<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$get = Get();

if ($_SESSION['loggedUser'] != 1 || !isset($_SESSION['userId'])) {
    $response = [
        'status' => 'error',
        'message' => 'Usuário não autenticado.',
        'debug' => 'Usuário não está logado ou sessão inválida.'
    ];
    echo json_encode($response);
    return;
}

$agendamento = $op->database->execute_query(
    "SELECT * FROM agendamentos_clientes 
    LEFT JOIN horarios ON horarios.id = agendamentos_clientes.horario_id
    WHERE agendamentos_clientes.id = :id",
    [
        ":id" => $get->id
    ]
);

$horarios = $op->database->execute_query(
    "SELECT * FROM horarios",
    []
);

?>

<div class="row">
    <input type="hidden" name="id" value="<?= $get->id ?>">
    <div class="col-md-12 mb-3">
        <label for="horario-select" class="form-label">Horário:</label>
        <select name="horario_id" id="horario-select" class="form-control" required>
            <?php if ($horarios->affected_rows > 0) : ?>
                <?php foreach ($horarios->results as $horario) : ?>
                    <option value="<?php echo $horario->id; ?>" <?php echo $agendamento->results[0]->horario_id == $horario->id ? 'selected' : ''; ?>>
                        <?= $horario->dia_semana ?> - <?php echo $horario->horario_inicio; ?> - <?php echo $horario->horario_fim; ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="col-md-12">
        <label for="status"><strong>Status:</strong></label>
        <select name="status_agendamento" id="status" class="form-control">
            <option value="" disabled selected>Selecione o status</option>
            <option value="ativo" <?= $agendamento->results[0]->status_agendamento == 'ativo' ? 'selected' : '' ?>>Ativo</option>
            <option value="inativo" <?= $agendamento->results[0]->status_agendamento == 'inativo' ? 'selected' : '' ?>>Inativo</option>
            <option value="cancelado" <?= $agendamento->results[0]->status_agendamento == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            <option value="concluido" <?= $agendamento->results[0]->status_agendamento == 'concluido' ? 'selected' : '' ?>>Concluído</option>
        </select>
    </div>

    <div class="col-md-12">
        <label for="observacoes"><strong>Observações:</strong></label>
        <textarea name="observacoes" id="observacoes" class="form-control"><?= $agendamento->results[0]->observacoes ?></textarea>
    </div>
</div>

<style>
    .select2-container {
        z-index: 9999 !important;
    }

    .select2-container--open {
        z-index: 9999 !important;
    }

    .select2-container .select2-search--dropdown .select2-search__field {
        padding: 8px 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .select2-container .select2-search--dropdown .select2-search__field:focus {
        border-color: #007bff;
    }

    .select2-container .select2-results__option {
        padding: 10px 12px;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .select2-container .select2-results__option--highlighted {
        background-color: #007bff !important;
        color: white;
    }
</style>

<script>
    $('#horario-select').select2({
        placeholder: "Selecione um horário",
        allowClear: true,
        dropdownParent: $('#editar-horario')
    });
</script>