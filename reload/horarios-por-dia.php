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

if ($_SESSION['loggedUser'] != 1 || !isset($_SESSION['userId'])): 
    $response = array(
        'status' => 'error',
        'message' => 'Usuário não autenticado.',
        'debug' => 'Usuário não está logado ou sessão inválida.'
    );
    echo json_encode($response);
    return;
endif;

$nome_dia_semana = $get->dia_semana;
$r = $op->database->execute_query('SELECT * FROM `horarios` WHERE dia_semana = :dia_semana', array(':dia_semana' => $nome_dia_semana));

if ($r->status === 'success' && $r->affected_rows > 0): ?>
    <option value="">Selecione um horário</option>
    <?php foreach ($r->results as $horario): 
        $hora_inicio = substr($horario->horario_inicio, 0, 5);
        $hora_fim = substr($horario->horario_fim, 0, 5);
        ?>
        <option value="<?php echo $horario->id; ?>"><?php echo $hora_inicio . ' - ' . $hora_fim; ?></option>
    <?php endforeach; ?>
<?php else: ?>
    <option value="">Nenhum horário disponível para este dia</option>
<?php endif; ?>