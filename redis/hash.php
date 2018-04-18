<?php

require "data/getData.php";

$redis = new Redis();
$res = $redis->connect('127.0.0.1', 6379);
$redis->auth('jimb55');

/**
 * hash
 *
 *
 * 场景一，比如要缓存用户信息，或产品信息啊，公司信息啊，订单信息啊之类的，可以用hash
 * 若果用其他，如string 得转换json，且不便于修改某个单一字段
 */


//某操作要查找 ID 为 13 的 user 信息
$id = 11;
$data = [];

if ($redis->exists("hash_user_$id")) {
    $data = $redis->hMget("hash_user_$id", array("id", "name", "age", "sex", "work", "qq", "mobile"));
} else {
    $data = getData("users", 1);
    foreach ($data as $item) {
        if ($item["id"] == $id) {
            $redis->hMset("hash_user_$id", $item);
            $data = $item;
            break;
        }
    }
}

print_r($data);

// hash 的作用便与查找之外更是能便于修改
// 例如 我要改变 $ID 的姓名
var_dump($redis->hSet("hash_user_$id", "name","jimb55"));
if($redis->hSet("hash_user_$id", "name","jimb55") !== false){
    $data = $redis->hMget("hash_user_$id", array("id", "name", "age", "sex", "work", "qq", "mobile"));
    print_r($data);
}

//D:/curl/curl.exe http://172.16.47.129/Exper/redis/hash.php

