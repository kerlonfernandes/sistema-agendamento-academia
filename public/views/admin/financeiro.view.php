<?php include('components/head.php') ?>

<?php
$timestamp = strtotime($data_inicio ?? date('Y-m-d'));
$anoBase = date("Y", $timestamp);
$anoAtual = date('Y');
?>

<body>

    <!-- ======= Header ======= -->
    <?php include('components/header.php'); ?>
    <!-- ======= Sidebar ======= -->
    <?php include('components/side-bar.php'); ?>

    <?php include('components/overlay.php'); ?>

    <style>
        .config-section {
            margin-bottom: 40px;
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .config-section h5 {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #012970;
        }

        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
            border: 1px solid #ddd;
        }

        .days-checkbox {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .days-checkbox .form-check {
            min-width: 120px;
        }

        .horarios-table th {
            white-space: nowrap;
        }
    </style>

    <main id="main" class="main">
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100; width: 350px;">
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
        </div>
        <div class="pagetitle">
            <h1>Configurações do Financeiro</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= SITE ?>/admin">Início</a></li>
                    <li class="breadcrumb-item active">Financeiro</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="config-section">
                        <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#relatorioFinanceiroModal">
                            <i class="bi bi-file-earmark-text me-1"></i> Exportar Relatório Financeiro
                        </button>

                        <h5><i class="bi bi-gear-fill me-2"></i> Cobrança</h5>
                        <form action="<?= SITE ?>/admin/financeiro/configuracoes/salvar" method="post" enctype="multipart/form-data">
                        </form>
                    </div>

                    <div class="card mt-4" id="resultsCard">
                        <div class="card-body">
                            <h5 class="card-title">Resultados Financeiros</h5>

                            <!-- Cards de Resumo -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">Receita Total</h6>
                                            <h3 class="card-text">R$ <span id="totalReceita">0,00</span></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">Total de Agendamentos</h6>
                                            <h3 class="card-text"><span id="totalAgendamentos">0</span></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">Média por Agendamento</h6>
                                            <h3 class="card-text">R$ <span id="mediaAgendamento">0,00</span></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" id="financialTable">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Vínculo</th>
                                            <th>Quantidade</th>
                                            <th>Valor Unitário</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Modal do Relatório Financeiro -->
                    <?php include('components/modal-relatorio-financeiro.php'); ?>
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

    <!-- Scripts específicos para relatórios financeiros -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Ativar seleção múltipla nos vínculos
            $('select[name="vinculo[]"]').select2({
                placeholder: "Selecione os vínculos",
                allowClear: true,
                width: '100%'
            });

            $('#resetFilters').click(function() {
                $('#financialReportForm')[0].reset();
                $('select[name="vinculo[]"]').val(null).trigger('change');
                $('#resultsCard').hide();
            });

            // Resetar o formulário quando o modal for fechado
            $('#relatorioFinanceiroModal').on('hidden.bs.modal', function() {
                $('#financialReportForm')[0].reset();
                $('select[name="vinculo[]"]').val(null).trigger('change');
            });

            // Ajustar o Select2 dentro do modal
            $('#relatorioFinanceiroModal').on('shown.bs.modal', function() {
                $('select[name="vinculo[]"]').select2({
                    dropdownParent: $('#relatorioFinanceiroModal')
                });
            });
        });

        $(document).on('change', "#dataEspecifica", function() {
            if ($("#dataEspecifica").is(':checked')) {
                $('.mes_data, .ano_data').prop('disabled', true);
                $('.data_inicio, .data_fim').prop('disabled', false);
            } else {
                $('.mes_data, .ano_data').prop('disabled', false);
                $('.data_inicio, .data_fim').prop('disabled', true);
            }
        });

        $('select[name="vinculo[]"]').change(function() {
            const $select = $(this);
            const selectedValues = $select.val();

            if (selectedValues && selectedValues.includes('all')) {
                $select.find('option[value="all"]').prop('selected', false);
                $select.find('option').each(function() {
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

        .bg-primary,
        .bg-success,
        .bg-info {
            border: none;
        }

        .card-text {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Estilos específicos para o modal */
        .modal-xl {
            max-width: 95%;
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 15px 15px 0 0;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 15px 15px;
        }

        .modal-body {
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .modal-xl {
                max-width: 100%;
                margin: 0.5rem;
            }
        }
    </style>
</body>

</html>