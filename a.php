<?php
session_start();

use Midspace\Database;

include '../../_app/Config.inc.php';
require_once('../../_app/Classes/Database.class.php');

$idprova = $_SESSION['idprova'];

$database = new Database(MYSQL_CONFIG);

$result = $database->execute_query("SELECT * FROM locais WHERE id_prova= :id_prova ORDER BY id ASC", [':id_prova' => $idprova]);

$locais = $result->results;
?>



<table class="table table-sm table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Nome</th>
            <th>H. inicio</th>
            <th>H. fim</th>
            <th>Data</th>
            <th>Local</th>
            <th>Local Tipo</th>

        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->affected_rows > 0) {
            foreach ($locais as $local) {
        ?>
                <tr>
                    <td style="width: 120px;">
                        <button type="button" data-id="<?= $local->id ?>" data-nomelocal="<?= $local->nome_local ?>" data-datalocal="<?= $local->data_local ?>" data-horainicio="<?= $local->hora_inicio ?>" data-horafim="<?= $local->hora_fim ?>" data-locallink="<?= $local->local_link ?>" data-instrucao_link="<?= $local->instrucao_link ?>" data-mostrar_desc="<?= $local->mostrar_observacao ?>" data-observacoes="<?= $local->descricao_local ?>" data-local="<?= $local->local_tipo ?>" data-toggle="modal" data-target="#editarlocal" class="btn btn-primary btn-sm editalocal">Editar</button>
                        <button type="button" data-id="<?= $local->id ?>" class="btn btn-danger btn-sm deletalocal">Deletar</button>
 
       

                    </td>
                    <td><?= $local->id ?></td>
                    <td><?= $local->nome_local ?></td>
                    <td><?= $local->hora_inicio ?></td>
                    <td><?= $local->hora_fim ?></td>
                    <td><?= $local->data_local ?></td>
                    <td><?= $local->local_link ?></td>
                    <td><?= $local->local_tipo ?></td>

                </tr>
        <?php
            }
        } else {
            echo '<tr><td colspan="2">Nenhum Local cadastrado</td></tr>';
        }
        ?>
    </tbody>
</table>