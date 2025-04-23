$(document).ready(function () {
    const url = $("#url").val();

    $('#agendamentosTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
            search: "Pesquisar:",
            searchPlaceholder: "Digite para pesquisar...",
            lengthMenu: "Mostrar _MENU_ registros por página"
        },
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true,
        dom: '<"top-container"<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 search-container"f>>>rt<"bottom-container"<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>>',
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        initComplete: function () {
            $('.search-container input').addClass('form-control form-control-lg');
            $('.search-container').css({
                'text-align': 'right',
                'padding-right': '15px'
            });
        },
        columnDefs: [
            {
                targets: [0],
                visible: false
            },
            {
                targets: [1, 2, 3],
                orderable: true
            },
            {
                targets: [4],
                orderable: true,
                searchable: false
            },
            {
                targets: [5],
                orderable: false,
                searchable: false,
                className: 'dt-actions',
                width: '150px'
            }
        ]
    });

    $(document).on("show.bs.modal", "#editar-horario", function (e) {
        const button = $(e.relatedTarget);
        const id = button.data('id');

        $.ajax({
            url: url + "/reload/editar-horario.php",
            type: "GET",
            data: { id: id },
            beforeSend: function () {
                $(".editar-horario-form").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            success: function (response) {
                $(".editar-horario-form").html(response);
            }
        });
    });

    $('#cor_primaria').on('input', function () {
        $('#colorPreview').css('background-color', $(this).val());
    });

    $('#imagem_formulario').change(function (e) {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imagemPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });


    // PAGINA DE CONFIGURAÇÕES

    // $('#configForm').submit(function (e) {
    //     e.preventDefault();
    // });


    if (document.querySelector('#texto_aviso')) {
        ClassicEditor.create(document.querySelector('#texto_aviso'));
    }

    if (document.querySelector('.horarios-table')) {
        $('.horarios-table').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' // Tradução PT-BR
            },
            responsive: true
        });
    }

    $(document).on('change', '#formulario_ativo', function () {
        if ($(this).is(':checked')) {
            $.ajax({
                url: url + "/admin/agendamentos/formulario/status",
                type: "GET",
                data: { status: "1" },
                success: function (response) {
                    console.log(response);
                }
            });
        }
        else {
            $.ajax({
                url: url + "/admin/agendamentos/formulario/status",
                type: "GET",
                data: { status: "0" },
                success: function (response) {
                    console.log(response);
                }
            });
            $('#formulario_ativo').val(0);
        }
    });

    // $(document).on('blur', '#limite_agendamentos', function () {

    //     let valor = $(this).val().trim();

    //     if (valor != "") {
    //         if(valor < 1) {
    //             $('.aviso').html('<span class="text-danger">O limite de agendamentos não pode ser menor que 1!</span>');
    //             setTimeout(() => {
    //                 $('.aviso').fadeOut(3000);
    //             }, 3000);
    //             return;


    //         }

    //         if (isNaN(valor)) {
    //             $('.aviso').html('<span class="text-danger">O limite de agendamentos deve ser um número inteiro!</span>');
    //             setTimeout(() => {
    //                 $('.aviso').fadeOut(3000);
    //             }, 3000);
    //             return;
    //         }

    //         $.ajax({
    //             url: url + "/admin/agendamentos/limite-agendamentos",
    //             type: "GET",
    //             data: { limite: valor },
    //             success: function (response) {
    //                 $('.aviso').html('<span class="text-success">Limite de agendamentos atualizado com sucesso!</span>');
    //                 setTimeout(() => {
    //                     $('.aviso').fadeOut(3000);
    //                 }, 3000);
    //             }
    //         });
    //     }
    // });

    // $(document).on('click', '#btn_aviso', function () {
    //     let texto = $('#texto_aviso').val().trim();
    //     if (texto != "") {
    //         $.ajax({
    //             url: url + "/admin/agendamentos/salvar/aviso",
    //             type: "POST",
    //             data: { aviso: texto },
    //             success: function (response) {
    //                 console.log(response);
    //             }
    //         });
    //     }
    // });

    $('.days-checkbox').on('click', 'input[type="checkbox"]', function () {

        let id = $(this).attr('id');
        let dia = id.replace(/^\d+-/, '');
        let valor = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: url + "/admin/configuracoes/dias-funcionamento",
            type: "GET",
            data: { dia: dia, valor: valor },
            beforeSend: function () {
                $("#overlay").fadeIn(500);

            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.status == 'success') {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: response.message,
                        icon: 'success'
                    });
                } else {
                    Swal.fire({
                        title: 'Erro!',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            complete: function () {
                $("#overlay").fadeOut(500);
            }
        });
    });


    $(document).on("click", ".del-usr", function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const nomeCliente = $(this).data('name');
        const btn = $(this);
        Swal.fire({
            title: 'Confirmar desativação',
            html: `Tem certeza que deseja desativar <b>${nomeCliente}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, desativar!',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(url + '/admin/usuario/set/status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id: id,
                        status: 0
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Falha na requisição: ${error.message}`
                        );
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value && result.value.status === 'success') {
                    btn.closest('tr').find('.status-badge')
                        .text('Inativo')
                        .removeClass('bg-success')
                        .addClass('bg-danger');

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: result.value.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Erro',
                        text: result.value?.message || 'Erro desconhecido',
                        icon: 'error'
                    });
                }
            }
        });
    });

    $('.spinner-text-loading').addClass('d-none');
    $('.texto-aviso-area').removeClass('d-none');

    $("#overlay").fadeOut(500);
});


function editarAgendamento(id) {
    Swal.fire({
        title: 'Editar Agendamento',
        text: 'Funcionalidade em desenvolvimento',
        icon: 'info'
    });
}

function cancelarAgendamento(id) {
    Swal.fire({
        title: 'Cancelar Agendamento',
        text: 'Tem certeza que deseja cancelar este agendamento?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, cancelar!',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Cancelado!',
                'O agendamento foi cancelado com sucesso.',
                'success'
            );
        }
    });


}

