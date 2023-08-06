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
                return json_encode(array(
                    'error' => 'Doesnt work'
                ));
        }
}