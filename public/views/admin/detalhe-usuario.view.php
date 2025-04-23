<?php include('components/head.php') ?>

<body>

    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>

    <?php include('components/overlay.php'); ?>

    <style>
        .info-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .info-item strong {
            display: inline-block;
            color: #6c757d;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .info-item strong {
                width: 80px;
            }

            .table-responsive {
                width: 100%;
                margin-bottom: 15px;
                overflow-y: hidden;
                -ms-overflow-style: -ms-autohiding-scrollbar;
                border: 1px solid #dee2e6;
            }
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
            <h1>Detalhes do Cliente</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin">Início</a></li>
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin/clientes">Usuários</a></li>
                    <li class="breadcrumb-item active">Detalhes</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">

                <!-- User Details Card -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informações do Cliente</h5>
                            <form method="POST" action="<?= SITE ?>/admin/configuracoes/editar/usuario">
                                <input type="hidden" name="id" value="<?= $client_id ?>">
                                <input type="hidden" name="back_url" value="<?= current_url() ?>">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <strong>Nome:</strong>
                                            <input type="text" class="form-control" name="nome" value="<?= $usuario->nome ?? 'N/A' ?>">
                                        </div>
                                        <div class="info-item">
                                            <strong>Telefone:</strong>
                                            <input type="text" class="form-control celular" name="telefone" value="<?= $usuario->telefone ?? 'N/A' ?>">
                                        </div>
                                        <div class="info-item">
                                        <strong>Nível de acesso:</strong>
                                        <select name="nivel_acesso" class="form-select">
                                               <option value="1" <?= $usuario->nivel_acesso == 1 ? "selected" : ""?>>Usuário</option>
                                               <option value="4" <?= $usuario->nivel_acesso == 4 ? "selected" : ""?>>Administrador</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <strong>CPF:</strong>
                                            <input type="text" class="form-control cpfcnpj" name="cpf" value="<?= isset($usuario->cpf) ? formatarCPF($usuario->cpf) : 'N/A' ?>">
                                        </div>
                                        <div class="info-item">
                                            <strong>Email:</strong>
                                            <input type="text" class="form-control" name="email" value="<?= $usuario->email ?? 'N/A' ?>">
                                        </div>
                                        <div class="info-item">
                                            <strong>Vínculo:</strong>
                                            <select name="vinculo" class="form-select">
                                                <?php foreach ($vinculos as $label => $vinculo): ?>
                                                    <option value="<?= $label ?>" <?php if ($usuario->vinculo == $label) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $label ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="info-item">
                                            <strong>Data de Cadastro:</strong>
                                            <span><?= date('d/m/Y H:i', strtotime($usuario->created_at)) ?? 'N/A' ?></span>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Appointments Table -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Horários Agendados</h5>

                            <div class="table-responsive">
                                <table id="horariosTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Dia da semana</th>
                                            <th>Horário</th>
                                            <th>Status</th>
                                            <th>Data do Agendamento</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($horarios->results)): ?>
                                            <?php foreach ($horarios->results as $horario): ?>
                                                <tr>
                                                    <td><?= $horario->dia_semana ?></td>
                                                    <td><?= date('H:i', strtotime($horario->horario_inicio)) ?> - <?= date('H:i', strtotime($horario->horario_fim)) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?=
                                                                                ($horario->status_agendamento == 'ativo') ? 'primary' : (($horario->status_agendamento == 'concluido') ? 'success' : (($horario->status_agendamento == 'cancelado') ? 'danger' :
                                                                                    'secondary'))
                                                                                ?>">
                                                            <i class="bi <?=
                                                                            ($horario->status_agendamento == 'ativo') ? 'bi-play-circle' : (($horario->status_agendamento == 'concluido') ? 'bi-check-circle' : (($horario->status_agendamento == 'cancelado') ? 'bi-x-circle' :
                                                                                'bi-pause-circle'))
                                                                            ?>"></i>
                                                            <?= ucfirst($horario->status_agendamento) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= date('d/m/Y H:i', strtotime($horario->created_at)) ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-id="<?= $horario->agendamento_id ?>" data-bs-target="#editar-horario">
                                                            <i class="fa fa-pencil"></i> Editar
                                                        </button>
                                                        <a class="btn btn-sm btn-danger" href="<?= SITE ?>/admin/deletar/agendamento/?id=<?= $horario->id ?>&back_url=<?= SITE ?>/admin/usuario/detalhes/<?= $client_id ?>"><i class="fa fa-trash"></i> Deletar</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Nenhum horário agendado</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <div class="modal fade" id="editar-horario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= SITE ?>/admin/agendamentos/editar-agendamento" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Horário</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning border-start border-5 border-warning" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-2">Atenção!</h5>
                                    <p class="mb-1">A edição do usuário não considera as regras de agendamento. Tais como:</p>
                                    <ul class="mb-0 ps-3">
                                        <li>Limite total de agendamentos</li>
                                        <li>Limite de agendamentos por dia da semana</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="editar-horario-form"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include("components/admin-main-js.php") ?>