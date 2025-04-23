<?php include('components/head.php') ?>
<main>
    <div class="container <?php if (isset($_SESSION['effect']) && !empty($_SESSION['effect'])) echo $_SESSION['effect'];
                            unset($_SESSION['effect']) ?>">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                        <div class="d-flex justify-content-center py-4">
                            <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <img src="assets/img/logo.png" alt="" class="img-fluid" style="max-height: 50px;">
                                <span class="d-none d-lg-block"></span>
                            </a>
                        </div>

                        <div class="card mb-3 shadow-sm border-0" style="min-height: auto; border-radius: 15px;">

                            <div class="card-body p-4">
                                <?php if (isset($_SESSION['alert_message'])): ?>
                                    <?php
                                    $alert = $_SESSION['alert_message'];
                                    unset($_SESSION['alert_message']);
                                    ?>
                                    <div class="alert alert-<?= $alert['type'] ?> <?= $alert['dismissible'] ? 'alert-dismissible fade show' : '' ?>" role="alert">
                                        <?php if (!empty($alert['title'])): ?>
                                            <strong><?= $alert['title'] ?></strong>
                                        <?php endif; ?>
                                        <?= $alert['message'] ?>
                                        <?php if ($alert['dismissible']): ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="text-center pt-2 pb-3">
                                    <h5 class="card-title pb-0 fs-4" style="color: #2c3e50;">Criar Conta</h5
                                    <p class="text-muted small">Preencha seus dados para se cadastrar</p>
                                </div>

                                <form class="row g-3 needs-validation" novalidate method="POST" action="<?= SITE ?>/register<?php if(isset($_GET['keep']) && !empty($_GET['keep'])) echo "?keep=".$_GET['keep'] ?>">
                                    <?php if (isset($_GET['keep']) && !isset($_GET['keep'])): ?>
                                        <input type="hidden" name="keep_data" value="<?= $_GET['keep'] ?>">
                                    <?php endif; ?>
                                    <div class="col-12">
                                        <label for="yourName" class="form-label">Nome Completo</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                                            <input type="text" name="name" class="form-control border-start-0" id="yourName" placeholder="Digite seu nome completo" required
                                                <?php if ($nome != null) {
                                                    echo "value='$nome'";
                                                }
                                                ?>>
                                            <div class="invalid-feedback">Por favor, digite seu nome</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourEmail" class="form-label">Email</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                                            <input type="email" name="email" class="form-control border-start-0" id="yourEmail" placeholder="Digite seu email" required
                                                <?php if ($email != null) {
                                                    echo "value='$email'";
                                                }
                                                ?>>
                                            <div class="invalid-feedback">Por favor, digite um email válido</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPhone" class="form-label">Telefone</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-light"><i class="bi bi-phone text-muted"></i></span>
                                            <input type="text" name="phone" class="form-control border-start-0 celular" id="yourPhone" placeholder="(00) 00000-0000" required
                                                <?php if ($telefone != null) {
                                                    echo "value='$telefone'";
                                                }
                                                ?>>
                                            <div class="invalid-feedback">Por favor, digite seu telefone</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourCpf" class="form-label">CPF</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-light"><i class="bi bi-card-text text-muted"></i></span>
                                            <input type="text" name="cpf" class="form-control border-start-0 cpf" id="yourCpf" placeholder="000.000.000-00" required>
                                            <div class="invalid-feedback">Por favor, digite um CPF válido</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Senha</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                                            <input type="password" name="password" class="form-control border-start-0" id="yourPassword" placeholder="Digite sua senha" required>
                                            <div class="invalid-feedback">Por favor, digite sua senha</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="repeatPassword" class="form-label">Repetir Senha</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-light"><i class="bi bi-lock-fill text-muted"></i></span>
                                            <input type="password" name="repeat_password" class="form-control border-start-0" id="repeatPassword" placeholder="Repita sua senha" required>
                                            <div class="invalid-feedback">As senhas devem ser iguais</div>
                                        </div>
                                    </div>
<!-- 
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="acceptTerms" required>
                                            <label class="form-check-label small" for="acceptTerms">
                                                Eu concordo com os <a href="#" class="text-primary">Termos de Serviço</a> e <a href="#" class="text-primary">Política de Privacidade</a>
                                            </label>
                                            <div class="invalid-feedback">Você deve concordar antes de se cadastrar</div>
                                        </div>
                                    </div> -->

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-2 rounded-pill" type="submit" style="background-color: #4e73df; border: none;">
                                            <i class="bi bi-person-plus me-2"></i>Criar Conta
                                        </button>
                                    </div>

                                    <div class="col-12 text-center mt-3">
                                        <p class="small text-muted mb-0">Já tem uma conta? <a href="<?= SITE ?>/login" class="text-primary" style="text-decoration: none;">Faça login</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">

<script src="<?= SRC ?>/assets/jquery.min.js"></script>
<script src="<?= SRC ?>/assets/jquery.mask.js"></script>
<script>
    $(document).ready(function() {
        $('#repeatPassword').on('keyup', function() {
            if ($('#yourPassword').val() !== $('#repeatPassword').val()) {
                this.setCustomValidity("As senhas não coincidem");
            } else {
                this.setCustomValidity("");
            }
        });
    });
</script>

<?php include("components/main-js.php") ?>
</body>

</html>