<?php include('components/head.php') ?>

<body>

    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>

    <?php include('components/overlay.php'); ?>

    <input type="hidden" class="h" value="<?= isset($horario_fim) && !empty($horario_fim) ? $horario_fim : '0' ?>">

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Acompanhamento</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin">Inicio</a></li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">


                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-4 h3" id="relogio"></div>
                            <h5 class="card-title">
                                <?php if ($horario_inicio != null && $horario_fim != null): ?>
                                    Horários Agendados de: <?= $horario_inicio ?> às <?= $horario_fim ?>
                                <?php else: ?>
                                    Não há nenhum horário
                                <?php endif; ?>
                            </h5>
                            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

                            <select name="horario" id="horarioSelect" class="form-select" style="width: 100%; height: 50px;">
                                <?php if ($horarios != null): ?>
                                    <?php foreach ($horarios as $horario): ?>
                                        <option value="<?= $horario->id ?>" <?= isset($get->h) && $get->h == $horario->id ? 'selected' : '' ?>>
                                            <?= $horario->dia_semana ?> | <?= $horario->horario ?> | <?= $horario->total_agendamentos ?> agendamento<?= $horario->total_agendamentos > 1 ? "s" : "" ?>.
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                            <script>
                                $(document).ready(function() {
                                    $('#horarioSelect').select2({
                                        placeholder: "Pesquise e selecione um horário",
                                        allowClear: true,
                                        minimumResultsForSearch: 1
                                    });

                                    var urlParams = new URLSearchParams(window.location.search);
                                    var horarioId = urlParams.get('h');
                                    
                                    if (horarioId) {
                                        $('#horarioSelect').val(horarioId).trigger('change');
                                    }

                                    $('#horarioSelect').on('change', function() {
                                        var selectedId = $(this).val();
                                        var currentId = urlParams.get('h');

                                        if (selectedId && selectedId !== currentId) {
                                            urlParams.set('h', selectedId);
                                            var newUrl = window.location.pathname + '?' + urlParams.toString();
                                            window.location.href = newUrl;
                                        }
                                    });
                                });
                            </script>
                            <div class="table-responsive">
                                <table id="horariosTable" class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Contato</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Horário</th>
                                            <th class="text-center">Data Agendamento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($horarios_atuais->affected_rows > 0 || empty($horarios_atuais) != null): ?>
                                            <?php foreach ($horarios_atuais->results as $horario): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="d-flex align-items-center">
                                                            <?php
                                                            $nome = explode(' ', $horario->nome);
                                                            $iniciais = '';
                                                            if (count($nome) >= 2) {
                                                                $iniciais = strtoupper(substr($nome[0], 0, 1) . substr($nome[count($nome)-1], 0, 1));
                                                            } else {
                                                                $iniciais = strtoupper(substr($horario->nome, 0, 2));
                                                            }
                                                            ?>
                                                            <img src="<?= !empty($horario->profile_img) ? 
                                                                $horario->profile_img : 
                                                                'https://ui-avatars.com/api/?name=' . urlencode($iniciais) . '&background=random&color=fff&size=40' ?>" 
                                                                 alt="Foto de <?= $horario->nome ?>" 
                                                                 class="rounded-circle me-2"
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                            <a href="<?= SITE ?>/admin/usuario/detalhes/<?= $horario->user_id ?>" class="text-decoration-none">
                                                                <?= $horario->nome ?>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span><?= formatarTelefone($horario->telefone) ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $status_class = '';
                                                        $status_icon = '';
                                                        switch($horario->status_agendamento) {
                                                            case 'confirmado':
                                                                $status_class = 'success';
                                                                $status_icon = 'check-circle';
                                                                break;
                                                            case 'agendado':
                                                                $status_class = 'warning';
                                                                $status_icon = 'clock';
                                                                break;
                                                            case 'cancelado':
                                                                $status_class = 'danger';
                                                                $status_icon = 'x-circle';
                                                                break;
                                                            default:
                                                                $status_class = 'secondary';
                                                                $status_icon = 'question-circle';
                                                        }
                                                        ?>
                                                        <span class="badge bg-<?= $status_class ?>">
                                                            <i class="bi bi-<?= $status_icon ?> me-1"></i>
                                                            <?= ucfirst($horario->status_agendamento) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary">
                                                            <?= $horario->horario_inicio ?> - <?= $horario->horario_fim ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (isset($horario->data_agendamento) && !empty($horario->data_agendamento)): ?>
                                                            <?= date('d/m/Y H:i', strtotime($horario->data_agendamento)) ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <div class="py-4">
                                                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                                                        <p class="mt-2 text-muted">Nenhum agendamento encontrado</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include("components/admin-main-js.php") ?>
    <script>
        let recarregado = false;

        function atualizarRelogio() {
            const relogioElement = document.getElementById('relogio');
            const agora = new Date();

            const opcoes = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            const formatador = new Intl.DateTimeFormat('pt-BR', opcoes);
            let dataFormatada = formatador.format(agora);

            dataFormatada = dataFormatada.replace(',', '');
            dataFormatada = dataFormatada.replace(/(\d{2}):(\d{2}):(\d{2})/, '$1:$2:$3');
            dataFormatada = dataFormatada.replace(/\b\w/g, l => l.toUpperCase());

            relogioElement.textContent = dataFormatada;

            let horario_fim = $('.h').val();
            if (horario_fim != 0) {
                verificarRecarregamentoNoHorario(horario_fim);
            }
        }

        function verificarRecarregamentoNoHorario(horarioAlvo) {
            if (recarregado) return;
            const agora = new Date();
            const horaAtual = String(agora.getHours()).padStart(2, '0');
            const minutoAtual = String(agora.getMinutes()).padStart(2, '0');
            const segundoAtual = String(agora.getSeconds()).padStart(2, '0');

            const horaAtualFormatada = `${horaAtual}:${minutoAtual}:${segundoAtual}`;

            // console.log( horaAtualFormatada);

            if (horaAtualFormatada === horarioAlvo) {
                console.log(`Recarregando página no horário exato: ${horarioAlvo}`);
                recarregado = true;
                window.location.reload(true);
            }
        }

        atualizarRelogio();
        setInterval(atualizarRelogio, 1000);
    </script>
</body>

</html>