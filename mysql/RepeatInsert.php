<?php
$mysql_conf = array(
    'host'    => 'localhost:3306',
    'db'      => 'test',
    'db_user' => 'root',
    'db_pwd'  => '_Jimb55!',
);

try {
    $pdo = new PDO("mysql:host=" . $mysql_conf['host'] . ";dbname=" . $mysql_conf['db'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
}catch (Exception $e){
    print_r($e);
}


$res = $pdo->query("select count(*),sum(time) from pierce left join gek_user as p on pierce.id = p.id  where pierce.name like '%".substr(uniqid(),1,1)."1%'");
$res = $pdo->query("select count(*),sum(time) from pierce left join gek_user as p on pierce.id = p.id  where pierce.name like '%".substr(uniqid(),1,2)."2%'");
$res = $pdo->query("select count(*),sum(time) from pierce left join gek_user as p on pierce.id = p.id  where pierce.name like '%".substr(uniqid(),1,3)."3%'");
$res = $pdo->query("select sleep(1),123;");
print_r($res);
exit();
$pdo->exec("set names 'utf8'");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

while (1){
    try{
        for($i=0;$i<10000;$i++){
            $sql = "INSERT INTO `pierce` (`name`,`key`,`time`) VALUES ('".uniqid()."','9','".time()."')";
            $pdo->query($sql);
            echo $i.PHP_EOL;
        }
    }catch (Exception $e){
        print_r($e);
        sleep(60);
    }
    sleep(5);
}



// some code

