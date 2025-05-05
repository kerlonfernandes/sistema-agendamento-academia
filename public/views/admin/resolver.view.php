<?php include('components/head.php') ?>

<body>
    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>
    <?php include('components/overlay.php'); ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Resolver Agendamentos</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>">Início</a></li>
                    <li class="breadcrumb-item active">Resolver Agendamentos</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Selecione um Horário</h5>

                            <!-- Select de Horários -->
                            <!-- Select de Horários -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <select name="dia" id="diaSelect" class="form-select" style="width: 100%;">
                                        <option value="">Selecione um dia</option>
                                        <?php if ($horarios != null && $horarios->affected_rows > 0): ?>
                                            <?php
                                            $dias_mostrados = [];
                                            foreach ($horarios->results as $horario):
                                                if (!in_array($horario->dia_semana, $dias_mostrados)) {
                                                    $dias_mostrados[] = $horario->dia_semana;
                                            ?>
                                                    <option value="<?= $horario->dia_semana ?>"><?= $horario->dia_semana ?></option>
                                            <?php
                                                }
                                            endforeach;
                                            ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <select name="horario" id="horarioSelect" class="form-select" style="width: 100%;" disabled>
                                        <option value="">Selecione um dia primeiro</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Área de Resultados -->
                    <div id="resultados-agendamentos" class="mt-4">
                        <div class="text-center text-muted">
                            <i class="bi bi-calendar-event fs-1"></i>
                            <p>Selecione um horário para visualizar os agendamentos</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            </div>
        </section>
    </main>

    <!-- Modal para Resolver Agendamento -->
    <div class="modal fade" id="resolverAgendamentoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resolver Agendamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-agendamento-content">
                    <!-- Conteúdo carregado via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btn-salvar-status">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include("components/admin-main-js.php") ?>

    <script>
          
    </script>
</body>

</html>