<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: X-PINGOTHER, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require_once __DIR__ . '/../vendor/autoload.php';

$method = $_SERVER['REQUEST_METHOD'];

session_start();
function getFormData($method): array
{
    //GET, POST
    if ($method === 'GET') return $_GET;
    if ($method === 'POST') return $_POST;

    // PUT, PATCH, DELETE
    $data = array();
    $exploded = explode('&', file_get_contents('php://input'));

    foreach ($exploded as $pair) {
        $item = explode('=', $pair);
        if (count($item) == 2) {
            $data[urldecode($item[0])] = urldecode($item[1]);
        }
    }
    return $data;
}

$formData = getFormData($method);

$url = $_SERVER['REQUEST_URI'];
$url = trim($url, '/');
$urls = explode('/', $url);

$router = $urls[0];

$urlData = array_slice($urls, 1);
$urlData[array_key_last($urlData)] = ltrim($urlData[array_key_last($urlData)], '?');
if (strstr($urlData[array_key_last($urlData)], '&')) {
    $urlData[array_key_last($urlData)] = explode('&', $urlData[array_key_last($urlData)]);
}

$qArr = $urlData[array_key_last($urlData)];

$params = ['method' => $urlData[array_key_first($urlData)]];

if (is_array($qArr)) {
    foreach ($qArr as $item) {
        $params[explode('=', $item)[0]] = explode('=', $item)[1];
    }
}else {
    $params[explode('=', $qArr)[0]] = explode('=', $qArr)[1];
}

include_once 'routers/' . $router . '.php';

echo json_encode(route($method, $params, $formData));