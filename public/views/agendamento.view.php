<?php include('components/head.php') ?>
<?php include('components/client-top-bar.php') ?>

<body>

    <?php include('components/overlay.php'); ?>

    <?php if (isset($_SESSION['after_login']) && !empty($_SESSION['after_login'])): ?>
        <input type="hidden" class="<?= $_SESSION['after_login'] ?>" value='<?php echo json_encode($_SESSION['last_form']);
                                                                            unset($_SESSION['last_form']);
                                                                            unset($_SESSION['after_login']); ?>'>
    <?php endif; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    <img src="<?= IMAGES . "/" . $imagem_formulario ?>" alt="Imagem atual" style="width: 100%; border-radius: 10px;" id="imagemPreview">

                    <?php if ($formulario_ativo == 1): ?>
                        <div class="alert alert-info mt-3">
                            <?= $aviso_formulario ?>
                        </div>

                        <form id="agendamentoForm">
                            <div class="mb-4">
                                <?php if (!isset($user->nome)): ?>
                                <h4 class="mb-3"><i class="bi bi-person-fill"></i> Dados Pessoais</h4>

                                    <div class="mb-3">
                                        <label for="nome" class="form-label required-field">Nome</label>
                                        <input type="text" class="form-control" id="nome" name="nome" required>
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($user->nome) ?>" required>
                                <?php endif; ?>

                                <?php if (!isset($user->telefone)): ?>
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label required-field">Telefone</label>
                                        <input type="tel" class="form-control celular" id="telefone" name="telefone" required>
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" class="form-control celular" id="telefone" name="telefone" value="<?= htmlspecialchars($user->telefone) ?>" required>
                                <?php endif; ?>

                                <?php if (!isset($user->email)): ?>
                                    <div class="mb-3">
                                        <label for="email" class="form-label required-field">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <?php if (!isset($user->vinculo)): ?>
                                    <label for="vinculo" class="form-label required-field">Qual seu vínculo com a UNIVC?</label>
                                        <select class="form-select" id="vinculo" name="vinculo" required>
                                            <?php foreach ($vinculos as $label => $vinculo): ?>
                                                <option value="<?= htmlspecialchars($vinculo) ?>"><?= htmlspecialchars($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <input type="hidden" id="vinculo" name="vinculo" value="<?= htmlspecialchars($user->vinculo) ?>" required>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="mb-3"><i class="bi bi-calendar-week"></i> Disponibilidade</h4>
                                <div class="mt-3 mb-5">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <div class="alert alert-success py-1 px-2 mb-0">
                                            <small><i class="bi bi-circle-fill me-1"></i> Menos de <?= $metade ?> agendamentos</small>
                                        </div>
                                        <div class="alert alert-warning py-1 px-2 mb-0">
                                            <small><i class="bi bi-circle-fill me-1"></i> <?= $metade ?> a <?= $um_a_menos ?> agendamentos</small>
                                        </div>
                                        <div class="alert alert-danger py-1 px-2 mb-0">
                                            <small><i class="bi bi-circle-fill me-1"></i><?= $limite_agendamentos ?> (lotado)</small>
                                        </div>
                                    </div>
                                </div>
                                <ul class="nav nav-tabs mb-3" id="diasTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="todos-tab" data-bs-toggle="tab" data-bs-target="#todos-dias" type="button" role="tab" aria-controls="todos-dias" aria-selected="true">Todos os Dias</button>
                                    </li>
                                    <!-- <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="segunda-tab" data-bs-toggle="tab" data-bs-target="#segunda-feira" type="button" role="tab" aria-controls="segunda-feira" aria-selected="false">Segunda</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="terca-tab" data-bs-toggle="tab" data-bs-target="#terca-feira" type="button" role="tab" aria-controls="terca-feira" aria-selected="false">Terça</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="quarta-tab" data-bs-toggle="tab" data-bs-target="#quarta-feira" type="button" role="tab" aria-controls="quarta-feira" aria-selected="false">Quarta</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="quinta-tab" data-bs-toggle="tab" data-bs-target="#quinta-feira" type="button" role="tab" aria-controls="quinta-feira" aria-selected="false">Quinta</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="sexta-tab" data-bs-toggle="tab" data-bs-target="#sexta-feira" type="button" role="tab" aria-controls="sexta-feira" aria-selected="false">Sexta</button>
                                </li> -->
                                </ul>

                                <div class="tab-content" id="diasTabsContent">
                                    <div class="tab-pane fade show active" id="todos-dias" role="tabpanel" aria-labelledby="todos-tab">
                                        <div class="row" id="horarios-todos">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Carregando...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="segunda-feira" role="tabpanel" aria-labelledby="segunda-tab">
                                        <div class="row" id="horarios-segunda">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Carregando...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="terca-feira" role="tabpanel" aria-labelledby="terca-tab">
                                        <div class="row" id="horarios-terca">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Carregando...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="quarta-feira" role="tabpanel" aria-labelledby="quarta-tab">
                                        <div class="row" id="horarios-quarta">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Carregando...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="quinta-feira" role="tabpanel" aria-labelledby="quinta-tab">
                                        <div class="row" id="horarios-quinta">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Carregando...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="sexta-feira" role="tabpanel" aria-labelledby="sexta-tab">
                                        <div class="row" id="horarios-sexta">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Carregando...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="mb-3"><i class="bi bi-exclamation-triangle-fill"></i> Observações</h4>
                                <div class="mb-3">
                                    <label for="restricoes" class="form-label">Possui alguma restrição ao movimento ou informação importante para os treinamentos?</label>
                                    <textarea class="form-control" id="restricoes" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="contact-info">
                                <h5><i class="bi bi-question-circle"></i> Dúvidas ou informações:</h5>
                                <p><i class="bi bi-envelope"></i> corporativo@ivc.br</p>
                                <p><i class="bi bi-telephone"></i> (27) 99580-9176</p>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button class="btn" style="background-color: <?= $cor_primaria ?>; color: white;" type="submit"><i class="bi bi-send-fill"></i> Enviar Agendamento</button>
                            </div>

                            <div class="text-muted mt-3">
                                <small>Nunca envie senhas por formulários. Este conteúdo é destinado exclusivamente ao agendamento do Estúdio Funcional UNIVC.</small>
                            </div>
                        </form>

                    <?php else: ?>
                        <div class="alert alert-danger mt-3">
                            <h4 class="mb-3 text-center"><i class="bi bi-exclamation-triangle-fill"></i> Formulário de agendamento desativado</h4>
                            <p class="text-center">O formulário de agendamento está desativado. Por favor, tente novamente mais tarde.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include("components/main-js.php") ?>

</body>

</html>