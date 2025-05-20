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
                            <div class="text-center mb-4">
                                <?php if ($_SESSION['accessLevel'] >= 4 || ($_SESSION['accessLevel'] == 2 && $client_id == $_SESSION['userId'])): ?>
                                    <form id="profileImageForm" action="<?= SITE ?>/upload-profile-image" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="user_id" value="<?= $client_id ?>">
                                        <input type="file" id="profileImageInput" name="profile_img" accept="image/*" style="display: none;">

                                        <div class="profile-image-container" style="cursor: pointer; display: inline-block;">
                                            <img id="profileImagePreview"
                                                src="<?= !empty($usuario->profile_img) ? $usuario->profile_img : 'https://ui-avatars.com/api/?name=' . urlencode($usuario->nome) . '&background=random&size=150' ?>"
                                                class="rounded-circle border"
                                                width="150"
                                                height="150"
                                                alt="Foto de perfil"
                                                onclick="document.getElementById('profileImageInput').click()">
                                            <div class="overlay-text">
                                                Para alterar a imagem, clique na imagem.
                                            </div>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div style="display: inline-block;">
                                        <img id="profileImagePreview"
                                            src="<?= !empty($usuario->profile_img) ? $usuario->profile_img : 'https://ui-avatars.com/api/?name=' . urlencode($usuario->nome) . '&background=random&size=150' ?>"
                                            class="rounded-circle border"
                                            width="150"
                                            height="150"
                                            alt="Foto de perfil">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form method="POST" action="<?= SITE ?>/admin/configuracoes/editar/usuario">
                                <input type="hidden" name="id" value="<?= $client_id ?>">
                                <input type="hidden" name="back_url" value="<?= current_url() ?>">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <strong>Nome:</strong>
                                            <?php if ($_SESSION['accessLevel'] >= 4): ?>
                                                <input type="text" class="form-control" name="nome" value="<?= $usuario->nome ?? 'N/A' ?>">
                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?= $usuario->nome ?? 'N/A' ?>" readonly>
                                                <input type="hidden" name="nome" value="<?= $usuario->nome ?? '' ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="info-item">
                                            <strong>Telefone:</strong>
                                            <?php if ($_SESSION['accessLevel'] >= 4): ?>
                                                <input type="text" class="form-control celular" name="telefone" value="<?= $usuario->telefone ?? 'N/A' ?>">
                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?= $usuario->telefone ?? 'N/A' ?>" readonly>
                                                <input type="hidden" name="telefone" value="<?= $usuario->telefone ?? '' ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="info-item">
                                            <strong>Nível de acesso:</strong>
                                            <?php if ($_SESSION['accessLevel'] >= 4): ?>
                                                <select name="nivel_acesso" class="form-select">
                                                    <option value="1" <?= $usuario->nivel_acesso == 1 ? "selected" : "" ?>>Usuário</option>
                                                    <option value="2" <?= $usuario->nivel_acesso == 2 ? "selected" : "" ?>>Instrutor</option>
                                                    <option value="4" <?= $usuario->nivel_acesso == 4 ? "selected" : "" ?>>Administrador</option>
                                                </select>
                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?=
                                                                                                $usuario->nivel_acesso == 1 ? 'Usuário' : ($usuario->nivel_acesso == 2 ? 'Instrutor' : 'Administrador')
                                                                                                ?>" readonly>
                                                <input type="hidden" name="nivel_acesso" value="<?= $usuario->nivel_acesso ?>">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <strong>CPF:</strong>
                                            <?php if ($_SESSION['accessLevel'] >= 4): ?>
                                                <input type="text" class="form-control cpfcnpj" name="cpf" value="<?= isset($usuario->cpf) ? formatarCPF($usuario->cpf) : 'N/A' ?>">
                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?= isset($usuario->cpf) ? formatarCPF($usuario->cpf) : 'N/A' ?>" readonly>
                                                <input type="hidden" name="cpf" value="<?= $usuario->cpf ?? '' ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="info-item">
                                            <strong>Email:</strong>
                                            <?php if ($_SESSION['accessLevel'] >= 4): ?>
                                                <input type="text" class="form-control" name="email" value="<?= $usuario->email ?? 'N/A' ?>">
                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?= $usuario->email ?? 'N/A' ?>" readonly>
                                                <input type="hidden" name="email" value="<?= $usuario->email ?? '' ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="info-item">
                                            <strong>Vínculo:</strong>
                                            <?php if ($_SESSION['accessLevel'] >= 4 || ($_SESSION['accessLevel'] == 2)): ?>
                                                <select name="vinculo" class="form-select">
                                                    <?php foreach ($vinculos as $label => $vinculo): ?>
                                                        <option value="<?= $label ?>" <?= $usuario->vinculo == $label ? 'selected' : '' ?>><?= $label ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?= $usuario->vinculo ?? 'N/A' ?>" readonly>
                                                <input type="hidden" name="vinculo" value="<?= $usuario->vinculo ?? '' ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="info-item">
                                            <strong>Data de Cadastro:</strong>
                                            <span><?= date('d/m/Y H:i', strtotime($usuario->created_at)) ?? 'N/A' ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <div class="col-md-4">
                                            <div class="alert alert-warning">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <strong>Atenção!</strong>
                                                <p class="mb-0">Ao definir uma data de cobrança, o sistema irá criar uma novo registro de cobrança para o usuário nesta data.</p>
                                            </div>
                                                <div class="form-group">
                                                    <label for="data_cobranca" class="form-label">Data de cobrança</label>
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <select class="form-select" id="tipo_cobranca" name="tipo_cobranca">
                                                                <option value="diaria" <?= isset($usuario->tipo_cobranca) && $usuario->tipo_cobranca == 'diaria' ? 'selected' : '' ?>>Diária</option>
                                                                <option value="semanal" <?= isset($usuario->tipo_cobranca) && $usuario->tipo_cobranca == 'semanal' ? 'selected' : '' ?>>Semanal</option>
                                                                <option value="mensal" <?= isset($usuario->tipo_cobranca) && $usuario->tipo_cobranca == 'mensal' ? 'selected' : '' ?>>Mensal</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="date" class="form-control" id="data_cobranca" name="data_cobranca" placeholder="Data de cobrança" value="<?= $usuario->data_cobranca ?? '' ?>">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Ex: Diária (todo dia 5), Semanal (toda segunda), Mensal (todo dia 15)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label d-block">Cobrança Automática</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="cobrancaAutomatica" name="cobranca_automatica" <?= isset($usuario->cobranca_automatica) && $usuario->cobranca_automatica ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="cobrancaAutomatica">Ativar cobrança automática</label>
                                            </div>
                                            <small class="text-muted d-block mt-1">Ao ativar, o sistema irá gerar cobranças automaticamente na data definida</small>
                                        </div>
                                    </div>

                                </div>

                                <?php if ($_SESSION['accessLevel'] >= 4): ?>
                                    <button class="btn btn-primary" type="submit">Salvar</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if ($instrutor != null && $instrutor->affected_rows > 0 && $usuario->nivel_acesso >= 2): ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Responsável por</h5>
                                <div class="table-responsive">
                                    <table id="instrutorTable" class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Dia da semana</th>
                                                <th>Horário</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Ativos</th>
                                                <th class="text-center">Confirmados</th>
                                                <th class="text-center">Cancelados</th>
                                                <th class="text-center">#</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($instrutor->results as $h): ?>
                                                <tr class="hover-row">
                                                    <td onclick="window.location='<?= SITE ?>/admin/acompanhamento/?h=<?= $h->id ?>'"><?= ucfirst($h->dia_semana) ?></td>
                                                    <td onclick="window.location='<?= SITE ?>/admin/acompanhamento/?h=<?= $h->id ?>'"><?= $h->horario_formatado ?></td>
                                                    <td onclick="window.location='<?= SITE ?>/admin/acompanhamento/?h=<?= $h->id ?>'" class="text-center">
                                                        <span class="badge bg-primary"><?= $h->total_agendamentos ?></span>
                                                    </td>
                                                    <td onclick="window.location='<?= SITE ?>/admin/acompanhamento/?h=<?= $h->id ?>'" class="text-center">
                                                        <span class="badge bg-info"><?= $h->agendamentos_ativos ?></span>
                                                    </td>
                                                    <td onclick="window.location='<?= SITE ?>/admin/acompanhamento/?h=<?= $h->id ?>'" class="text-center">
                                                        <span class="badge bg-success"><?= $h->agendamentos_confirmados ?></span>
                                                    </td>
                                                    <td onclick="window.location='<?= SITE ?>/admin/acompanhamento/?h=<?= $h->id ?>'" class="text-center">
                                                        <span class="badge bg-danger"><?= $h->agendamentos_cancelados ?></span>
                                                    </td>
                                                    <td class="text-center"><a href="<?= SITE ?>/admin/instrutor/remover/horario/?id=<?= $h->id ?>&bkurl=<?= SITE ?>/admin/usuario/detalhes/<?= $client_id ?>" class="btn btn-danger">Remover</a></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($usuario->nivel_acesso == 2): ?>
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Nenhum horário atribuído a este instrutor.
                        </div>
                    </div>
                <?php endif; ?>
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
                                                                                ($horario->status_agendamento == 'agendado') ? 'primary' : (($horario->status_agendamento == 'confirmado') ? 'success' : (($horario->status_agendamento == 'cancelado') ? 'danger' :
                                                                                    'secondary'))
                                                                                ?>">
                                                            <i class="bi <?=
                                                                            ($horario->status_agendamento == 'agendado') ? 'bi-play-circle' : (($horario->status_agendamento == 'confirmado') ? 'bi-check-circle' : (($horario->status_agendamento == 'cancelado') ? 'bi-x-circle' :
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
                                                        <?php if (
                                                            isset($_SESSION['userId']) &&
                                                            !empty($_SESSION['userId']) &&
                                                            ($_SESSION['loggedAdmin'] ?? false) === true &&
                                                            ($_SESSION['admin'] ?? false) === true
                                                            && (!empty($_SESSION['accessLevel']) || isset($_SESSION['accessLevel']))
                                                            && $_SESSION['accessLevel'] >= 4
                                                        ): ?>
                                                            <a class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja deletar este agendamento?');" href="<?= SITE ?>/admin/deletar/agendamento/?id=<?= $horario->id ?>&back_url=<?= SITE ?>/admin/usuario/detalhes/<?= $client_id ?>"><i class="fa fa-trash"></i> Deletar</a>
                                                    </td>
                                                <?php endif; ?>
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

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Financeiro</h5>
                            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#registrarPagamentoModal">
                                <i class="bi bi-plus-circle me-1"></i> Novo Registro
                            </button>

                            <form action="<?= SITE ?>/admin/financeiro/salvar-cobranca" method="POST">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="alert alert-warning">
                                            <i class="bi bi-info-circle me-1"></i>
                                            <strong>Atenção!</strong>
                                            <p class="mb-0">Ao definir uma data de cobrança, o sistema irá criar uma novo registro de cobrança para o usuário nesta data.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="data_cobranca" class="form-label">Data de cobrança</label>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <select class="form-select" id="tipo_cobranca" name="tipo_cobranca">
                                                        <option value="diaria" <?= isset($usuario->tipo_cobranca) && $usuario->tipo_cobranca == 'diaria' ? 'selected' : '' ?>>Diária</option>
                                                        <option value="semanal" <?= isset($usuario->tipo_cobranca) && $usuario->tipo_cobranca == 'semanal' ? 'selected' : '' ?>>Semanal</option>
                                                        <option value="mensal" <?= isset($usuario->tipo_cobranca) && $usuario->tipo_cobranca == 'mensal' ? 'selected' : '' ?>>Mensal</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="date" class="form-control" id="data_cobranca" name="data_cobranca" placeholder="Data de cobrança" value="<?= $usuario->data_cobranca ?? '' ?>">
                                                </div>
                                            </div>
                                            <small class="text-muted">Ex: Diária (todo dia 5), Semanal (toda segunda), Mensal (todo dia 15)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label d-block">Cobrança Automática</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="cobrancaAutomatica" name="cobranca_automatica" <?= isset($usuario->cobranca_automatica) && $usuario->cobranca_automatica ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="cobrancaAutomatica">Ativar cobrança automática</label>
                                            </div>
                                            <small class="text-muted d-block mt-1">Ao ativar, o sistema irá gerar cobranças automaticamente na data definida</small>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-save me-1"></i> Salvar Configurações
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h6 class="card-title text-white">Total Pago</h6>
                                            <h3 class="card-text">R$ <span id="totalPago"><?= $financeiro->total ? formatarMoeda($financeiro->total) : '0,00' ?></span></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">Pendências</h6>
                                            <h3 class="card-text">R$ <span id="totalPendente"><?= $financeiro->total_pendente ? formatarMoeda($financeiro->total_pendente) : '0,00' ?></span></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body">
                                            <h6 class="card-title text-white">Cancelados</h6>
                                            <h3 class="card-text">R$ <span id="totalCancelados"><?= $financeiro->total_cancelados ? formatarMoeda($financeiro->total_cancelados) : '0,00' ?></span></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabs de Navegação -->
                            <ul class="nav nav-tabs" id="financeiroTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="historico-tab" data-bs-toggle="tab" data-bs-target="#historico" type="button" role="tab" aria-controls="historico" aria-selected="true">
                                        <i class="bi bi-clock-history me-1"></i> Histórico de Pagamentos
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-3" id="financeiroTabsContent">
                                <div class="tab-pane fade show active" id="historico" role="tabpanel" aria-labelledby="historico-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="historicoTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Data pagamento</th>
                                                    <th class="text-center">Data vencimento</th>
                                                    <th class="text-center">Valor</th>
                                                    <th class="text-center">Forma de Pagamento</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Observações</th>
                                                    <th class="text-center">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($financeiro->historico as $pagamento): ?>
                                                    <tr>
                                                        <td class="text-center"><?= ($pagamento->data_pagamento && $pagamento->data_pagamento != '0000-00-00') ? date('d/m/Y', strtotime($pagamento->data_pagamento)) : "Indefinido" ?></td>
                                                        <td class="text-center"><?= ($pagamento->data_vencimento && $pagamento->data_vencimento != '0000-00-00') ? date('d/m/Y', strtotime($pagamento->data_vencimento)) : "Indefinido" ?></td>
                                                        <td class="text-center"><?= formatarMoeda($pagamento->valor) ?></td>
                                                        <td class="text-center"><?= $pagamento->forma_pagamento ?></td>
                                                        <td class="text-center"><?= $pagamento->status == "pendente" ? "<span class='badge bg-warning'>Pendente</span>" : ($pagamento->status == "pago" ? "<span class='badge bg-success'>Pago</span>" : "<span class='badge bg-danger'>Cancelado</span>") ?></td>
                                                        <td class="text-center" style="max-width: 100px;"><?= !empty($pagamento->observacoes) ? (strlen($pagamento->observacoes) > 100 ? substr($pagamento->observacoes, 0, 100) . '...' : $pagamento->observacoes) : '-' ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarPagamentoModal" data-id="<?= $pagamento->id ?>" data-back_url="<?= current_url() ?>#financeiroTabsContent" data-user_id="<?= $client_id ?>">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <a class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja deletar este pagamento?');" href="<?= SITE ?>/admin/financeiro/deletar-pagamento/?id=<?= $pagamento->id ?>&back_url=<?= SITE ?>/admin/usuario/detalhes/<?= $client_id ?>&user_id=<?= $client_id ?>">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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

    <!-- Modal de Registro de Pagamento -->
    <?php include("components/modal-relatorio-financeiro.php") ?>
    <?php include("components/modal-editar-financeiro.php") ?>


    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include("components/admin-main-js.php") ?>

    <script>
        const url = $("#url").val();

        document.getElementById('profileImageInput').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('profileImagePreview').src = e.target.result;

                    var preview = document.getElementById('profileImagePreview');
                    preview.style.opacity = '0.7';

                    var form = document.getElementById('profileImageForm');
                    var formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.new_url) {
                                    preview.src = data.new_url;
                                }
                                // Mostrar mensagem de sucesso
                                showAlert('success', 'Foto atualizada com sucesso!');
                            } else {
                                showAlert('danger', data.message || 'Erro ao atualizar foto');
                            }
                            preview.style.opacity = '1';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('danger', 'Erro ao processar a requisição');
                            preview.style.opacity = '1';
                        });
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.style.position = 'fixed';
            alertDiv.style.top = '20px';
            alertDiv.style.right = '20px';
            alertDiv.style.zIndex = '1100';
            alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

            document.body.appendChild(alertDiv);

            // Remover após 5 segundos
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Função para formatar valores monetários
        function formatarMoeda(valor) {
            return parseFloat(valor).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Carregar formulário de edição de pagamento
        $(document).on("show.bs.modal", "#editarPagamentoModal", function(e) {
            const button = $(e.relatedTarget);
            const id = button.data('id');
            const back_url = button.data('back_url');
            const user_id = button.data('user_id');

            $.ajax({
                url: url + "/reload/editar-registro-pagamento.php",
                type: "GET",
                data: {
                    id: id,
                    back_url: back_url,
                    user_id: user_id
                },
                beforeSend: function() {
                    $(".editar-pagamento-form").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div></div>');
                },
                success: function(response) {
                    $(".editar-pagamento-form").html(response);
                }
            });
        });

        // Formatar campos monetários
        $('.moeda').on('input', function() {
            let value = $(this).val();
            value = value.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            $(this).val(value);
        });
    </script>

    <style>
        .profile-image-container {
            position: relative;
            display: inline-block;
        }

        .overlay-text {
            margin-top: 5px;
            padding: 4px;
        }

        .profile-image-container:hover .overlay-text {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border-radius: 10px;
        }

        .profile-image-container:hover img {
            opacity: 0.9;
        }

        /* Estilos para a seção financeira */
        .nav-tabs .nav-link {
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 500;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.75em;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>