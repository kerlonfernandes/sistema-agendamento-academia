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

            $total_agendamentos = $horario->total_agendamentos ?? 0;
            $horario_valor = $horario->horario ?? '';
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
                <label for="<?= $id_input ?>" class="card-label">
                    <div class="card <?= $class ?> h-100 position-relative">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 fw-bold">
                                    <?= $horario_valor ?>
                                </h5>
                                <span class="badge bg-secondary">
                                    <?= $total_agendamentos ?>/10
                                </span>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input"
                                    type="radio"
                                    name="horario"
                                    id="<?= $id_input ?>"
                                    value="<?= $horario_valor ?>"
                                    <?= $disabled ? 'disabled' : '' ?>
                                    <?= ($horario_selecionado === $horario_valor) ? 'checked' : '' ?>>
                                <span class="form-check-label">
                                    Selecionar horário
                                </span>
                            </div>

                            <?php if (!empty($horario->nomes_usuarios)): ?>
                                <!-- <div class="small text-muted mt-2">
                                    <i class="bi bi-people"></i> <?= $horario->nomes_usuarios ?>
                                </div> -->
                            <?php endif; ?>
                        </div>
                    </div>
                </label>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
