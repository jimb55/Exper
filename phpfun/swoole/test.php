<?php

$serv = new swoole_server('172.16.47.129', 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);

$serv->set(array(
    'worker_num' => 4,
    'daemonize' => true,
    'backlog' => 128,
));

$serv->on('Connect', function(...$arg){
    print_r('Connect');
    print_r($arg);
});
$serv->on('Receive', function(...$arg){
    print_r('Receive');
    print_r($arg);
});
$serv->on('Close', function(...$arg){
    print_r('Close');
    print_r($arg);
});

$serv->start();

// D:/curl/curl.exe http://172.16.47.129/Exper/phpfun/swoole/test.php