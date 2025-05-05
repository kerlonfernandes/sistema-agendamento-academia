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
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin">Inicio</a></li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard mt-5">
            <div class="row">

                <div class="col">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">Ações</th>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">Telefone</th>
                                    <th class="text-center">CPF</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Vínculo</th>
                                    <th class="text-center">Nível acesso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($usuarios->affected_rows > 0): ?>
                                    <?php foreach ($usuarios->results as $usuario): ?>
                                        <tr>
                                            <td class="text-center">
                                                <div class="dropdown mx-auto">
                                                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Ações
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="<?= SITE ?>/admin/usuario/detalhes/<?= $usuario->id ?>">Acessar</a></li>
                                                        <?php if (
                                                            isset($_SESSION['userId']) &&
                                                            !empty($_SESSION['userId']) &&
                                                            ($_SESSION['loggedAdmin'] ?? false) === true &&
                                                            ($_SESSION['admin'] ?? false) === true
                                                            && (!empty($_SESSION['accessLevel']) || isset($_SESSION['accessLevel']))
                                                            && $_SESSION['accessLevel'] >= 4
                                                        ): ?>
                                                            <li><a class="dropdown-item del-usr" data-name="<?= $usuario->nome ?>" data-id="<?= $usuario->id ?>" href="#">Deletar</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td><strong><?= !empty($usuario->nome) ? htmlspecialchars(substr($usuario->nome, 0, 30)) . (strlen($usuario->nome) > 30 ? '...' : '') : '' ?><?php if ($usuario->id == $_SESSION['userId']) echo "(Você)" ?></strong></td>
                                            <td><?= !empty($usuario->telefone) ? htmlspecialchars($usuario->telefone) : 'Não informado' ?></td>
                                            <td><?= !empty($usuario->cpf) ? htmlspecialchars($usuario->cpf) : "Não informado" ?></td>
                                            <td><?= !empty($usuario->email) ? htmlspecialchars($usuario->email) : 'Não informado' ?></td>
                                            <td><?= !empty($usuario->vinculo) ? htmlspecialchars($usuario->vinculo) : 'Não informado' ?></td>
                                            <td>
                                                <?php
                                                if (isset($usuario->nivel_acesso)) {
                                                    switch ($usuario->nivel_acesso) {
                                                        case 4:
                                                            echo "Administrador";
                                                            break;
                                                        case 2:
                                                            echo "Instrutor";
                                                            break;
                                                        case 5:
                                                            echo "Super Administrador";
                                                            break;
                                                        default:
                                                            echo "Usuário Normal";
                                                    }
                                                } else {
                                                    echo "Não informado";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
    <?php include("components/admin-main-js.php") ?>

</body>

</html>