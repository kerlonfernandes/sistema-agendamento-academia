<?php

// Para criar uma nova separação de rotar, o nome da variável deve ser o mesmo que o nome do arquivo
$Agendamentos = [
    "/agendamentos" => "MainController@index",
    "/horarios" => "AgendamentosController@horarios_disponiveis",
    "/agenda" => "AgendamentosController@agenda_horario_post",
    "/api/cancelar-agendamento" => "AgendamentosController@status_agendamento"

];

