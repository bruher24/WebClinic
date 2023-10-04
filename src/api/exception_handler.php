<?php

function exception_handler(Throwable $e)
{
    date_default_timezone_set('Europe/Samara');
    $time = date('Y-m-d H:i:s');
    $message = "[{$time}] {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}\n";
    error_log($message, 3, $_SERVER['DOCUMENT_ROOT'] . '/logs/errors.log');
    echo "Упс, кажется что-то пошло не так!";
}

set_exception_handler('exception_handler');