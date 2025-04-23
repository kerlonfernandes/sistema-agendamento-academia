<?php

// Para criar uma nova separação de rotar, o nome da variável deve ser o mesmo que o nome do arquivo
$Main = [
    "/" => "MainController@index",
    "/login" => "MainController@login",
    "/logout" => "MainController@logout",
    "/home" => "MainController@home",
    "/auth" => "MainController@authUser",
    "/cadastrar" => "MainController@cadastrar",
    "/register" => "MainController@cadastrar_post",
    "/vizualizar-horarios" => "MainController@vizualizar_horarios",
    "/agendamentos-usuario" => "MainController@vizualizar_horarios_usuario",

];
