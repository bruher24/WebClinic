<?php

function route($method, $params, $formData): array
{
    $app = new Application();
    switch ($params['method']) {
        case 'getDocsTable':
            return $app->getDocsTable($params);
        case 'addVisit':
            return $app->addVisit($params);
        case 'unsetVisit':
            return $app->unsetVisit($params);
        case 'getUserTable':
            return $app->getUserTable();
        default:
            throw new RoutersException();
    }
}

