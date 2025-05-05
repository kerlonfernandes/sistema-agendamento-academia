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
$get = Get();

// Verifica autenticação
if ($_SESSION['loggedUser'] != 1 || !isset($_SESSION['userId'])) {
    die('<div class="alert alert-danger">Acesso não autorizado</div>');
}

$horario_id = $get->horario_id ?? null;

if (!$horario_id) {
    die('<div class="alert alert-danger">Horário não especificado</div>');
}

$agendamentos = $op->database->execute_query(
    "SELECT 
        ag.id as agendamento_id,
        u.nome as cliente_nome,
        u.email as cliente_email,
        u.telefone as cliente_telefone,
        ag.status_agendamento,
        DATE_FORMAT(ag.created_at, '%d/%m/%Y %H:%i') as data_agendamento
     FROM agendamentos_clientes ag
     JOIN users u ON ag.user_id = u.id
     WHERE ag.horario_id = :horario_id
     ORDER BY ag.created_at DESC",
    [':horario_id' => $horario_id]
);

if ($agendamentos->status === 'success' && $agendamentos->affected_rows > 0): ?>
    <div class="table-responsive">
        <div class="mb-3">
            <?php
            $instrutor_responsavel = $op->database->execute_query(
                "SELECT u.nome 
             FROM instrutor_horario ih
             JOIN users u ON ih.user_id = u.id
             WHERE ih.horario_id = :horario_id",
                [':horario_id' => $horario_id]
            );

            if ($instrutor_responsavel->status === 'success' && $instrutor_responsavel->affected_rows > 0):
            ?>
                <div class="alert alert-info">
                    <i class="bi bi-person-check"></i> Instrutor responsável:
                    <strong><?= htmlspecialchars($instrutor_responsavel->results[0]->nome) ?></strong>
                </div>
            <?php endif; ?>

            <button id="btn-resolver-todos" class="btn btn-success">
                <i class="bi bi-check-all"></i> Resolver Todos (e assumir horário)
            </button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Contato</th>
                    <th>Status</th>
                    <th>Data do Agendamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agendamentos->results as $agendamento): ?>
                    <tr>
                        <td><?= htmlspecialchars($agendamento->cliente_nome) ?></td>
                        <td>
                            <?= htmlspecialchars($agendamento->cliente_telefone) ?><br>
                            <small><?= htmlspecialchars($agendamento->cliente_email) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-<?=
                                                    $agendamento->status_agendamento == 'confirmado' ? 'success' : ($agendamento->status_agendamento == 'cancelado' ? 'danger' : 'warning')
                                                    ?>">
                                <?= ucfirst($agendamento->status_agendamento) ?>
                            </span>
                        </td>
                        <td><?= $agendamento->data_agendamento ?></td>
                        <td>
                            <?php if ($agendamento->status_agendamento != 'confirmado'): ?>
                                <button class="btn btn-sm btn-success btn-confirmar"
                                    data-id="<?= $agendamento->agendamento_id ?>">
                                    <i class="bi bi-check-circle"></i> Confirmar
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-danger btn-cancelar"
                                    data-id="<?= $agendamento->agendamento_id ?>">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <div class="alert alert-info">
        Nenhum agendamento encontrado para este horário.
    </div>
<?php endif; ?>