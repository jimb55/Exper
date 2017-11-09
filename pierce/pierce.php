<?php

// 一百万人 同时访问开始了
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('jimb55');

// 无线循环，只有在缓存或数据库中取到值才会跳出
while(true){
    // 在缓存判断是否存在统计只
    // 有就从缓存中取
    // 没有就从数据库中取
    if ($redis->exists("pierce_count")) {
        $count = $redis->get("pierce_count");
        $type = "cache";
        break;
    } else {
        // 防止多并发情况下的缓存穿透，只能允许一人通过
        $isExist = $redis->setNX("lock_pierce_count", 1);
        if ($isExist) {
            // 防止请求死掉，变成死锁
            // 若5秒后还不能完成访问数据库任务,便直接解锁
            $redis->expire("lock_pierce_count", 5);

            try {
                $pdo = new PDO("mysql:host=127.0.0.1;dbname=test", "root", "111111");
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }

            $pdo->query('set names utf8;');
            $res = $pdo->query("select count(1) as count from pierce")->fetchAll(PDO::FETCH_CLASS)[0];
            sleep(1);

            $count = $res->count;
            $redis->setex("pierce_count", 1, $count);
            // 解锁
            $redis->del("lock_pierce_count");
            $type = "db";
            break;
        }else{
            // 同时并发的其他人只能处于等待一段时间再次访问流程
            usleep(100000);
            //...
            // 当然，期间还能做许多操作，如超时,从备份缓存中取等
            //...
            continue;
        }
    }
}
echo "总数：$count($type)";