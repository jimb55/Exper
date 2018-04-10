<?php
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