<?php

require "data/getData.php";

$redis = new Redis();
$res = $redis->connect('127.0.0.1', 6379);
$redis->auth('jimb55');

/**
 * 场景二，（用浏览访问）限制用户访问频率,当然这是可以破解的，禁止cookie就行，用IP 也不靠谱，只能一定程度上限流
 */

$cookieTime = 3600;

//复原SESSION ID
if(key_exists("session_id",$_COOKIE)){
    session_id($_COOKIE["session_id"]);
    session_start();
}else{
    session_start();
    setcookie('session_id', session_id(),time() + $cookieTime);
}


if(key_exists("test",$_COOKIE)){
    print_r("cookie里面有值，值为：".$_COOKIE["test"]);
//    print_r(" -- PHPSESSID里面有值，值为：".$_COOKIE["PHPSESSID"]);
}else{
    setcookie('test', "123",time() + $cookieTime);
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


//
////Redis键值
//$keyName = Yii::app()->request->userHostAddress . '-' . $apiKey;
////初始化接口访问频次
//if (Yii::app()->redis->get($apiRunCountKey) === false) {
//    Yii::app()->redis->setex(
//        $apiRunCountKey,
//        self::$RateLimitTime,
//        self::$RateLimitCount
//    );
//}
////获取当前可执行的频次
//$currentApiCount = Yii::app()->redis->decr($apiRunCountKey);
//
//if ($currentApiCount < 0) {
//    Yii::log($apiRunCountKey, 'info', 'webadmin.cms.api.rate');
//    return false;
//}
//return true;
