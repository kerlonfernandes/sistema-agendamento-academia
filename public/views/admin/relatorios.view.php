<?php
include('components/head.php');

$timestamp = strtotime($data_inicio);

$anoBase = date("Y", $timestamp);
$anoAtual = date('Y');

?>

<body>
    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>

    <?php include('components/overlay.php'); ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Relatórios</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin">Voltar para o inicio</a></li>
                    <li class="breadcrumb-item active">Gerar Relatório</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filtros do Relatório</h5>

                            <form id="reportForm" method="GET" action="<?= SITE ?>/admin/relatorio/gerar">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Mês</label>
                                                <select class="form-select mes_data" name="month" required>
                                                    <option value="all">Todos os meses</option>
                                                    <option value="1">Janeiro</option>
                                                    <option value="2">Fevereiro</option>
                                                    <option value="3">Março</option>
                                                    <option value="4">Abril</option>
                                                    <option value="5">Maio</option>
                                                    <option value="6">Junho</option>
                                                    <option value="7">Julho</option>
                                                    <option value="8">Agosto</option>
                                                    <option value="9">Setembro</option>
                                                    <option value="10">Outubro</option>
                                                    <option value="11">Novembro</option>
                                                    <option value="12">Dezembro</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Ano</label>
                                                <select class="form-select ano_data" name="year" required>
                                                    <option value="all">Todos os anos</option>
                                                    <?php for ($i = $anoAtual; $i >= $anoBase; $i--): ?>
                                                        <option value="<?= $i ?>" <?= ($i == $anoAtual) ? 'selected' : '' ?>><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Horários</label>
                                        <select class="form-select" name="horario[]" multiple required>
                                            <?php if ($horarios->affected_rows > 0) : ?>
                                                <option value="all">Todos os horários</option>
                                                <?php foreach ($horarios->results as $horario) : ?>
                                                    <option value="<?= $horario->id ?>">
                                                        <?= $horario->dia_semana ?> - <?= $horario->horario_inicio ?> - <?= $horario->horario_fim ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option disabled>Nenhum horário disponível</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <div class="form-check form-switch mt-5 mb-3">
                                            <input class="form-check-input" type="checkbox" role="switch" id="dataEspecifica">
                                            <label class="form-check-label" for="dataEspecifica">Filtrar por data específica</label>
                                        </div>
                                        <label class="form-label">Filtrar por período específico</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control data_inicio" name="start_date" disabled required>
                                            <span class="input-group-text">até</span>
                                            <input type="date" class="form-control data_fim" name="end_date" disabled required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="row g-3 mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-file-earmark-text me-1"></i> Gerar Relatório
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary ms-2" id="resetFilters">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Limpar Filtros
                                        </button>
                                       
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4" id="resultsCard" style="display: none;">
                        <div class="card-body">
                            <h5 class="card-title">Resultados do Relatório</h5>
                            <div class="table-responsive">
                                <table class="table table-striped" id="reportTable">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Dia Semana</th>
                                            <th>Horário</th>
                                            <th>Total Agendamentos</th>
                                            <th>Detalhes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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

    <!-- Vendor JS Files -->
    <?php include("components/admin-main-js.php") ?>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Scripts específicos para relatórios -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Ativar seleção múltipla nos dias da semana
            $('select[name="horario[]"]').select2({
                placeholder: "Selecione os horários",
                allowClear: true,
                width: '100%'
            });

            $('select[name="time_slots[]"]').select2({
                placeholder: "Selecione os horários",
                allowClear: true,
                width: '100%'
            });

            $('#resetFilters').click(function() {
                $('#reportForm')[0].reset();
                $('select[name="horario[]"]').val(null).trigger('change');
                $('#resultsCard').hide();
            });
        });

        $(document).on('change', "#dataEspecifica", function() {
            if ($("#dataEspecifica").is(':checked')) {
                $('.mes_data, .ano_data').prop('disabled', true);
                $('.data_inicio, .data_fim').prop('disabled', false);
                console.log("Modo data específica - selects desabilitados");
            } else {
                $('.mes_data, .ano_data').prop('disabled', false);
                $('.data_inicio, .data_fim').prop('disabled', true);
                console.log("Modo período - selects habilitados");
            }
        });

        $('select[name="horario[]"]').change(function () {
        const $select = $(this);
        const selectedValues = $select.val();

        if (selectedValues && selectedValues.includes('all')) {
            $select.find('option[value="all"]').prop('selected', false);

            $select.find('option').each(function () {
                if ($(this).val() !== 'all') {
                    $(this).prop('selected', true);
                }
            });

            $select.trigger('change');
        }
    });
    </script>

    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
            min-height: 42px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        #resultsCard {
            transition: all 0.3s ease;
        }
    </style>
</body>

</html>