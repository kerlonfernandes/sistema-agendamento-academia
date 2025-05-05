<?php include('components/head.php') ?>

<body>

    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>

    <?php include('components/overlay.php'); ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin">Início</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">

                <!-- Cards de Resumo -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Agendamentos de Hoje -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Agendamentos <span>| Hoje</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?= $total_agendamentos_hoje ?? '0' ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total de Usuários -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Usuários <span>| Total</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?= $total_usuarios ?? '0' ?></h6>
                                            <span class="text-success small pt-1 fw-bold"><?= $novos_usuarios ?? '0' ?></span>
                                            <span class="text-muted small pt-2 ps-1">novos este mês</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Próximo Horário -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Próximo Horário</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <div class="ps-3">
                                            <span style="font-size: 2.1vh;"><strong><?= $proximo_horario ?? 'Nenhum agendamento' ?></strong></span>
                                            <span class="text-muted small pt-2 ps-1"><?= $proximo_horario_time ?? '' ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agendamentos Ativos -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="card-title">Agendamentos <span>| Ativos</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-list-check"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?= $agendamentos_ativos ?? '0' ?></h6>
                                            <span class="text-muted small pt-2 ps-1">em andamento</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Tabela de Últimos Agendamentos -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Últimos Agendamentos</h5>
                            <div class="table-responsive">

                            <table id="ultimosAgendamentosTable" class="table table-striped table-hover nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Cliente</th>
                                        <th scope="col" class="text-center">Data/Horário</th>
                                        <th scope="col" class="text-center">Data de agendamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($ultimos_agendamentos->status === "success" && $ultimos_agendamentos->affected_rows > 0): ?>
                                        <?php foreach ($ultimos_agendamentos->results as $agendamento): ?>
                                            <tr>
                                                <td class="text-center"><?= htmlspecialchars($agendamento->cliente_nome) ?></td>
                                                <td class="text-center">
                                                    <?= ucfirst($agendamento->dia_semana) ?><br>
                                                    <?= $agendamento->horario_inicio ?> - <?= $agendamento->horario_fim ?>
                                                </td>
                                                <td class="text-center"><?= $agendamento->data_agendamento_formatada ?></td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Nenhum agendamento encontrado</td>
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

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include("components/admin-main-js.php") ?>

</body>

</html>