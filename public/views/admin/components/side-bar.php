<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
    <?php
        if (
            isset($_SESSION['userId']) &&
            !empty($_SESSION['userId']) &&
            ($_SESSION['loggedAdmin'] ?? false) === true &&
            ($_SESSION['admin'] ?? false) === true 
            && (!empty($_SESSION['accessLevel']) || isset($_SESSION['accessLevel']))
            && $_SESSION['accessLevel'] == 4
        ): ?>
        <li class="nav-item">
            <a class="nav-link " href="<?= SITE ?>">
                <i class="bi bi-newspaper"></i>
                <span>Ir para o formulário</span>
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link " href="<?= SITE ?>/admin">
                <i class="bi bi-grid"></i>
                <span>Inicio</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#agendamentos-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-calendar-check"></i><span>Agendamentos</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="agendamentos-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= SITE ?>/admin/acompanhamento">
                        <i class="bi bi-circle"></i><span>Acompanhamento</span>
                    </a>
                </li>
                <?php if (
                    isset($_SESSION['userId']) &&
                    !empty($_SESSION['userId']) &&
                    ($_SESSION['loggedAdmin'] ?? false) === true &&
                    ($_SESSION['admin'] ?? false) === true
                    && (!empty($_SESSION['accessLevel']) || isset($_SESSION['accessLevel']))
                    && $_SESSION['accessLevel'] >= 4
                ): ?>
                    <li>
                        <a href="<?= SITE ?>/admin/configuracoes">
                            <i class="bi bi-circle"></i><span>Configurações</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (
                    isset($_SESSION['userId']) &&
                    !empty($_SESSION['userId']) &&
                    ($_SESSION['loggedAdmin'] ?? false) === true &&
                    ($_SESSION['admin'] ?? false) === true
                    && (!empty($_SESSION['accessLevel']) || isset($_SESSION['accessLevel']))
                    && $_SESSION['accessLevel'] >= 4
                ): ?>
                <li>
                    <a href="<?= SITE ?>/admin/relatorios">
                        <i class="bi bi-circle"></i><span>Relatórios</span>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="<?= SITE ?>/admin/clientes">
                        <i class="bi bi-circle"></i><span>Clientes com horários agendados</span>
                    </a>
                </li>
                <li>
                    <a href="<?= SITE ?>/admin/resolver/agendamentos">
                        <i class="bi bi-circle"></i><span>Resolver agendamentos</span>
                    </a>
                </li>
                <li>
                    <a href="<?= SITE ?>/admin/instrutores">
                        <i class="bi bi-circle"></i><span>Instrutores</span>
                    </a>
                </li>
            </ul>
        </li>

        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#clientes-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-circle"></i><span>Usuários</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="clientes-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= SITE ?>/admin/usuarios">
                        <i class="bi bi-circle"></i><span>Gerenciar</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Components Nav -->

        <li class="nav-heading">Sistema</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= SITE ?>/logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sair</span>
            </a>
        </li><!-- End Login Page Nav -->
    </ul>

</aside><!-- End Sidebar-->