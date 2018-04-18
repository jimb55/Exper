<?php
require __DIR__."/cla/listab.class.php";
require __DIR__."/cla/listOne.class.php";
require __DIR__."/cla/listTwo.class.php";
// $queue = require __DIR__."/queue.php";

use queue\cla\listOne;
use queue\cla\listTwo;

// 类名和队列名
$c_name = $argv[1];
$q_name = "list_queue_".$c_name;

$redis = new Redis();
$res = $redis->connect('127.0.0.1', 6379);
$redis->auth('jimb55');

while (true){
    try{
        // 执行操作
        shell_exec("echo '\n st $q_name !    ' >> log");
        shell_exec("echo '\n st ".($q_name." redis is block pop ".PHP_EOL)." !    ' >> log");
        $data = $redis->blPop($q_name,0);
        (new $c_name) -> action($data);
        sleep(1);
    }catch (Exception $e){
        print_r($e);
        break;
    }
}