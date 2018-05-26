<?php
/**
 * 模拟用户不停的请求
 */
$rquest_nums = $_GET["rn"] ? $_GET["rn"] : false;
$starttime = explode(' ',microtime());
function getpdo(){
    $pdo = null;
    $mysql_conf = array(
        'host'    => '172.16.47.134:3306',
        'db'      => 'testb',
        'db_user' => 'allperson',
        'db_pwd'  => '123456',
    );

    try {
        $pdo = new PDO("mysql:host=" . $mysql_conf['host'] . ";dbname=" . $mysql_conf['db'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
    }catch (Exception $e){
        exec("echo '链接失败' >>p.log");
    }
    return $pdo ? $pdo : false;
}

$die_nums = 0;
while (1) {
    try {
        $pdo = getpdo();
        if($pdo === false){
            sleep(3);
            $die_nums ++;
            if($die_nums >= 5){
                exec("echo '终止访问' >>p.log");
                break;
            }
            continue;
        }
        $res1 = $pdo->exec("INSERT INTO `gek_identifi_company` (`name`,`sex`,`birthaddr`) VALUES ('".uniqid()."','9','".time()."')");
        $stmt = $pdo->prepare("select count(*) as count from gek_identifi_company");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)["count"];

        //程序运行时间
        $endtime = explode(' ',microtime());
        $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
        $thistime = round($thistime,3);


        $ist = "\n" . ($res1 == 1 ? "录入成功" : "error") . "------当前录入ID" . $count . "   耗时：" . $thistime . " 秒。" . time();
        exec("echo '" . $ist . "' >>p.log");
    }catch (Exception $e){

        //程序运行时间
        $endtime = explode(' ',microtime());
        $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
        $thistime = round($thistime,3);

        exec("echo '耗时：" . $thistime . " 秒, ==> " . $e . "' >>p.log");
        sleep(60);
    }
    sleep(1);
    $pdo = null;
    $starttime = explode(' ',microtime());

    #发送一次模式
    if($rquest_nums !== false){
        break;
    }
}

// some code
// D:/curl/curl.exe http://172.16.47.129/Exper/mysql/persion.php
