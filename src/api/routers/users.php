<?php

function route($method, $urlData, $formData)
{
    $app = new Application();
    if ($method === 'POST') {
        switch ($urlData[0]) {
            case 'signUp':
                return json_encode($app->signUp($formData));
            case 'signIn':
                return json_encode($app->signIn($formData));
            default:
                header('HTTP/1.0 400 Bad Request');
                echo json_encode(array(
                    'error' => 'Doesnt work'
                ));
        }
    }
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'My Bad Request'
    ));
}