<?php include('components/head.php') ?>

<body>
    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>
    <?php include('components/overlay.php'); ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Gerenciar Instrutores</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>">Início</a></li>
                    <li class="breadcrumb-item active">Instrutores</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-people me-2"></i>Lista de Instrutores
                            </h5>

                            <!-- Barra de Ferramentas -->
                            <div class="d-flex justify-content-between mb-4">
                                <div class="w-50 me-3">
                                    <div class="input-group">
                                        <input type="text" id="searchInstructor" class="form-control"
                                            placeholder="Pesquisar por nome, email ou telefone..."
                                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                        <button id="btnSearch" class="btn btn-primary" type="button">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabela Responsiva -->
                            <div class="table-responsive">

                                <div id="instructorsTable">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Carregando...</span>
                                    </div>
                                    <p class="mt-2">Carregando lista de instrutores...</p>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-center" id="pagination"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal de Detalhes -->
    <div class="modal fade" id="instructorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-person-badge me-2"></i>
                        <span id="modalTitle">Detalhes do Instrutor</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="instructorDetails">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Fechar
                    </button>
                    <button type="button" id="btnSaveChanges" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <?php include("components/admin-main-js.php") ?>

    <!-- Script Principal -->
   
</body>

</html>