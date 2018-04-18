<?php

require "data/getData.php";

$redis = new Redis();
$res = $redis->connect('127.0.0.1', 6379);
$redis->auth('jimb55');

/**
 * string 原子计数器
 *
 * 场景二，（用浏览访问）限制用户访问频率,当然这是可以破解的，禁止cookie就行，用IP 也不靠谱，只能一定程度上限流
 */

$cookieTime = 10;

//复原SESSION ID
if(key_exists("session_id",$_COOKIE)){
    session_id($_COOKIE["session_id"]);
    session_start();
}else{
    session_start();
    setcookie('session_id', session_id(),time() + $cookieTime);
}

$yuqi = "";
if(key_exists("test",$_COOKIE)){
    print_r("cookie里面有值，值为：".$_COOKIE["test"]);
    $yuqi = $_COOKIE["test"];
//    print_r(" -- PHPSESSID里面有值，值为：".$_COOKIE["PHPSESSID"]);
}else{
    $yuqi = 'test_'.uniqid();
    setcookie('test', $yuqi,time() + $cookieTime);
    print_r("cookie里面没有值");
}

echo "<br />";echo "<br />";echo "<br />";echo "<br />";

print_r($_SESSION);

echo "<br />";echo "<br />";

print_r($_COOKIE);

echo "<br />";echo "<br />";

if(key_exists("test2",$_SESSION)){
    print_r("session里面有值，值为：".$_SESSION["test2"]);
}else{
    $_SESSION["test2"] = "天天 Exper";
    print_r("session里面没有值");
}

$redis ->incr("string_speed_".$yuqi);
$num = $redis -> get("string_speed_".$yuqi);
if($num == 1){
    $redis -> expire("string_speed_".$yuqi,10);
}else if($num >2){
    echo "<br />十秒内最多访问2次";exit();
}
echo "<br />访问通过!!!";



//http://172.16.47.129/Exper/redis/speedLimit.php