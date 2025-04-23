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
                                <?php if (isset($_SESSION['message'])): ?>
                                    <?php
                                    $message = htmlspecialchars($_SESSION['message']);
                                    $alertClass = 'alert-' . ($_SESSION['status'] === 'success' ? 'success' : 'danger');
                                    ?>
                                    <div class="alert <?php echo $alertClass; ?> mt-3 alert-dismissible fade show" role="alert">
                                        <?php echo $message; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <?php unset($_SESSION['message'], $_SESSION['status']); ?>
                                <?php endif; ?>
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
                                    <h5 class="card-title pb-0 fs-4" style="color: #2c3e50;">Fazer Login</h5>
                                    <p class="text-muted small">Entre com suas credenciais</p>
                                </div>

                                <form class="row g-3 needs-validation" novalidate method="POST" action="<?= SITE ?>/auth<?php if(isset($_GET['keep']) && !empty($_GET['keep'])) echo "?keep=".$_GET['keep'] ?>">
                                    <div class="col-12">
                                        <?php if(isset($_GET['keep']) && !isset($_GET['keep'])): ?>
                                            <input type="hidden" name="keep_data" value="<?= $_GET['keep'] ?>">
                                        <?php endif; ?>
                                        <label for="yourUsername" class="form-label">Email</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                                            <input type="email" name="email" class="form-control border-start-0" id="yourUsername" placeholder="Digite seu email" required
                                                <?php if (!empty($_SESSION['last_try']['email'])) {
                                                    echo "value='{$_SESSION['last_try']['email']}'";
                                                }
                                                if(isset($_SESSION['retry']) || !empty($_SESSION['retry'])) 
                                                {
                                                    echo "value='".$_SESSION['retry']."'";
                                                    unset($_SESSION['retry']);
                                                }
                                                ?>>
                                            <div class="invalid-feedback">Por favor, digite seu email</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Senha</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                                            <input type="password" name="password" class="form-control border-start-0" id="yourPassword" placeholder="Digite sua senha" required>
                                            <div class="invalid-feedback">Por favor, digite sua senha</div>
                                        </div>
                                        <!-- <div class="text-end mt-2">
                                            <a href="#" class="small text-muted">Esqueceu a senha?</a>
                                        </div> -->
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-2 rounded-pill" type="submit" style="background-color: #4e73df; border: none;">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                                        </button>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex align-items-center my-3">
                                            <hr class="flex-grow-1">
                                            <span class="px-3 text-muted small">ou</span>
                                            <hr class="flex-grow-1">
                                        </div>

                                        <!-- Botão de Login com Google -->
                                        <!-- <a href="<?= SITE ?>/auth/google" class="btn btn-outline-danger w-100 py-2 rounded-pill">
                                            <img src="<?= SRC ?>/images/imagem_67f96adacb08a1.62774783.png" alt="Google" style="height: 18px; margin-right: 10px;">
                                            Entrar com Google
                                        </a> -->
                                    </div>

                                    <div class="col-12 text-center mt-3">
                                        <p class="small text-muted mb-0">Não tem uma conta? <a href="<?= SITE ?>/cadastrar" class="text-primary" style="text-decoration: none;">Crie uma</a></p>
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
<script src="<?= SRC ?>/js/masks.js?id=<?= uniqid() ?>"></script>

<?php include("components/main-js.php") ?>
</body>

</html>