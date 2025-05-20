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

$pagamento_id = $get->id;
$client_id = $get->user_id;
$pagamento = $op->database->execute_query("SELECT * FROM pendencias_financeiras WHERE id = ?", [$pagamento_id]);
    
if($pagamento->affected_rows <= 0){
    echo "<script>alert('Pagamento não encontrado');</script>";
    exit;
}

$registro = $pagamento->results[0];
?>

<form action="<?= SITE ?>/admin/financeiro/editar-pagamento" method="POST">
    <div class="modal-body">
        <input type="hidden" name="pagamento_id" id="pagamentoId" value="<?= $registro->id ?>">
        <input type="hidden" name="user_id" value="<?= $client_id ?>">
        <input type="hidden" name="back_url" value="<?= $get->back_url ?>">

        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="text" class="form-control moeda" id="valor" name="valor" value="<?= $registro->valor ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="dataPagamento" class="form-label">Data do Pagamento</label>
            <input type="date" class="form-control" id="dataPagamento" name="data_pagamento" value="<?= $registro->data_pagamento ?>" required>
        </div>

        <div class="mb-3">
            <label for="dataVencimento" class="form-label">Data de Vencimento</label>
            <input type="date" class="form-control" id="dataVencimento" name="data_vencimento" value="<?= $registro->data_vencimento ?>" required>
        </div>

        <div class="mb-3">
            <label for="formaPagamento" class="form-label">Forma de Pagamento</label>
            <select class="form-select" id="formaPagamento" name="forma_pagamento" required>
                <option value="">Selecione...</option>
                <option value="dinheiro" <?= $registro->forma_pagamento == "dinheiro" ? "selected" : "" ?>>Dinheiro</option>
                <option value="pix" <?= $registro->forma_pagamento == "pix" ? "selected" : "" ?>>PIX</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="">Selecione...</option>
                <option value="pendente" <?= $registro->status == "pendente" ? "selected" : "" ?>>Pendente</option>
                <option value="pago" <?= $registro->status == "pago" ? "selected" : "" ?>>Pago</option>
                <option value="cancelado" <?= $registro->status == "cancelado" ? "selected" : "" ?>>Cancelado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="observacao" class="form-label">Observação</label>
            <textarea class="form-control" id="observacao" name="observacao" rows="3"><?= $registro->observacoes ?></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
</form>