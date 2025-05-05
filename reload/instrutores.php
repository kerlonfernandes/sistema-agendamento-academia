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

if ($_SESSION['loggedUser'] != 1 || !isset($_SESSION['userId'])) { ?>
    <div class="alert alert-danger">Acesso não autorizado</div>
<?php exit;
}

$db = $op->database;

// Parâmetros da requisição
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$perPage = 10;
$offset = ($page - 1) * $perPage;

$query = "SELECT 
            u.id,
            u.nome, 
            u.email, 
            u.telefone, 
            u.profile_img,
            u.status,
            u.created_at,
            (
                SELECT COUNT(*) 
                FROM agendamentos_clientes ac 
                JOIN instrutor_horario ih ON ac.horario_id = ih.horario_id 
                WHERE ih.user_id = u.id AND ac.status_agendamento = 'agendado'
            ) as agendamentos_ativos,
            (
                SELECT COUNT(*) 
                FROM agendamentos_clientes ac 
                JOIN instrutor_horario ih ON ac.horario_id = ih.horario_id 
                WHERE ih.user_id = u.id AND ac.status_agendamento = 'confirmado'
            ) as agendamentos_confirmados,
            (
                SELECT COUNT(*) 
                FROM agendamentos_clientes ac 
                JOIN instrutor_horario ih ON ac.horario_id = ih.horario_id 
                WHERE ih.user_id = u.id AND ac.status_agendamento = 'cancelado'
            ) as agendamentos_cancelados
          FROM users u
          WHERE u.nivel_Acesso >= 2";

$params = [];
if (!empty($search)) {
    $query .= " AND (u.nome LIKE :search OR u.email LIKE :search OR u.telefone LIKE :search)";
    $params[':search'] = "%$search%";
}

// Executa a consulta principal com paginação
$query .= " ORDER BY u.nome ASC LIMIT $perPage OFFSET $offset";
$result = $db->execute_query($query, $params);

if ($result->status === 'success') { ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Agendamentos</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result->results as $instructor):
                $foto = $instructor->profile_img;
                $status = $instructor->status == 1 ? 'Ativo' : 'Inativo';
                $statusClass = $instructor->status == 1 ? 'success' : 'danger';
                $totalAgendamentos = $instructor->agendamentos_ativos + $instructor->agendamentos_confirmados + $instructor->agendamentos_cancelados;
            ?>
                <tr>
                    <td><?= $instructor->id ?></td>
                    <td><img src="<?= $foto ?>" class="rounded-circle" width="40" height="40" alt="Foto"></td>
                    <td><?= htmlspecialchars($instructor->nome) ?></td>
                    <td><?= htmlspecialchars($instructor->email) ?></td>
                    <td><?= htmlspecialchars($instructor->telefone) ?></td>
                    <td><span class="badge bg-<?= $statusClass ?>"><?= $status ?></span></td>
                    <td>
                        <span class="badge bg-primary">Total: <?= $totalAgendamentos ?></span>
                        <span class="badge bg-info">Ativos: <?= $instructor->agendamentos_ativos ?></span>
                        <span class="badge bg-success">Confirmados: <?= $instructor->agendamentos_confirmados ?></span>
                        <span class="badge bg-danger">Cancelados: <?= $instructor->agendamentos_cancelados ?></span>
                    </td>
                    <td>
                        <a class="btn btn-primary" href="<?= SITE ?>/admin/usuario/detalhes/<?= $instructor->id ?>">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    $countQuery = "SELECT COUNT(*) as total FROM users WHERE nivel_Acesso = 2";
    if (!empty($search)) {
        $countQuery .= " AND (nome LIKE :search OR email LIKE :search OR telefone LIKE :search)";
    }

    $totalResult = $db->execute_query($countQuery, $params);
    $totalInstructors = $totalResult->results[0]->total;
    $totalPages = ceil($totalInstructors / $perPage);
    ?>

    <!-- <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Anterior</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Próxima</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="text-muted text-center mt-2">
        Mostrando <?= count($result->results) ?> de <?= $totalInstructors ?> instrutores
    </div> -->

<?php } else { ?>
    <div class="alert alert-danger">Erro ao carregar dados dos instrutores</div>
<?php } ?>