<?php

function route($method, $params, $formData)
{
    $app = new Application();
        switch ($params['method']) {
            case 'signUp':
                return $app->signUp($params);
            case 'signIn':
                return json_encode($app->signIn($params));
            default:
                echo json_encode(array(
                    'error' => 'Doesnt work'
                ));
        }

    echo json_encode(array(
        'error' => 'My Bad Request'
    ));
}