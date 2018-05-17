<?php

$mysql_conf = array(
    'host'    => '172.16.47.134:3306',
    'db'      => 'testb',
    'db_user' => 'allperson',
    'db_pwd'  => '123456',
);
$pdo = new PDO("mysql:host=" . $mysql_conf['host'] . ";dbname=" . $mysql_conf['db'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
$pdo->exec("set names 'utf8'");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

while (1){
    try{
        for($i=0;$i<10000;$i++){
            $sql = "INSERT INTO `students` (sname) VALUES ('".substr(uniqid(),6)."')";
            $pdo->exec($sql);
            echo $i.PHP_EOL;
        }
    }catch (Exception $e){
        print_r($e);
        sleep(60);
    }
    sleep(5);
}



// some code

