<div class="card-body">
    <h5 class="card-title">Registrar pedido de Benefício</h5>
    <form action="<?= SITE ?>/api/registrar/operario" method="POST">
        <input type="hidden" name="operario_id" value="<?= $id ?>">
        <input type="hidden" name="form" value="beneficio">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="operario_beneficio" class="form-label">Benefício</label>
                <select class="form-select" id="operario_beneficio" name="operario_beneficio" required>
                    <?php if ($beneficios_operario->affected_rows > 0): ?>
                        <option value="" selected disabled>Selecione o benefício</option>
                        <?php foreach ($beneficios_operario->results as $beneficio): ?>
                            <option value="<?= $this->helpers->encodeURL($beneficio->id) ?>"><?= $beneficio->nome_beneficio ?></option>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <option value="" selected disabled>O operador não possuí nenhum benefício</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <div class="mb-3">
                    <label for="data_registro" class="form-label">Data</label>
                    <input type="date" class="form-control" id="data_registro" name="data_registro" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </form>
</div>
</div>