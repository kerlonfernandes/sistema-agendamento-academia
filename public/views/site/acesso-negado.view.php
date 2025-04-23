<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link href="<?= VENDOR ?>/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom styles for this template-->
    <link href="<?= CSS ?>/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= CSS ?>/css.css" rel="stylesheet">

    <script src=" https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js">
    </script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include(__DIR__ . '/../components/side-bar.php') ?>

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include(__DIR__ . '/../components/top-bar.php') ?>

                <div class="container-fluid">
                    <div class="text-center">
                        <div class="error mx-auto" data-text="403">403</div>
                        <p class="lead text-gray-800 mb-5">Permissão negada</p>
                        <p class="text-gray-500 mb-0">Parece que você não tem permissão para acessar esta área.</p>
                        <a href="<?= SITE ?>">&larr; Voltar pro início.</a>
                    </div>
                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include(__DIR__ . '/../components/footer.php') ?>

                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <?php include(__DIR__ . '/../components/logout-modal.php') ?>

        <?php include __DIR__ . "/../components/modais/relatorio.php"; ?>

        <!-- Bootstrap core JavaScript-->
        <script src="<?= VENDOR ?>/jquery/jquery.min.js"></script>
        <script src="<?= VENDOR ?>/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?= VENDOR ?>/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?= JS ?>/sb-admin-2.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
        <!-- <script src="<?= VENDOR ?>/jquery/jquery.min.js"></script> Load jQuery first -->
        <script src="<?= ASSETS ?>/bootstrap.bundle.js?id=<?= uniqid() ?>"></script>
        <script src="<?= ASSETS ?>/jquery.mask.js?id=<?= uniqid() ?>"></script>
        <script src="<?= JS ?>/styles.js?id=<?= uniqid() ?>"></script>
        <script src="<?= JS ?>/clients.js?id=<?= uniqid() ?>"></script>
        <script src="<?= JS ?>/js.js?id=<?= uniqid() ?>"></script>
        <script src="<?= JS ?>/scripts.js?id=<?= uniqid() ?>"></script>
        <script src="<?= JS ?>/masks.js?id=<?= uniqid() ?>"></script>
        <script src="<?= JS ?>/index.js?id=<?= uniqid() ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js" integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>