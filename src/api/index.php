<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: X-PINGOTHER, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();

//if ($_POST) {
//    $data['method'] = $_POST['method'] ?? null;
//    $data['surname'] = $_POST['surname'] ?? null;
//    $data['name'] = $_POST['name'] ?? null;
//    $data['firstname'] = $_POST['firstname'] ?? null;
//    $data['email'] = $_POST['email'] ?? null;
//    $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT) ?? null;
//    $data['role'] = $_POST['role'] ?? null;
//    $data['speciality'] = $_POST['speciality'] ?? null;
//} else {
//    $data = null;
//}

function router($data)
{
    global $app;
    if (isset($data['method'])) {
        switch ($data['method']) {
            case 'singUp':
                return $app->signUp($data);
            case 'singIn':
                return $app->signIn();
            case 'logout':
                return $app->logout();
            default:
                return 'Invalid method.'; //TODO: throw exception & add exception handler
        }
    }else {
        return 'Data is invalid.'; //TODO: throw exception & add exception handler
    }
}

function answer($data): array {
    if ($data) {
        return array(
            'result' => 'ok',
            'data' => $data
        );
    }
    return array(
        'result' => 'error'
    );
}

echo(json_encode(answer(router($_POST))));
