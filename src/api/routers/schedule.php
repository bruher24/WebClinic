<?php

function route($method, $params, $formData): array
{
    $app = new Application();
    switch ($params['method']) {
        case 'getDocsTable':
            return $app->getDocsTable($params);
        case 'setVisit':
            return $app->setVisit($params);
        case 'unsetVisit':
            return $app->unsetVisit();
        case 'getUserTable':
            return $app->getUserTable();
        default:
            return array(
                'error' => 'Doesnt work'
            );
    }
}