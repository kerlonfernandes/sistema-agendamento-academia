<?php

$Admin = [
    "/admin" => "AdminController@index",
    "/admin/usuario/detalhes/{id}" => "AgendamentosController@detalhes_usuario",
    "/admin/clientes" => "AgendamentosController@clientes_view",
    "/admin/usuarios" => "AdminController@usuarios_view",
    "/admin/agendamentos" => "AgendamentosController@agendamentos_view",
    "/admin/configuracoes" => "AdminController@configuracoes_view",
    "/admin/agendamentos/editar-agendamento" => "AgendamentosController@editar_agendamento",
    "/admin/agendamentos/formulario/status" => "AgendamentosController@formulario_ativo",
    "/admin/agendamentos/limite-agendamentos" => "AgendamentosController@limite_agendamentos",
    "/admin/agendamentos/salvar/aviso" => "AgendamentosController@salvar_aviso",
    "/admin/configuracoes/salvar" => "AdminController@post_configuracoes",
    "/admin/configuracoes/vinculo_formulario" => "AdminController@post_vinculo_formulario",
    "/admin/configuracoes/dias_funcionamento" => "AdminController@post_dias_funcionamento",
    "/admin/configuracoes/remover_vinculo" => "AdminController@remover_vinculo",
    "/admin/configuracoes/dias-funcionamento" => "AdminController@dias_funcionamento",
    "/admin/configuracoes/adicionar-horario" => "AdminController@adicionar_horario",
    "/admin/configuracoes/deletar-horario" => "AdminController@deleta_horario",
    "/admin/configuracoes/editar/usuario" => "AdminController@editar_usuario",
    "/admin/relatorios" => "AdminController@relatorio_view",
    "/admin/relatorio/gerar" => "RelatoriosController@gerar_relatorio",
    "/admin/acompanhamento" => "AdminController@acompanhamento_view",
    "/admin/usuario/set/status" => "AdminController@usuario_set_status",
    "/admin/deletar/agendamento" => "AdminController@deleta_agendamento",
    "/admin/resolver/agendamentos" => "AdminController@resolver_agendamentos",
    "/admin/instrutores" => "AdminController@instrutores",
    "/admin/instrutor/remover/horario" => "AdminController@instrutor_remover_horario",


];