<?php

function route($method, $params, $formData)
{
    $app = new Application();
    switch ($params['method']) {
        case 'register':
            return $app->register($params);
        case 'login':
            return $app->login($params);
        case 'logout':
            return $app->logout($params);
        default:
            return array(
                'error' => 'Doesnt work'
            );
    }
}