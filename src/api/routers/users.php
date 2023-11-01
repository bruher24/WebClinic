<?php

function route($method, $params, $formData): array
{
    $app = new Application();
    switch ($params['method']) {
        case 'register':
            return $app->register($params);
        case 'login':
            return $app->login($params);
        case 'logout':
            return $app->logout();
        case 'getUserData':
            return $app->getUserData();
        case 'addDocData':
            return $app->addDocData($params);
        default:
            throw new RoutersException();
    }
}