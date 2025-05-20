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

        .filtro-select {
            width: 30px !important;
            min-width: 100px;
            max-width: 100px;
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
                                    <form action="<?php current_url() ?>" method="get">
                                                <div class="d-flex mb-3">
                                                    <div class="input-group input-group-sm">                                                        
                                                    <select class="form-select filtro-select" id="filtroCliente" name="f">
                                                            <option value="">Filtrar por</option>
                                                            <option value="nome" <?php if(isset($_GET['f']) && $_GET['f'] == "nome"): ?> selected <?php endif; ?>>Nome</option>
                                                            <option value="email" <?php if(isset($_GET['f']) && $_GET['f'] == "email"): ?> selected <?php endif; ?>>Email</option>
                                                            <option value="telefone" <?php if(isset($_GET['f']) && $_GET['f'] == "telefone"): ?> selected <?php endif; ?>>Telefone</option>
                                                        </select>
                                                        <select class="form-select filtro-select" id="tipoVinculo" name="v">
                                                            <option value="" disabled selected>Selecione o vínculo</option>
                                                            <option value="">Todos</option>
                                                            <?php foreach ($vinculos as $vinculo): ?>
                                                                <option value="<?= $vinculo ?>" <?php if(isset($_GET['v']) && $_GET['v'] == $vinculo): ?> selected <?php endif; ?>><?= $vinculo ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <input type="text" class="form-control" aria-label="Text input with dropdown button" name="q" value="<?php if(isset($_GET['q'])): echo $_GET['q']; endif; ?>">
                                                        <button class="btn btn-primary btn-sm" type="submit">Buscar</button>
                                                    </div>
                                                </div>
                                                <a href="<?= SITE ?>/admin/clientes" class="btn btn-secondary btn-sm mb-3"><i class="bi bi-x-circle"></i> Limpar Filtros</a>

                                            </form>
                                    <?php if ($agendamentos->affected_rows <= 0): ?>
                                        <span class="text-center">Nenhum agendamento foi feito ainda <?php if($pelo_gue != ""): ?> por <?= $pelo_gue ?> <?php endif; ?></span>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            
                                            <table id="agendamentosTable" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="d-table-cell d-sm-none">Ações</th> <!-- Ações primeiro em mobile -->
                                                        <th>Nome</th>
                                                        <th>Email</th>
                                                        <th>Telefone</th>
                                                        <th>Vínculo</th>
                                                        <th>Horários Agendados</th>
                                                        <th class="d-none d-sm-table-cell">Ações</th> <!-- Ações normal em telas grandes -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($agendamentos->affected_rows > 0): ?>
                                                        <?php foreach ($agendamentos->results as $agendamento): ?>
                                                            <tr>
                                                                <td class="d-table-cell d-sm-none">
                                                                    <a href="<?= SITE ?>/admin/usuario/detalhes/<?= $agendamento->user_id ?>" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <?= $agendamento->nome ?>
                                                                </td>
                                                                <td><?= $agendamento->email ?></td>
                                                                <td><?= $agendamento->telefone ?></td>
                                                                <td><?= $agendamento->vinculo ?></td>
                                                                <td>
                                                                    <span class="badge bg-primary">
                                                                        <?= $agendamento->total_horarios ?> horário(s)
                                                                    </span>
                                                                </td>
                                                                <td class="d-none d-sm-table-cell">
                                                                    <a href="<?= SITE ?>/admin/usuario/detalhes/<?= $agendamento->user_id ?>" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye"></i> Detalhes
                                                                    </a>
                                                                </td>
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