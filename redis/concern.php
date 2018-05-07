<?php

require "data/getData.php";

$redis = new Redis();
$res = $redis->connect('127.0.0.1', 6379);
$redis->auth('jimb55');

/**
 * hash
 *
 *
 * 场景一 找出交集或差集，如交集找出共同的关注的人
 */


$laowang = ["Aabbye","Aaron","Abagael","Abagail","Abbe","Abbe"];
$lisi = ["Aabbye","Abbott","Lane","Aaron","Abagael","Abdul","Abelard","Abiba"];
$zhangshan = ["Lane","Aaron","Abagael","Abdul","Abelard","Abiba"];
$baibai = ["Lane","Aaron","Abelard","Abiba"];

$redis -> sAddArray("set_user_concern_laowang",$laowang);
$redis -> sAddArray("set_user_concern_lisi",$lisi);
$redis -> sAddArray("set_user_concern_zhangshan",$zhangshan);
$redis -> sAddArray("set_user_concern_baibai",$baibai);

print_r($redis->sMembers("set_user_concern_laowang"));
print_r($redis->sMembers("set_user_concern_lisi"));
print_r($redis->sMembers("set_user_concern_zhangshan"));
print_r($redis->sMembers("set_user_concern_baibai"));

$l = call_user_func_array(array($redis, 'sInter'), array(
    "set_user_concern_laowang",
    "set_user_concern_lisi",
    "set_user_concern_zhangshan",
    "set_user_concern_baibai"
));
print_r($l);
//输出共同好友为 Aaron

//D:/curl/curl.exe http://172.16.47.129/Exper/redis/concern.php

