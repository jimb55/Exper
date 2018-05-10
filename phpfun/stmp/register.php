<?php

namespace Stmp;

require_once 'Mail.class.php';
require_once 'vendor/autoload.php';

$to = $_POST["email"];
$username = $_POST["username"];
$password = $_POST["password"];
// 邮件内容
$message = "Hello! This is a simple email message.".$username."--->".$password;
// 实例化 Mail
$mailer = new \Stmp\Mail();
// 邮件标题
$title = '收到吗，收到请回复！！！';

// 发送QQ邮件
if($mailer->send($to, $title, $message)){
    echo "邮件发送成功";
}else{
    echo "邮件发送失败";
}
?>