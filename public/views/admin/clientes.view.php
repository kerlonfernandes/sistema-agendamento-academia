<?php include('components/head.php') ?>

<body>

    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>

    <?php include('components/overlay.php'); ?>

    <style>
    .search-container input {
        width: 300px !important;
        margin-left: auto;
        display: block;
    }
    
    .dt-actions {
        white-space: nowrap;
        text-align: center !important;
    }
    
    .dt-actions .btn {
        margin: 2px;
    }
    
    @media (max-width: 768px) {
        .search-container input {
            width: 100% !important;
        }
        
        .dt-actions {
            width: auto !important;
        }
    }
    
</style>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Clientes com horário agendados</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin">Inicio</a></li>
                </ol>
            </nav>
        </div>
        <section class="section dashboard">
            <div class="row">

                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Lista de Clientes</h5>
                                    <?php if ($agendamentos->affected_rows <= 0): ?>
                                            <span class="text-center">Nenhum agendamento foi feito ainda</span>
                                    <?php else: ?>
                                    <table id="agendamentosTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Telefone</th>
                                                <th>Horários Agendados</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($agendamentos->affected_rows > 0): ?>
                                                <?php foreach ($agendamentos->results as $agendamento): ?>
                                                    <tr>
                                                        <td><?= $agendamento->user_id ?></td>
                                                        <td><?= $agendamento->nome ?></td>
                                                        <td><?= $agendamento->email ?></td>
                                                        <td><?= $agendamento->telefone ?></td>
                                                        <td>
                                                            <span class="badge bg-primary">
                                                                <?= $agendamento->total_horarios ?> horário(s)
                                                            </span>
                                                        </td>
                                                        <td>
                                                            
                                                            <a href="<?= SITE ?>/admin/usuario/detalhes/<?= $agendamento->user_id ?>" class="btn btn-sm btn-primary">
                                                                <i class="bi bi-eye"></i> Detalhes
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Nenhum agendamento encontrado</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <?php endif; ?>

                                </div>
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

    <!-- DataTables CSS -->


    <!-- Script personalizado -->
    <script src="<?= SITE ?>/src/js/admin/agendamentos.js"></script>

</body>

</html>