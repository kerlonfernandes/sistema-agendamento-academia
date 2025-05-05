<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= SITE ?>">
            <!-- Adicione sua logo aqui se desejar -->
        </a>
        <?php
        if (
            isset($_SESSION['userId']) &&
            !empty($_SESSION['userId']) &&
            ($_SESSION['loggedAdmin'] ?? false) === true &&
            ($_SESSION['admin'] ?? false) === true 
            && (!empty($_SESSION['accessLevel']) || isset($_SESSION['accessLevel']))
            && $_SESSION['accessLevel'] == 4
        ): ?>
            <a href="<?= SITE ?>/admin">Painel Administrativo</a>
        <?php endif; ?>
        
        <?php if (
            isset($_SESSION['userId']) &&
            !empty($_SESSION['userId']) &&
            (!empty($_SESSION['accessLevel']) || isset($_SESSION['accessLevel'])) &&
            $_SESSION['accessLevel'] == 2
        ): ?>
            <a href="<?= SITE ?>/admin">Painel Instrutor</a>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['loggedUser']) && $_SESSION['loggedUser'] != false): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>
                            <span class="d-none d-sm-inline"><?= htmlspecialchars($_SESSION['nome']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= SITE ?>/"><i class="bi bi-newspaper"></i>Agendar</a></li>
                            <!-- <li><a class="dropdown-item" href="<?= SITE ?>/perfil"><i class="bi bi-person me-2"></i>Meu Perfil</a></li> -->
                            <li><a class="dropdown-item" href="<?= SITE ?>/vizualizar-horarios"><i class="bi bi-list-check me-2"></i>Meus Agendamentos</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?= SITE ?>/logout"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= SITE ?>/login" class="btn btn-outline-primary border-2 fw-bold">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Entrar
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>