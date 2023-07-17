<?php

$method = $_SERVER['REQUEST_METHOD'];

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

$url = (isset($_GET['args'])) ? $_GET['args'] : '';
$url = rtrim($url, '/');
$urls = explode('/', $url);

$router = $urls[0];
$urlData = array_slice($urls, 1);
include_once 'routers/' . $router . '.php';
return route($method, $urlData, $formData);