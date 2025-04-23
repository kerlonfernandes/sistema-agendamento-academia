<?php include('components/head.php') ?>

<body>
    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>
    <?php include('components/overlay.php'); ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Configuração do Estabelecimento</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>">Inicio</a></li>
                    <li class="breadcrumb-item active">Configurações</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Configurações do Estabelecimento</h5>
                            
                            <!-- Navegação por etapas -->
                            <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Informações Básicas</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="horarios-tab" data-bs-toggle="tab" data-bs-target="#horarios" type="button" role="tab">Horários</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="imagens-tab" data-bs-toggle="tab" data-bs-target="#imagens" type="button" role="tab">Imagens</button>
                                </li>
                            </ul>
                            
                            <form class="row g-3 mt-3">
                                <div class="tab-content pt-2" id="borderedTabContent">
                                    <!-- ETAPA 1: Informações Básicas -->
                                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                                        <div class="row">
                                            <!-- Público -->
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="publico" name="publico" checked>
                                                    <label class="form-check-label" for="publico">Estabelecimento Público</label>
                                                </div>
                                            </div>
                                            
                                            <!-- Nome -->
                                            <div class="col-md-12">
                                                <label for="nome" class="form-label">Nome do Estabelecimento *</label>
                                                <input type="text" class="form-control" id="nome" name="nome" required>
                                            </div>
                                            
                                            <!-- Descrição -->
                                            <div class="col-md-12">
                                                <label for="descricao" class="form-label">Descrição</label>
                                                <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end mt-3">
                                            <button type="button" class="btn btn-primary next-step" data-next="horarios">Próximo <i class="bi bi-arrow-right"></i></button>
                                        </div>
                                    </div>
                                    
                                    <!-- ETAPA 2: Horários -->
                                    <div class="tab-pane fade" id="horarios" role="tabpanel">
                                        <div class="row">
                                            <!-- Horários -->
                                            <div class="col-md-6">
                                                <label for="horario_abertura" class="form-label">Horário de Abertura</label>
                                                <input type="time" class="form-control" id="horario_abertura" name="horario_abertura">
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="horario_fechamento" class="form-label">Horário de Fechamento</label>
                                                <input type="time" class="form-control" id="horario_fechamento" name="horario_fechamento">
                                            </div>
                                            
                                            <!-- Dias de Abertura -->
                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">Dias de Funcionamento</label>
                                                <div class="dias-abertura-container">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="domingo" name="dias_abertura[]" value="domingo">
                                                        <label class="form-check-label" for="domingo">Domingo</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="segunda" name="dias_abertura[]" value="segunda-feira">
                                                        <label class="form-check-label" for="segunda">Segunda-feira</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="terca" name="dias_abertura[]" value="terca-feira">
                                                        <label class="form-check-label" for="terca">Terça-feira</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="quarta" name="dias_abertura[]" value="quarta-feira">
                                                        <label class="form-check-label" for="quarta">Quarta-feira</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="quinta" name="dias_abertura[]" value="quinta-feira">
                                                        <label class="form-check-label" for="quinta">Quinta-feira</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="sexta" name="dias_abertura[]" value="sexta-feira">
                                                        <label class="form-check-label" for="sexta">Sexta-feira</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="sabado" name="dias_abertura[]" value="sabado">
                                                        <label class="form-check-label" for="sabado">Sábado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-3">
                                            <button type="button" class="btn btn-secondary prev-step" data-prev="info"><i class="bi bi-arrow-left"></i> Anterior</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="imagens">Próximo <i class="bi bi-arrow-right"></i></button>
                                        </div>
                                    </div>
                                    
                                    <!-- ETAPA 3: Imagens -->
                                    <div class="tab-pane fade" id="imagens" role="tabpanel">
                                        <div class="row">
                                            <!-- Imagem Principal -->
                                            <div class="col-md-6">
                                                <label for="imagem_principal" class="form-label">Imagem Principal *</label>
                                                <input class="form-control" type="file" id="imagem_principal" name="imagem_principal" required>
                                                <small class="text-muted">Imagem que aparecerá como principal do estabelecimento</small>
                                            </div>
                                            
                                            <!-- Imagem Capa -->
                                            <div class="col-md-6">
                                                <label for="imagem_capa" class="form-label">Imagem de Capa *</label>
                                                <input class="form-control" type="file" id="imagem_capa" name="imagem_capa" required>
                                                <small class="text-muted">Imagem que aparecerá no topo da página do estabelecimento</small>
                                            </div>
                                            
                                            <!-- Imagens do Estabelecimento -->
                                            <div class="col-md-12 mt-3">
                                                <label for="imagens_estabelecimento" class="form-label">Galeria de Imagens</label>
                                                <input class="form-control" type="file" id="imagens_estabelecimento" name="imagens_estabelecimento[]" multiple>
                                                <small class="text-muted">Selecione múltiplas imagens para a galeria</small>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-3">
                                            <button type="button" class="btn btn-secondary prev-step" data-prev="horarios"><i class="bi bi-arrow-left"></i> Anterior</button>
                                            <button type="submit" class="btn btn-success">Salvar Configurações</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
    <?php include("components/main-js.php") ?>

    <!-- Script para navegação entre etapas -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navegação entre etapas
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', function() {
                const nextTab = this.getAttribute('data-next');
                const nextTabEl = document.querySelector(`#${nextTab}-tab`);
                const tab = new bootstrap.Tab(nextTabEl);
                tab.show();
            });
        });
        
        document.querySelectorAll('.prev-step').forEach(button => {
            button.addEventListener('click', function() {
                const prevTab = this.getAttribute('data-prev');
                const prevTabEl = document.querySelector(`#${prevTab}-tab`);
                const tab = new bootstrap.Tab(prevTabEl);
                tab.show();
            });
        });
    });
    </script>
</body>
</html>