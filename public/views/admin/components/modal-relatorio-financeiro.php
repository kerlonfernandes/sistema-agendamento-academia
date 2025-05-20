<div class="modal fade" id="registrarPagamentoModal" tabindex="-1" aria-labelledby="registrarPagamentoModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrarPagamentoModalLabel">Novo Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="<?= SITE ?>/admin/financeiro/registrar-pagamento" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="pagamento_id" id="pagamentoId">
                    <input type="hidden" name="user_id" value="<?= $client_id ?>">
                    <input type="hidden" name="back_url" value="<?= current_url() ?>">
                    
                    <div class="mb-3">
                        <label for="valor" class="form-label">Valor</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control moeda" id="valor" name="valor" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="dataPagamento" class="form-label">Data do Pagamento</label>
                        <input type="date" class="form-control" id="dataPagamento" name="data_pagamento" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="dataVencimento" class="form-label">Data de Vencimento</label>
                        <input type="date" class="form-control" id="dataVencimento" name="data_vencimento" value="<?= date('Y-m-d', strtotime('+1 month')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="formaPagamento" class="form-label">Forma de Pagamento</label>
                        <select class="form-select" id="formaPagamento" name="forma_pagamento" required>
                            <option value="">Selecione...</option>
                            <option value="dinheiro">Dinheiro</option>
                            <option value="pix">PIX</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Selecione...</option>
                            <option value="pendente">Pendente</option>
                            <option value="pago">Pago</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="observacao" class="form-label">Observação</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('registrarPagamentoModal');
    let previousActiveElement = null;

    modal.addEventListener('show.bs.modal', function() {
        previousActiveElement = document.activeElement;
        modal.removeAttribute('inert');
    });

    modal.addEventListener('hidden.bs.modal', function() {
        modal.setAttribute('inert', '');
        if (previousActiveElement) {
            previousActiveElement.focus();
        }
    });

    // Inicialmente, o modal está fechado e deve ser inert
    modal.setAttribute('inert', '');
});
</script>