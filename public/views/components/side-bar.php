<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="<?= SITE ?>/panel">
                <i class="bi bi-grid"></i>
                <span>Inicio</span>
            </a>
        </li>
        <a class="nav-link collapsed" data-bs-target="#projetos-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-boxes"></i><span>Estabelecimento</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="projetos-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= SITE ?>/estabelecimento/painel">
                        <i class="bi bi-circle"></i><span>Painel</span>
                    </a>
                </li>
                <li>
                    <a href="<?= SITE ?>/estabelecimento/servicos">
                        <i class="bi bi-circle"></i><span>Serviços</span>
                    </a>
                </li>
                <li>
                    <a href="<?= SITE ?>/estabelecimento/configuracoes">
                        <i class="bi bi-circle"></i><span>Configurações</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-vcard"></i><span>Agendamentos</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= SITE ?>/agendamentos/adicionar">
                        <i class="bi bi-circle"></i><span>Adicionar</span>
                    </a>
                </li>
                <li>
                    <a href="<?= SITE ?>/agendamentos/list">
                        <i class="bi bi-circle"></i><span>Gerenciar</span>
                    </a>
                </li>
                
            </ul>
        </li>

        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#beneficios-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-postcard"></i><span>Benefícios</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="beneficios-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= SITE ?>/beneficios">
                        <i class="bi bi-circle"></i><span>Gerenciar</span>
                    </a>
                </li>
                <li>
                    <a href="<?= SITE ?>/cadastrar/beneficio">
                        <i class="bi bi-circle"></i><span>Cadastrar</span>
                    </a>
                </li>
            </ul>
        </li> -->
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