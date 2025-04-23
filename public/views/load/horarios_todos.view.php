<?php
$horarios = $horarios ?? [];
$horario_selecionado = $horario_selecionado ?? null;

// Organizamos os horários por dia e hora para fácil acesso
$horarios_organizados = [];
foreach ($horarios as $horario) {
    $dia = $horario->dia_semana;
    $hora = $horario->horario;
    $horarios_organizados[$hora][$dia] = $horario;
}

$todos_horarios = array_unique(array_map(function ($h) {
    return $h->horario;
}, $horarios));
sort($todos_horarios);

$diasSemana = [
    'segunda-feira',
    'terca-feira',
    'quarta-feira',
    'quinta-feira',
    'sexta-feira'
];
?>
<div class="table-responsive mb-4" style="position: relative; overflow: auto; max-height: 80vh;">
    <table class="table">
        <thead class="table-light" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
            <tr>
                <th class="text-center align-middle horario-header" style="min-width: 15vh; position: sticky; left: 0; z-index: 11; background-color: #f8f9fa;">Horário</th>
                <?php foreach ($diasSemana as $dia): ?>
                    <th class="text-center align-middle" style="min-width: 200px;">
                        <?= ucfirst($dia) ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($todos_horarios as $horario): ?>
                <tr>
                    <td class="text-center align-middle fw-bold" style="position: sticky; left: 0; z-index: 9; background-color: white;">
                        <?= $horario ?>
                    </td>
                    <?php foreach ($diasSemana as $dia): ?>
                        <?php
                        $agendamentoAtual = $horarios_organizados[$horario][$dia] ?? null;
                        $total = isset($agendamentoAtual->total_agendamentos) ?
                            $agendamentoAtual->total_agendamentos : 0;

                        $class = 'alert-success';
                        $disabled = '';
                        $status_text = 'Selecionar';

                        if ($total >= $limite_agendamentos) {
                            $class = 'alert-danger';
                            $disabled = 'disabled';
                            $status_text = 'Lotado';
                        } elseif ($total >= $metade) {
                            $class = 'alert-warning';
                        }

                        $id_input = 'horario_' . str_replace([':', ' ', '-'], '_', $horario . '_' . $dia);
                        ?>
                        <td class="p-2">
                            <div class="card <?= $class ?> mb-0 h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input"
                                                type="radio"
                                                name="horario_<?= str_replace('-feira', '', $dia) ?>"
                                                id="<?= $id_input ?>"
                                                value="<?= $horario ?>"
                                                data-dia="<?= $dia ?>"
                                                data-horario="<?= $horario ?>"
                                                <?= $disabled ?>
                                                <?= ($horario_selecionado === $horario) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="<?= $id_input ?>">
                                                <?= $status_text ?>
                                            </label>
                                        </div>
                                        <span class="badge bg-secondary">
                                            <?= $total ?>/<?= $limite_agendamentos ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>