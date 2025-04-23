<?php
$horarios = $horarios ?? [];
$horario_selecionado = $horario_selecionado ?? null;
?>

<div class="row">
    <?php if (empty($horarios)): ?>
        <div class="col-12 text-center">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Nenhum horário disponível para este dia.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($horarios as $horario): ?>
            <?php 
            if (!is_object($horario)) {
                continue;
            }
            
            $total_agendamentos = isset($horario->total_agendamentos) ? $horario->total_agendamentos : 0;
            $horario_valor = isset($horario->horario) ? $horario->horario : '';
            $class = 'alert-success';
            $disabled = false;
            
            if ($total_agendamentos >= 10) {
                $class = 'alert-danger';
                $disabled = true;
            } elseif ($total_agendamentos >= 5) {
                $class = 'alert-warning';
            }
            
            $id_input = 'horario_' . str_replace([':', ' '], '_', $horario_valor);
            ?>
            
            <div class="col-md-4 mb-3">
                <div class="card <?php echo $class; ?> h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0 fw-bold">
                                <?php echo $horario_valor; ?>
                            </h5>
                            <span class="badge bg-secondary">
                                <?php echo $total_agendamentos; ?>/10
                            </span>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" 
                                   type="radio" 
                                   name="horario" 
                                   id="<?php echo $id_input; ?>" 
                                   value="<?php echo $horario_valor; ?>"
                                   <?php echo $disabled ? 'disabled' : ''; ?>
                                   <?php echo ($horario_selecionado === $horario_valor) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="<?php echo $id_input; ?>">
                                Selecionar horário
                            </label>
                        </div>
                        
                        <?php if (!empty($horario->nomes_usuarios)): ?>
                            <div class="small text-muted mt-2">
                                <i class="bi bi-people"></i> <?php echo $horario->nomes_usuarios; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="mt-3">
    <div class="d-flex gap-2 flex-wrap">
        <div class="alert alert-success py-1 px-2 mb-0">
            <small><i class="bi bi-circle-fill me-1"></i> Menos de 5 agendamentos</small>
        </div>
        <div class="alert alert-warning py-1 px-2 mb-0">
            <small><i class="bi bi-circle-fill me-1"></i> 5 a 9 agendamentos</small>
        </div>
        <div class="alert alert-danger py-1 px-2 mb-0">
            <small><i class="bi bi-circle-fill me-1"></i> 10 ou mais (lotado)</small>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
    border-radius: 8px;
}

.card:hover:not(.alert-danger) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
    opacity: 0.8;
}

.form-check-input:disabled + .form-check-label {
    opacity: 0.6;
}

.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

.card-title {
    font-size: 1.1rem;
}

.bi {
    font-size: 0.9em;
}
</style> 