<div class="card-body">
    <h5 class="card-title">Pagamento</h5>
    <form action="<?= SITE ?>/api/registrar/operario" method="POST">
        <input type="hidden" name="operario_id" value="<?= $id ?>">
        <input type="hidden" name="form" value="pagamento">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="text" class="form-control moeda" id="valor" name="valor" placeholder="Digite o valor" value="<?= formatFloatToCurrency($valor_salario) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="salario" class="form-label">Salário ou Diária</label>
                <select class="form-select" id="salario_tipo" name="salario_tipo" required>
                    <option value="Salário" <?= ($operario->tipo_pagamento == "Salário") ? 'selected' : '' ?>>Salário</option>
                    <option value="Diária" <?= ($operario->tipo_pagamento == "Diária") ? 'selected' : '' ?>>Diária</option>
                </select>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6 mb-3">
                <div class="mb-3">
                    <label for="data_registro" class="form-label">Data</label>
                    <input type="date" class="form-control" id="data_registro" name="data_registro" value="<?= $operario->data_admissao ?>" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="mb-3">
                    <label for="hora_registro" class="form-label">Horário</label>
                    <input type="time" class="form-control" id="hora_registro" name="hora_registro" value="<?= date('H:i:s') ?>">
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label for="beneficios" class="form-label">Selecione os Benefícios</label>
                <select class="form-select select2 beneficios" id="beneficios" name="beneficios[]" multiple="multiple">
                    <?php foreach ($beneficios_operario->results as $beneficio): ?>
                        <option value="<?= $this->helpers->encodeURL($beneficio->id) ?>" data-valor="<?= $beneficio->valor ?>" selected>
                            <?= $beneficio->nome_beneficio ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label for="projeto_envolvido" class="form-label">Referente ao projeto</label>

                <select class="form-select" id="projeto_envolvido" name="projeto_id" required>
                    <?php if ($projetos_operario_envolvido->affected_rows > 0): ?>
                        <option value="" selected disabled>Selecione o projeto</option>
                        <?php foreach ($projetos_operario_envolvido->results as $projetos_operario): ?>
                            <option value="<?= $this->helpers->encodeURL($projetos_operario->id) ?>"><?= $projetos_operario->nome_projeto ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" selected disabled>O operário não está envolvido em nenhum projeto</option>
                    <?php endif; ?>
                </select>
            </div>

        </div>

        <?php if ($beneficios_operario->affected_rows > 0): ?>
            <hr class="mt-4 mb-4">
            <h5 class="mt-5">Informações do pagamento com benefícios</h5>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th scope="col">Benefício</th>
                        <th scope="col">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_beneficios = 0;
                    foreach ($beneficios_operario->results as $beneficio):
                        $total_beneficios += $beneficio->valor;
                    ?>
                        <tr>
                            <td><?= $beneficio->nome_beneficio ?></td>
                            <td><?= formatFloatToCurrency($beneficio->valor) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="fw-bold">Valor total com benefícios</td>
                        <td class="fw-bold"><?= formatFloatToCurrency($total_beneficios) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.beneficios').select2();
    });
</script>
</div>