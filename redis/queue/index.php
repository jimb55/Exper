<?php



$redis = new Redis();
$res = $redis->connect('127.0.0.1', 6379);
$redis->auth('jimb55');

$q_name = "list_queue_queue\cla\listOne";
$redis -> lPush($q_name,json_encode(["name" => "jimb55","age" => "23","sex" => "man","uid" => uniqid()]));

$q_name = "list_queue_queue\cla\listTwo";
$redis -> lPush($q_name,json_encode(["name" => "WeiNiYa","age" => "18","sex" => "girl","uid" => uniqid()]));