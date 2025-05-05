<?php include('components/head.php') ?>

<body>

    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>

    <?php include('components/overlay.php'); ?>

    <style>
        .config-section {
            margin-bottom: 40px;
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .config-section h5 {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #012970;
        }

        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
            border: 1px solid #ddd;
        }

        .days-checkbox {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .days-checkbox .form-check {
            min-width: 120px;
        }

        .horarios-table th {
            white-space: nowrap;
        }
    </style>

    <main id="main" class="main">
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100; width: 350px;">
            <?php if (isset($_SESSION['alert_message'])): ?>
                <?php
                $alert = $_SESSION['alert_message'];
                unset($_SESSION['alert_message']);
                ?>
                <div class="alert alert-<?= $alert['type'] ?> <?= $alert['dismissible'] ? 'alert-dismissible fade show' : '' ?>" role="alert">
                    <?php if (!empty($alert['title'])): ?>
                        <strong><?= $alert['title'] ?></strong>
                    <?php endif; ?>
                    <?= $alert['message'] ?>
                    <?php if ($alert['dismissible']): ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="pagetitle">
            <h1>Configurações do Sistema</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin">Início</a></li>
                    <li class="breadcrumb-item active">Configurações</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Seção Sistema -->
                    <div class="config-section">
                        <h5><i class="bi bi-gear-fill me-2"></i> Configurações do Sistema</h5>
                        <form action="<?= SITE ?>/admin/configuracoes/salvar" method="post" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="imagem_formulario" class="form-label">Imagem do Formulário</label>
                                    <input type="file" class="form-control" id="imagem_formulario" name="imagem_formulario">
                                    <div class="mt-2">
                                        <img src="<?= IMAGES . "/" . $imagem_formulario ?>" class="w-100" alt="Imagem atual" style="max-height: 100px;" id="imagemPreview">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Formulário Ativo</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="formulario_ativo" name="formulario_ativo" <?= $formulario_ativo == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="formulario_ativo">Ativar formulário de agendamento</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="limite_agendamentos" class="form-label">Limite de Agendamentos por Dia</label>
                                    <input type="number" class="form-control" id="limite_agendamentos" name="limite_agendamentos" value="<?= $limite_agendamentos ?>" min="1">
                                    <div class="aviso"></div>

                                    <div class="alert alert-warning d-flex align-items-center mt-3" role="alert">
                                        <i class="bi bi-alert"></i>
                                        <div>
                                        <i class="bi bi-exclamation-circle-fill"></i> O sistema irá marcar automaticamente como concluídos todos os horários que tiverem mais de <strong><?= $metade ?></strong> de agendamentos.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="cor_primaria" class="form-label">Cor Primária</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="cor_primaria" name="cor_primaria" value="<?= $cor_primaria ?>">

                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center spinner-text-loading">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="texto-aviso-area d-none">
                                        <label for="texto_aviso" class="form-label">Texto de Aviso</label>
                                        <textarea id="texto_aviso" name="texto_aviso" style="height: 100px;"><?= $aviso_formulario ?></textarea>
                                    </div>
                                </div>
                            </div>


                            <button class="btn btn-sm btn-outline-primary float-end mt-5" style="width: 150px;" id="btn_aviso">Salvar alterações</button>
                        </form>


                        <div class="row mt-5">

                            <div class="col-md-6 mt-5">
                                <h5><i class="bi bi-link-45deg me-2" id="vinculos_area"></i> Vinculos</h5>
                                <label class="form-label">Vínculos</label>
                                <form action="<?= SITE ?>/admin/configuracoes/vinculo_formulario" method="post">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="novo_vinculo" name="novo_vinculo" placeholder="Adicionar novo vínculo" required>
                                        <button class="btn btn-primary" type="submit" id="addVinculo">Adicionar</button>
                                    </div>
                                </form>

                                <button class="btn btn-sm btn-outline-secondary mb-5" type="button" data-bs-toggle="collapse" data-bs-target="#vinculos"> <span class="badge bg-secondary"><?php $i = 0;
                                                                                                                                                                                            foreach ($vinculo_formulario as $label) {
                                                                                                                                                                                                $i++;
                                                                                                                                                                                            } ?> <?= $i ?></span> Visualizar os vinculos criados</button>
                                <div class="collapse <?= !empty($alert['open_colapse']) ? 'show' : '' ?>" id="vinculos">
                                    <ul class="list-group">
                                        <?php foreach ($vinculo_formulario as $label => $vinculo) { ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?= $label ?>
                                                <a href="<?= SITE ?>/admin/configuracoes/remover_vinculo/?vinculo=<?= $vinculo ?>"
                                                    class="btn btn-danger btn-sm" type="button" id="removeVinculo"
                                                    data-vinculo="<?= $vinculo ?>">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Seção Horários -->
                    <div class="config-section">
                        <h5><i class="bi bi-clock-fill me-2"></i> Configurações de Horários</h5>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label mb-3">Dias de Funcionamento</label>
                                <div class="days-checkbox">
                                    <?php foreach ($dias_funcionamento as $label => $dia) { ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="<?= $dia ?>-<?= $label ?>" name="dias_funcionamento[]" value="<?= $dia ?>" <?= $dia == 1 ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="<?= $dia ?>-<?= $label ?>"><?= $label ?></label>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6>Horários de Atendimento</h6>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addHorarioModal">
                                        <i class="bi bi-plus"></i> Adicionar Horário
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover horarios-table">
                                        <thead>
                                            <tr>
                                                <th>Dia da Semana</th>
                                                <th>Horário Início</th>
                                                <th>Horário Fim</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="configuracoes_horarios">
                                            <?php foreach ($horarios_funcionamento as $horario) { ?>
                                                <tr>
                                                    <td><?= $horario->dia_semana ?></td>
                                                    <td><?= $horario->horario_inicio ?></td>
                                                    <td><?= $horario->horario_fim ?></td>
                                                    <td>
                                                        <a href="<?= SITE ?>/admin/configuracoes/deletar-horario?id=<?= $horario->id ?>" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal Adicionar Horário -->
        <div class="modal fade" id="addHorarioModal" tabindex="-1" aria-labelledby="addHorarioModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addHorarioModalLabel">Adicionar Novo Horário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?= SITE ?>/admin/configuracoes/adicionar-horario">
                            <div class="mb-3">
                                <label for="dia_semana" class="form-label">Dia da Semana</label>
                                <select class="form-select" id="dia_semana" name="dia_semana" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($dias_funcionamento as $label => $dia) { ?>
                                        <?php if ($dia != 0): ?>
                                            <option value="<?= $label ?>" <?= $label == 1 ? 'selected' : '' ?>>
                                                <?= ucfirst($label) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php } ?>
                                </select>

                            </div>
                            <div class="mb-3">
                                <label for="horario_inicio" class="form-label">Horário Início</label>
                                <input type="text" class="form-control time" id="horario_inicio" name="horario_inicio" placeholder="00:00:00" required>
                            </div>
                            <div class="mb-3">
                                <label for="horario_fim" class="form-label">Horário Fim</label>
                                <input type="text" class="form-control time" id="horario_fim" name="horario_fim" placeholder="00:00:00" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Horário</button>
                    </div>
                </div>
                </form>

            </div>
        </div>

    </main>

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include("components/admin-main-js.php") ?>

</body>

</html>