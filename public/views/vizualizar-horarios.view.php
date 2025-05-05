<?php include('components/head.php') ?>
<?php include('components/client-top-bar.php') ?>
<style>
    .instrutor-info-inline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.instrutor-img-inline {
    width: 40px; /* Tamanho da imagem */
    height: 40px; /* Tamanho da imagem */
    border-radius: 50%; /* Deixa a imagem circular */
    object-fit: cover; /* Faz a imagem cobrir totalmente o espaço */
    margin-right: 8px; /* Espaço entre a imagem e o nome */
}

.instrutor-nome-inline {
    font-size: 14px; /* Tamanho do nome */
    font-weight: bold; /* Torna o nome em negrito */
    color: #333; /* Cor do nome */
    white-space: nowrap; /* Impede que o nome quebre em várias linhas */
}

</style>
<body>
    <?php include('components/overlay.php'); ?>

    <div class="modal fade" id="detalhesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Agendamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetalhesContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-2">Carregando detalhes...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-danger btn-cancelar" data-id="" title="Cancelar">
                        Cancelar <i class="bi bi-x-circle"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-container">
                    <h2 class="mb-4 text-center"><i class="bi bi-calendar-check"></i> Meus Agendamentos</h2>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aqui você pode visualizar todos os seus horários agendados no Estúdio Funcional UNIVC.
                    </div>

                    <!-- Lista de Agendamentos -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center"><i class="bi bi-calendar-date"></i> Dia da semana</th>
                                    <th class="text-center"><i class="bi bi-clock"></i> Horário</th>
                                    <th class="text-center"><i class="bi bi-info-circle"></i> Status</th>
                                    <th class="text-center"><i class="bi bi-person"></i> Instrutor</th>
                                    <th class="text-center"><i class="bi bi-gear"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody id="listaAgendamentos">
                                <?php if ($horarios->affected_rows > 0): ?>
                                    <?php foreach ($horarios->results as $horario) : ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php
                                                switch ($horario->dia_semana) {
                                                    case 'segunda-feira':
                                                        echo "Segunda-Feira";
                                                        break;
                                                    case 'terca-feira':
                                                        echo "Terça-Feira";
                                                        break;
                                                    case 'quarta-feira':
                                                        echo "Quarta-Feira";
                                                        break;
                                                    case 'quinta-feira':
                                                        echo "Quinta-Feira";
                                                        break;
                                                    case 'sexta-feira':
                                                        echo "Sexta-Feira";
                                                        break;
                                                    case 'sábado':
                                                        echo "Sábado";
                                                        break;
                                                    case 'domingo':
                                                        echo "Domingo";
                                                        break;
                                                    default:
                                                        echo ucfirst($horario->dia_semana);
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center"><?= $horario->horarios_formatados ?></td>
                                            <td class="text-center">
                                                <span class="badge 
                                        <?php
                                        switch (strtolower($horario->status_agendamento)) {
                                            case 'confirmado':
                                            case 'aprovado':
                                                echo 'bg-success';
                                                break;

                                            case 'pendente':
                                            case 'aguardando':
                                            case 'em análise':
                                                echo 'bg-warning text-dark';
                                                break;

                                            case 'cancelado':
                                            case 'rejeitado':
                                            case 'recusado':
                                                echo 'bg-danger';
                                                break;

                                            case 'finalizado':
                                            case 'Confirmado':
                                            case 'agendado':

                                                echo 'bg-primary';
                                                break;

                                            case 'remarcado':
                                            case 'reagendado':
                                                echo 'bg-info text-dark';
                                                break;

                                            default:
                                                echo 'bg-secondary';
                                        }
                                        ?>">
                                                    <?= ucfirst($horario->status_agendamento) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($horario->instrutor_id) || $horario->instrutor_id != null): ?>
                                                    <div class="instrutor-info-inline">
                                                        <img src="<?= $horario->instrutor_profile_img ?>" alt="Imagem do Instrutor" class="instrutor-img-inline">
                                                        <span class="instrutor-nome-inline"><?= $horario->instrutor_primeiro_nome ?></span>
                                                    </div>

                                                <?php else: ?>
                                                    <span>Não informado</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary btn-detalhes" data-id="<?= $this->helpers->encodeURL($horario->id) ?>" title="Visualizar">
                                                    Detalhes
                                                </button>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= Footer ======= -->
    <?php include("components/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include("components/main-js.php") ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

        });
    </script>
</body>

</html>