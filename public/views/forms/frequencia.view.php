<div class="card-body">
    <h5 class="card-title">Frequência</h5>
    <form action="<?= SITE ?>/api/registrar/operario" method="POST">
        <input type="hidden" name="operario_id" value="<?= $id ?>">
        <input type="hidden" name="form" value="frequencia">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="operario_pesenca" class="form-label">Registro de</label>
                <select class="form-select operario_pesenca" id="operario_pesenca" name="operario_pesenca">
                    <option value="presenca" selected>Presença</option>
                    <option value="falta">Falta</option>
                    <option value="falta_justificada">Falta Justificada</option>
                    <option value="atestado">Atestado</option>
                    <option value="ferias">Férias</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <div class="mb-3">
                    <label for="data_registro" class="form-label">Data</label>
                    <input type="date" class="form-control" id="data_registro" name="data_registro" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="mb-3">
                    <label for="hora_registro" class="form-label">Horário</label>
                    <input type="time" class="form-control" id="hora_registro" name="hora_registro" value="<?= date('H:i:s') ?>">
                </div>
            </div>


            <div class="col-md-6 mb-3">
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
        <div class="row">
            <div class="col-md-6 mb-3">
            </div>
            <div class="col-md-6 mb-3">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea class="form-control" id="motivo" name="motivo" placeholder="Digite o motivo"></textarea>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </form>
</div>
