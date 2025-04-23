<?php

function Post(): mixed
{
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        return null;
    }

    $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

    if (strpos($content_type, 'application/json') !== false) {
        $postData = json_decode(file_get_contents('php://input'));
    } else {
        $postData = (object)$_POST;
    }

    return $postData;
}

function Get(): object|null
{
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        return null;
    }

    $queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

    parse_str($queryString, $queryParams);

    return (object)$queryParams;
}

function verify_post_method(): void
{
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        echo json_encode([
            "status" => "error",
            "message" => "invalid_method"
        ]);
        exit;
    }
}
function verify_get_method(): void
{
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        echo json_encode([
            "status" => "error",
            "message" => "invalid_method"
        ]);
    }
    return;
}

function TreatedJson($arr): bool|string
{
    $json = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return json_last_error_msg();
    }
    return $json;
}

function makePostRequest($url, $postData): bool|string
{
    // Initialize cURL session
    $ch = curl_init($url);

    // Encode the data array into a JSON string
    $payload = json_encode($postData);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Attach the payload to the request
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json', // Specify that the request body is JSON
        'Content-Length: ' . strlen($payload) // Specify the length of the request body
    ]);


    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    return $response;
}

function header_json(): void
{
    header('Content-Type: application/json');
}

/**
 * Rodrigo Monteiro Junior (TsukiGva2)
 * sex 23 ago 2024 11:20:12
 *
 * converte uma string no formato "HH:mm:ss" (H:i:s)
 *
 * @param  string $str	String que vai ser convertida
 * @return DateTime     Objeto DateTime do PHP
 **/
function stringToDateTime(string $str)
{
    return DateTime::createFromFormat("H:i:s", $str);
}

/** 
 * Rodrigo Monteiro Junior (TsukiGva2)
 * sex 23 ago 2024 16:40:52 -03
 *
 * converte um objeto DateTime para o formato "H:i:s" ou "HH:mm:ss"
 *
 * @param  DateTime $dt	String que vai ser convertida
 * @return string 	Objeto DateTime do PHP
 **/
function dateTimeToString(DateTime $dt)
{
    return $dt->format("H:i:s");
}

function printData($data, $die = true)
{

    echo "<pre>";
    if (is_object($data) || is_array($data)) {
        print_r($data);
    } else {
        die(PHP_EOL . "END" . PHP_EOL);
    }
}

function isValidInteger($value, $min = PHP_INT_MIN, $max = PHP_INT_MAX)
{
    return is_numeric($value) && intval($value) == $value && $value >= $min && $value <= $max;
}

function logRequestData($data) {
    $logFile = __DIR__ . '/request_log.txt';
    $logData = date('Y-m-d H:i:s') . " - " . json_encode($data) . PHP_EOL; // Formata a data e os dados em JSON
    file_put_contents($logFile, $logData, FILE_APPEND); // Grava os dados no arquivo
}

function getSessionOrCookieValue($key)
{
    // Inicie a sessão se ainda não estiver iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Tenta obter o valor da sessão
    if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    }

    // Tenta obter o valor do cookie se a sessão não estiver definida
    if (isset($_COOKIE[$key])) {
        return $_COOKIE[$key];
    }

    // Retorna null se nenhum valor for encontrado
    return null;
}

function isInteger($value)
{
    // Verifica se é um número inteiro
    if (is_int($value) || (is_string($value) && ctype_digit($value))) {
        return true;
    }
    return false; // Não é um inteiro nem o formato desejado
}

function isEncoded($value)
{
    if (is_string($value) && preg_match('/^[a-zA-Z0-9]+=[a-zA-Z0-9]+$/', $value)) {
        return true;
    }
    return false;

}
function ucfirstCustomStrict($string) {
    return mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
}
function Session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    return (object) $_SESSION;
}
function formatDateToPortuguese($date) {
    $fmt = new IntlDateFormatter(
        'pt_BR',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'America/Sao_Paulo',
        IntlDateFormatter::GREGORIAN
    );
    return $fmt->format(new DateTime($date));
}

function validarCamposObrigatorios($campos)
{
    $camposFaltantes = [];
    foreach ($campos as $campo => $valor) {
        if (empty($valor)) {
            $camposFaltantes[] = $campo;
        }
    }
    if (!empty($camposFaltantes)) {
        throw new Exception("Os seguintes campos são obrigatórios: " . implode(", ", $camposFaltantes) . ".");
    }
}
function getAccessId(): ?int
{
    // Verifica se externalAccess está definido e é diferente de 0
    if (isset($_SESSION['externalAccess']) && $_SESSION['externalAccess'] != 0) {
        return $_SESSION['externalAccess'];
    }

    // Verifica se userId está definido
    if (isset($_SESSION['userId'])) {
        return $_SESSION['userId'];
    }

    // Retorna null caso nenhuma das condições seja atendida
    return null;
}
function isAssociativeArray($array) {
    if (!is_array($array)) {
        return false; // Não é uma array
    }

    // Verifica se há alguma chave que não seja numérica ou não sequencial
    return array_keys($array) !== range(0, count($array) - 1);
}

function bitsToGB($bits) {
    $gb = $bits / (8 * 1024 * 1024 * 1024); // 1 GB = 8 * 1024^3 bits
    return $gb;
}

function bytesToGB($bytes) {
    $gb = $bytes / (1024 * 1024 * 1024); // 1 GB = 1024^3 bytes
    return $gb;
}

function formatarData($data) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $data);
    return $dateTime ? $dateTime->format('d/m/Y') : null;
}
function formatCurrencyToFloat($value) {
    // Remove os pontos (separadores de milhar)
    $value = str_replace('.', '', $value);
    // Substitui a vírgula decimal por um ponto
    $value = str_replace(',', '.', $value);
    // Converte para float
    return (float)$value;
}
function formatFloatToCurrency($value) {
    // Converte o valor para float
    $value = (float)$value;
    // Formata para o padrão desejado (2 casas decimais e separador de milhar com ponto)
    return number_format($value, 2, ',', '.');
}

/**
 * Inclui arquivos CSS na página
 * @param string $dir Diretório base (ex: 'assets/css')
 * @param array $files_list Lista de arquivos (sem extensão)
 */
function include_styles(string $dir, array $files_list): void {
    foreach ($files_list as $file): ?>
        <link href="<?= $dir ?>/<?= $file ?>.css" rel="stylesheet">
    <?php endforeach;
}

/**
 * Inclui arquivos JavaScript na página
 * @param string $dir Diretório base (ex: 'assets/js')
 * @param array $files_list Lista de arquivos (sem extensão)
 * @param bool $defer Adiciona atributo "defer" (opcional)
 */
function include_scripts(string $dir, array $files_list, bool $defer = false): void {
    foreach ($files_list as $file): ?>
        <script src="<?= $dir ?>/<?= $file ?>.js" <?= $defer ? 'defer' : '' ?>></script>
    <?php endforeach;
}


function include_vendor_css(array $files, string $base_path = 'vendor') {
    foreach ($files as $file): ?>
        <link href="<?= SRC ?>/<?= $base_path ?>/<?= $file ?>" rel="stylesheet">
    <?php endforeach;
}

function formatarTelefone($numero) {
    $numero = preg_replace('/\D/', '', $numero);

    if (strlen($numero) > 11) {
        $numero = substr($numero, -11);
    }

    return preg_replace('/(\d{2})(\d)(\d{4})(\d{4})/', '($1) $2 $3-$4', $numero);
}

function current_url() {
    return $_SERVER['REQUEST_URI'];
}

function formatarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) == 11) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    return $cpf;
}

function desformatarCPF($cpf) {
    return preg_replace('/[^0-9]/', '', $cpf);
}

function isValidCPF(string $cpf): bool {
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }

    return true;
}
