<?php

function exception_handler(Throwable $e){
    date_default_timezone_set('Europe/Kirov');
    $time = date('Y-m-d H:i:s');
    $message ="[{$time}] {$e->getMessage()}\n";
    error_log($message, 3, __DIR__ . '/logs/errors.log');
    echo "Упс, кажется что-то пошло не так!";
}

set_exception_handler('exception_handler');