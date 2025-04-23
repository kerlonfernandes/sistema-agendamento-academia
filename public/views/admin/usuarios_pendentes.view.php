<?php include('components/head.php') ?>

<body>

    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>

    <?php include('components/overlay.php'); ?>


    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Usuários Pendentes</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin/usuarios">../</a></li>
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin/pending-users">pendentes</a></li>
                </ol>
       
            </nav>
        </div>

        <section class="section dashboard mt-5">
            <div class="row">
                <div class="d-flex justify-content-center op-loads">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="col operarios-table d-none">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">Ações</th>
                                    <th class="text-center"><b>N</b>ome</th>
                                    <th class="text-center">Email</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <div class="dropdown w-25 mx-auto">
                                            <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Acessar</a></li>
                                                <li><a class="dropdown-item del-opr" data-id="#">Deletar</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-center">Nome da categoria</td>
                                    <td class="text-center">Email</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include("components/main-js.php") ?>

</body>

</html>