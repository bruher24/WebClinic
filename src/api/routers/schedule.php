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
        case 'makeTimeTable':
            return $app->makeTimeTable();
        case 'toolUsageCount':
            return $app->toolUsageCount($params);
        case 'getToolsUsage':
            return $app->getToolsUsage();
        default:
            throw new RoutersException();
    }
}

