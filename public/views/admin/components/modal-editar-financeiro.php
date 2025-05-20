<div class="modal fade" id="editarPagamentoModal" tabindex="-1" aria-labelledby="editarPagamentoModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarPagamentoModalLabel">Editar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="editar-pagamento-form"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editarPagamentoModal');
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