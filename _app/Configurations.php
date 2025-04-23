<?php

require_once('app.php');

define("SITE", "https://" . $_SERVER['SERVER_NAME']. '/sistema-agendamento');
require_once('urls.php');

$config_data = file_get_contents(__DIR__."/config.json");

$config_array = json_decode($config_data, true);

if (json_last_error() != JSON_ERROR_NONE) {
    return;
}

date_default_timezone_set('America/Sao_Paulo');


define("CONFIGURATION_DIRECTORY", __DIR__);

$config_data = file_get_contents(__DIR__."/config.json");
$config_array = json_decode($config_data, true);

//MYSQL configurations
define('MYSQL_CONFIG', $config_array["database_homologation"]);

// TimeZone Config
date_default_timezone_set('America/Sao_Paulo');

$dataHoraAtual = new DateTime();
$dataAtual = $dataHoraAtual->format('Y-m-d');
$horaAtual = $dataHoraAtual->format('H:i:s');

define("currentDate", $dataAtual);
define("currentTime", $horaAtual);

$mail_conf = $config_array['email'];

define('MAILUSER', $mail_conf['MAILUSER']);
define('MAILPASS', $mail_conf['MAILPASS']);
define('MAILPORT', $mail_conf['MAILPORT']);
define('MAILHOST', $mail_conf['MAILHOST']);
define('FROM_NAME', $mail_conf['FROM_NAME']);
define('FROM_EMAIL', $mail_conf['FROM_EMAIL']);

// require_once('intern/debug.php');
App::showErrors();
// // App::modoManutencao();