<div class="agendamento-detalhes">
    <!-- Cabeçalho com informações principais -->
    <div class="detalhes-header mb-4 p-3 bg-light rounded">
        <h4 class="mb-3"><i class="bi bi-calendar-event"></i> Informações do Agendamento</h4>
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2"><strong>Dia da Semana:</strong> 
                    <span id="detalhe-dia-semana">
                        <?php 
                        switch ($horario->dia_semana) {
                            case 'segunda-feira': echo 'Segunda-Feira'; break;
                            case 'terca-feira': echo 'Terça-Feira'; break;
                            case 'quarta-feira': echo 'Quarta-Feira'; break;
                            case 'quinta-feira': echo 'Quinta-Feira'; break;
                            case 'sexta-feira': echo 'Sexta-Feira'; break;
                            case 'sábado': echo 'Sábado'; break;
                            case 'domingo': echo 'Domingo'; break;
                            default: echo ucfirst($horario->dia_semana); 
                        }
                        ?>
                    </span>
                </p>
                <p class="mb-2"><strong>Horário:</strong> 
                    <span id="detalhe-horario">
                        <?= date('H:i', strtotime($horario->horario_inicio)) ?> às <?= date('H:i', strtotime($horario->horario_fim)) ?>
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Status:</strong> 
                    <span id="detalhe-status" class="badge 
                        <?= $horario->status_agendamento === 'ativo' ? 'bg-success' : 
                           ($horario->status_agendamento === 'cancelado' ? 'bg-danger' : 'bg-secondary') ?>">
                        <?= ucfirst($horario->status_agendamento) ?>
                    </span>
                </p>
                <p class="mb-2"><strong>Data do Agendamento:</strong> 
                    <span id="detalhe-data-criacao">
                        <?= date('d/m/Y H:i', strtotime($horario->created_at)) ?>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="detalhes-observacoes mb-4">
        <h5><i class="bi bi-chat-left-text"></i> Observações</h5>
        <div class="card">
            <div class="card-body">
                <?php if (!empty($horario->observacoes)): ?>
                    <p id="detalhe-observacoes" class="mb-0"><?= nl2br(htmlspecialchars($horario->observacoes)) ?></p>
                <?php else: ?>
                    <small class="text-muted" id="detalhe-observacoes-empty">Nenhuma observação registrada</small>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($horario->status_agendamento === 'cancelado'): ?>
    <div class="detalhes-cancelamento mb-4" id="detalhes-cancelamento-section">
        <h5 class="text-danger"><i class="bi bi-x-circle"></i> Motivo do Cancelamento</h5>
        <div class="card border-danger">
            <div class="card-body">
                <p id="detalhe-motivo-cancelamento" class="text-danger mb-0">
                    <?= !empty($horario->motivo_cancelamento) ? nl2br(htmlspecialchars($horario->motivo_cancelamento)) : 'Nenhum motivo especificado' ?>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Histórico de Alterações -->
    <div class="detalhes-historico">
        <h5><i class="bi bi-clock-history"></i> Histórico</h5>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Criado em:</span>
                <span id="detalhe-criado-em" class="badge bg-primary rounded-pill">
                    <?= date('d/m/Y', strtotime($horario->created_at)) ?>
                </span>
            </li>
            
        </ul>
    </div>
</div>

<style>
.agendamento-detalhes {
    font-size: 1rem;
    color: #212529;
}

.agendamento-detalhes h4 {
    color: #0d6efd;
    font-size: 1.4rem;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.agendamento-detalhes h5 {
    font-size: 1.2rem;
    color: #495057;
    margin-bottom: 1rem;
}

.detalhes-header {
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
}

.card {
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 0.75rem 1.25rem;
}

.badge {
    font-size: 0.9em;
    padding: 0.35em 0.65em;
}
</style>