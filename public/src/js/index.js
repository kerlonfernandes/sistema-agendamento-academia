$(document).ready(function () {
  const url = $("#url").val();



  if ($('.send_form').length > 0) {
    let formDataRaw = $('.send_form').val();
    let dados = JSON.parse(formDataRaw);

    let formData = new FormData();

    formData.append('is_multiple', dados.is_multiple);
    formData.append('agendamentos', dados.agendamentos);

    Object.entries(dados).forEach(([key, value]) => {
      if (key.startsWith('horario_')) {
        formData.append(key, value);
      }
    });

    Swal.fire({
      title: 'Enviando agendamento',
      html: 'Estamos processando seu agendamento...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();

        $.ajax({
          url: './agenda',
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            let data = typeof response === 'string' ? JSON.parse(response) : response;

            let htmlMensagem = '';
            data.resultados.forEach(function (resultado) {
              const icon = resultado.status === 'success' ? '✅' : '❌';
              const color = resultado.status === 'success' ? '#198754' : '#dc3545';

              htmlMensagem += `
                <div style="margin-bottom: 10px; padding: 8px; border-bottom: 1px solid #eee; text-align: left;">
                  ${icon} <strong>${resultado.dia}</strong><br>
                  <span style="margin-left: 25px">${resultado.horario}</span><br>
                  <span style="margin-left: 25px; color: ${color}">${resultado.mensagem}</span>
                </div>
              `;
            });

            Swal.fire({
              title: 'Resultado dos Agendamentos',
              html: htmlMensagem,
              icon: data.status === 'success' ? 'success' : 'warning',
              confirmButtonText: 'OK',
              width: '500px'
            }).then(() => {
              window.location.reload();
            });
          },
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Erro',
              text: 'Ocorreu um erro ao processar os agendamentos.'
            });
          }
        });
      }
    });
  }


  if ($(".operarios-table")) {
    $(".operarios-table").removeClass("d-none");
    $(".op-loads").addClass("d-none");
  }

  $(document).ready(function () {
    const diasNomes = {
      'todos': 'todos-dias',
      'segunda': 'segunda-feira',
      'terca': 'terca-feira',
      'quarta': 'quarta-feira',
      'quinta': 'quinta-feira',
      'sexta': 'sexta-feira',
      'sabado': 'sabado',
      'domingo': 'domingo'
    };

    let estadoAgendamento = {
      diaSelecionado: 'todos-dias',
      horarioSelecionado: null
    };

    function carregarHorarios(dia, horarioSelecionado = null) {
      const diaTab = Object.keys(diasNomes).find(key => diasNomes[key] === dia) || dia;
      const container = $(`#horarios-${diaTab}`);

      container.html('<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>');

      $.ajax({
        url: './horarios',
        method: 'GET',
        data: {
          dia: dia,
          horario: horarioSelecionado
        },
        dataType: 'html',
        success: function (response) {
          try {
            container.empty();

            container.html(response);

            if (horarioSelecionado) {
              const radio = $(`input[name="horario"][value="${horarioSelecionado}"]`);
              if (radio.length) {
                radio.prop('checked', true);
                radio.closest('.card').addClass('horario-ativo');
              }
            }
          } catch (error) {
            console.error('Erro ao processar resposta:', error);
            container.html('<div class="col-12 text-center text-danger">Erro ao processar horários. Tente novamente.</div>');
          }
        },
        error: function (xhr, status, error) {
          console.error('Erro ao carregar horários:', error);
          console.error('Status:', status);
          console.error('Resposta:', xhr.responseText);

          container.html('<div class="col-12 text-center text-danger">Erro ao carregar horários. Tente novamente.</div>');

          Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Erro ao carregar horários. Tente novamente.'
          });
        }
      });
    }

    carregarHorarios('todos-dias');

    $('.nav-link').click(function (e) {
      e.preventDefault();

      if (!$(this).length || !$(this).attr('id')) {
        return;
      }

      const diaTabId = $(this).attr('id');
      const diaTab = diaTabId.replace('-tab', '');
      const dia = diasNomes[diaTab] || diaTab;

      if (estadoAgendamento.diaSelecionado !== dia) {
        estadoAgendamento.horarioSelecionado = null;
      }

      estadoAgendamento.diaSelecionado = dia;

      $('.nav-link').removeClass('active');
      $(this).addClass('active');

      $('.tab-pane').removeClass('show active');
      const tabPane = $(`#${dia}`);

      if (tabPane.length) {
        tabPane.addClass('show active');
        carregarHorarios(dia, estadoAgendamento.horarioSelecionado);
      }
    });

    $(document).on('change', 'input[name="horario"]', function () {
      const horarioSelecionado = $(this).val();

      estadoAgendamento.horarioSelecionado = horarioSelecionado;

      $('.card').removeClass('horario-ativo');

      $(this).closest('.card').addClass('horario-ativo');

    });

    $('#agendamentoForm').submit(function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      const tabAtual = $('.nav-link.active').attr('id');

      const dadosFormulario = {
        nome: $('#nome').val(),
        email: $('#email').val(),
        telefone: $('#telefone').val(),
        vinculo: $('#vinculo').val(),
        restricoes: $('#restricoes').val()
      };

      if (tabAtual === 'todos-tab') {
        const agendamentosSelecionados = [];

        ['segunda', 'terca', 'quarta', 'quinta', 'sexta'].forEach(dia => {
          const radioSelecionado = $(`input[name="horario_${dia}"]:checked`);

          if (radioSelecionado.length > 0) {
            agendamentosSelecionados.push({
              dia: radioSelecionado.data('dia'),
              horario: radioSelecionado.val(),
              ...dadosFormulario
            });
          }
        });

        console.log('Agendamentos múltiplos:', agendamentosSelecionados);

        if (agendamentosSelecionados.length === 0) {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione pelo menos um horário para continuar!'
          });
          return false;
        }

        formData.append('agendamentos', JSON.stringify(agendamentosSelecionados));
        formData.append('is_multiple', 'true');
      } else {
        const radioSelecionado = $('input[name="horario"]:checked');

        if (radioSelecionado.length === 0) {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione um horário para continuar!'
          });
          return false;
        }

        const agendamentoUnico = {
          dia: estadoAgendamento.diaSelecionado,
          horario: radioSelecionado.val(),
          ...dadosFormulario
        };

        // console.log('Agendamento único:', agendamentoUnico);

        formData.append('agendamentos', JSON.stringify([agendamentoUnico]));
        formData.append('is_multiple', 'false');
      }

      Swal.fire({
        title: 'Enviando agendamento',
        html: 'Estamos processando seu agendamento...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();

          $.ajax({
            url: './agenda',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {

              let data = response;
              if (typeof response === 'string') {
                data = JSON.parse(response);
              }
              if (data.cause && data.cause == 'not_logged_in') {
                let message = `
                  <div style="margin-bottom: 10px; padding: 8px; border-bottom: 1px solid #eee; text-align: left;">
                    ⚠️ <strong>Você não está logado!</strong>
                    <br>
                    <span class="text-center" style="margin-left: 25px;">
                      <a class="gotologin" style="color: #0d6efd; text-decoration: none; cursor: pointer;">Fazer login</a> | 
                      <a class="gotocad" style="color: #0d6efd; text-decoration: none; cursor: pointer;">Criar conta</a>
                    </span>
                  </div>
                `;

                Swal.fire({
                  title: 'Aviso!',
                  html: message,
                  icon: data.status === 'success' ? 'success' : 'warning',
                  confirmButtonText: 'OK',
                  width: '500px'
                })

                setTimeout(() => {
                  const loginBtn = document.querySelector(".gotologin");
                  const cadBtn = document.querySelector(".gotocad");

                  if (loginBtn) {
                    loginBtn.addEventListener("click", () => {
                      document.querySelector(".container").classList.add('body-slide-left');
                      setTimeout(() => {
                        window.location.href = `${url}/login?redirect_back=${url}&keep=${data._d}`;
                      }, 1000); // espera a animação terminar
                    });
                  }

                  if (cadBtn) {
                    cadBtn.addEventListener("click", () => {
                      document.body.classList.add('body-slide-left');
                      setTimeout(() => {
                        window.location.href = `${url}/cadastrar?keep=${data._d}`;
                      }, 400);
                    });
                  }
                }, 100); // pequena espera para o DOM montar os elementos

                return;
              }


              let htmlMensagem = '';
              data.resultados.forEach(function (resultado) {
                const icon = resultado.status === 'success' ?
                  '✅' : '❌';
                const color = resultado.status === 'success' ?
                  '#198754' : '#dc3545';

                htmlMensagem += `
                                <div style="margin-bottom: 10px; padding: 8px; border-bottom: 1px solid #eee; text-align: left;">
                                    ${icon} <strong>${resultado.dia}</strong> 
                                    <br>
                                    <span style="margin-left: 25px">${resultado.horario}</span>
                                    <br>
                                    <span style="margin-left: 25px; color: ${color}">${resultado.mensagem}</span>
                                </div>
                            `;
              });

              Swal.fire({
                title: 'Resultado dos Agendamentos',
                html: htmlMensagem,
                icon: data.status === 'success' ? 'success' : 'warning',
                confirmButtonText: 'OK',
                width: '500px'
              }).then(() => {
                window.location.reload();
              });
            },
            error: function (xhr, status, error) {
              Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Ocorreu um erro ao processar os agendamentos.'
              });
            }
          });
        }
      });
    });

    const style = document.createElement('style');
    style.innerHTML = `
        .horario-ativo {
            background-color: #e6f2ff;
            border-radius: 5px;
            border-left: 3px solid #0d6efd;
            transition: all 0.3s ease;
        }
        .horario-ativo .form-check-label {
            font-weight: bold;
            color: #0d6efd;
        }
        
        /* Efeitos de transição */
        .fade-out {
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.2s ease-out;
        }
        
        .fade-in {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease-in;
        }
        
        /* Efeito na tab ativa */
        .nav-link {
            transition: all 0.2s ease;
        }
        
        .nav-link.active {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Efeito nos cards de horário */
        .time-slot {
            transition: all 0.3s ease;
        }
        
        .time-slot:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    `;
    document.head.appendChild(style);
  });

  const detalhesModal = $('#detalhesModal');

  $('.btn-detalhes').click(function () {
    const agendamentoId = $(this).data('id');
    $('.btn-cancelar').attr('data-id', agendamentoId)
    const modal = new bootstrap.Modal(detalhesModal);

    $('#detalhesModal').find('.btn-cancelar').data('id', agendamentoId);

    $('#modalDetalhesContent').html(`
      <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Carregando...</span>
          </div>
          <p class="mt-2">Carregando detalhes...</p>
      </div>
  `);

    modal.show();

    $.ajax({
      url: url + '/agendamentos-usuario',
      type: 'GET',
      data: {
        id: agendamentoId
      }
    })
      .done(function (response) {
        $('#modalDetalhesContent').html(response);
      })
      .fail(function () {
        $('#modalDetalhesContent').html(`
          <div class="alert alert-danger">
              Ocorreu um erro ao carregar os detalhes. Por favor, tente novamente.
          </div>
      `);
      });
  });

  $('.btn-cancelar').click(function () {
    const agendamentoId = $(this).data('id');
    const botao = $(this);
    const linha = botao.closest('tr');

    bootstrap.Modal.getInstance(detalhesModal)?.hide();

    const cancelModal = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-danger',
        cancelButton: 'btn btn-secondary'
      },
      buttonsStyling: false
    });

    cancelModal.fire({
      title: 'Cancelar Agendamento',
      html: `
          <div class="text-start">
              <p>Tem certeza que deseja cancelar este agendamento?</p>
              <div class="form-group mt-3">
                  <label for="motivoCancelamento" class="form-label">Motivo:</label>
                  <textarea id="motivoCancelamento" class="form-control" rows="3" 
                      placeholder="Digite o motivo (mínimo 10 caracteres)" 
                      style="width:100%; min-height:100px;"></textarea>
              </div>
          </div>
      `,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Confirmar Cancelamento',
      cancelButtonText: 'Voltar',
      focusConfirm: false,
      allowOutsideClick: false,
      preConfirm: () => {
        const motivo = $('#motivoCancelamento').val().trim();

        if (!motivo) {
          Swal.showValidationMessage('Por favor, informe o motivo');
          return false;
        }

        if (motivo.length < 10) {
          Swal.showValidationMessage('O motivo deve ter pelo menos 10 caracteres');
          return false;
        }

        return motivo;
      },
      didOpen: () => {
        setTimeout(() => {
          $('#motivoCancelamento').trigger('focus');
        }, 100);
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const motivo = result.value;

        cancelModal.fire({
          title: 'Processando...',
          html: 'Cancelando seu agendamento',
          allowOutsideClick: false,
          didOpen: () => {
            cancelModal.showLoading();
            $.ajax({
              url: url + '/api/cancelar-agendamento',
              type: 'POST',
              data: {
                id: agendamentoId,
                status: 'cancelado',
                motivo: motivo
              },
              dataType: 'json'
            })
              .done(function (response) {
                Swal.close();

                if (response.status === 'success') {
                  const agendamentoRow = $(`[data-id="${agendamentoId}"]`).closest('tr');

                  agendamentoRow.find('.badge')
                    .removeClass('bg-success bg-warning bg-primary')
                    .addClass('bg-danger')
                    .text('cancelado');

                  agendamentoRow.find('.btn-cancelar').prop('disabled', true);
                  agendamentoRow.find('.btn-detalhes').addClass('disabled');

                  agendamentoRow.css('background-color', '#ffebee')
                    .animate({
                      backgroundColor: '#ffffff'
                    }, 1500);

                  Swal.fire({
                    title: '✔️ ' + response.message,
                    text: 'O agendamento foi marcado como cancelado',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                      agendamentoRow.highlight(2000);
                    }
                  });

                } else {
                  const isWarning = response.code === 400;

                  Swal.fire({
                    title: isWarning ? '⚠️ Atenção' : '❌ Erro',
                    text: response.message,
                    icon: isWarning ? 'warning' : 'error',
                    showConfirmButton: true,
                    showCancelButton: isWarning,
                    cancelButtonText: 'Corrigir',
                    confirmButtonText: 'Entendi'
                  }).then((result) => {
                    if (isWarning && result.isDismissed) {
                      showCancelModal(agendamentoId);
                    }
                  });
                }
              })
              .fail(function (error) {
                Swal.fire({
                  title: '⛔ Erro de Conexão',
                  text: 'Não foi possível comunicar com o servidor',
                  icon: 'error',
                  footer: '<small>Tente novamente em alguns instantes</small>',
                  showConfirmButton: true,
                  showCancelButton: true,
                  cancelButtonText: 'Fechar',
                  confirmButtonText: 'Tentar Novamente'
                }).then((result) => {
                  if (result.isConfirmed) {
                    setTimeout(() => {
                      $('.btn-cancelar[data-id="' + agendamentoId + '"]').click();
                    }, 500);
                  }
                });
              });

            $.fn.highlight = function (duration) {
              this.css('background-color', '#FFF9C4');
              this.animate({
                backgroundColor: '#ffffff'
              }, duration);
              return this;
            };

            function showCancelModal(id) {
              const btn = $(`.btn-cancelar[data-id="${id}"]`);
              if (btn.length) {
                btn.click();
              }
            }
          }
        });
      }
    });
  });



  $("#overlay").fadeOut(500);
});
