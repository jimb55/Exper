<?php

namespace Main;

use lib\HashRing;
use lib\CacheDbContainer;
use lib\HashOver;

class App
{
    private $ips = [
        "139.129.168.101",
        "139.129.168.102",
        "139.129.168.103",
        "139.129.168.104",
        "139.129.168.105",
    ];
    private $count = [
        "139.129.168.101" => 0,
        "139.129.168.102" => 0,
        "139.129.168.103" => 0,
        "139.129.168.104" => 0,
        "139.129.168.105" => 0,
    ];
    private $cc = [];

    public function main()
    {
        // 机子 IP
        $ips = $this->ips;
        // 统计插入数量
        $count = $this->count;
        // 模拟生成一个 cache 集群
        $this->cc = new CacheDbContainer(new HashOver($ips));
        // 模拟分布 100 个 ID 插入
        for ($id = 0; $id < 100; $id++) {
            // 存入数据，overSet内部用取余法匹配IP对应实例
            $ip = $this->cc->overSet($id);
            // 统计IP插入
            $count[$ip]++;
        }

        // 模拟分布 100 个 ID 取出
        //for ($id = 0; $id < 100; $id++) {
        // 存入数据，overSet内部用取余法匹配IP对应实例
        //$ip = $cc -> overGet($id);
        //}

        // 随便取些数据
        echo "\n" . "----------------------------------------------" . "\n";
        $this->getVal(90);//139.129.168.101
        $this->getVal(16);//139.129.168.102
        $this->getVal(87);//139.129.168.103
        $this->getVal(83);//139.129.168.104
        $this->getVal(54);//139.129.168.105
        // 可以看到分别存在不同机械中

        // 模拟突然之间，"139.129.168.103" 这 台机械坏掉
        $this->cc->bad("139.129.168.103");

        // 再次取上面数据
        $this->getVal(90);//NOT FIND!Re SAVE IN 139.129.168.104
        $this->getVal(16);//NOT FIND!Re SAVE IN 139.129.168.101 (!important)
        $this->getVal(87);//NOT FIND!Re SAVE IN 139.129.168.105
        $this->getVal(83);//NOT FIND!Re SAVE IN 139.129.168.105
        $this->getVal(54);//NOT FIND!Re SAVE IN 139.129.168.104
        // 可以看到坏了一台，全部的机械都要重新存储缓存且再区，事实上缓存并没有失效，仍然保存在目标机械内
        // 但是取的算法 由 id % N 变成 id % (N-1)，所以所有的机械都不能被IP 正确匹配
        // 下面打印出 139.129.168.101 机械内的缓存数据,不难发现除了最后的 key_16 外，所有是N=5时的余数
        $cache = $this->cc->link("139.129.168.101");
        print_r($cache->getAll());

        // 这很可能做成大雪蹦，坏一台，全部缓存不能正确匹配，需要重新录入
        // 绕过缓存层面直接访问 数据库层面，必然造就数据库压力和磁盘IO压力
        // 那么，正确的应该是即使一台机子失效，也要能正确匹配id对应原ip机械

        //--------------------------------------------------------------------------------------------------------------
        //--------------------------------------------------- 分割 ------------------------------------------------------
        //--------------------------------------------------------------------------------------------------------------
        // 一次性hash算法就是解决这个问题,新建cache 群
        $this->cc = new CacheDbContainer(new HashRing($this->ips));
        // 模拟分布 100 个 ID 插入
        for ($id = 0; $id < 100; $id++) {
            // 存入数据，overSet内部用取余法匹配IP对应实例
            $ip = $this->cc->overSet($id);
            // 统计IP插入
            $count[$ip]++;
        }

        // 模拟分布 100 个 ID 取出
        //for ($id = 0; $id < 100; $id++) {
        // 存入数据，overSet内部用取余法匹配IP对应实例
        //$ip = $cc -> overGet($id);
        //}

        // 随便取些数据
        echo "\n" . "----------------------------------------------" . "\n";
        $this->getVal(10);//139.129.168.101
        $this->getVal(36);//139.129.168.102
        $this->getVal(55);//139.129.168.103
        $this->getVal(86);//139.129.168.104
        $this->getVal(98);//139.129.168.105
        // 可以看到分别存在不同机械中

        // 模拟突然之间，"139.129.168.103" 这 台机械坏掉
        $this->cc->bad("139.129.168.103");

        // 再次取上面数据
        $this->getVal(10);//FIND IN CACHE!Re SAVE IN 139.129.168.101
        $this->getVal(36);//FIND IN CACHE!Re SAVE IN 139.129.168.102
        $this->getVal(55);//NOT FIND!Re SAVE IN 139.129.168.102     (!important)
        $this->getVal(86);//FIND IN CACHE!Re SAVE IN 139.129.168.104
        $this->getVal(98);//FIND IN CACHE!Re SAVE IN 139.129.168.105
        // 可以看到坏了一台，只更新那台机子的缓存，其他的id仍然对上原来ip
        $cache = $this->cc->link("139.129.168.102");
        print_r($cache->getAll());

        //end
    }

    /**
     * 模拟从缓存中取值
     */
    private function getVal($id)
    {
        get:{
            if ($val = $this->cc->overGet($id)) {
                echo "id is:$id,This value is taken out of the cache" . $val . "\n";
                echo "\n" . "----------------------------------------------" . "\n";
            } else {
                echo "id is:$id,Not Find in cache" . "\n" . "Save a new values in the cache:" . "\n";
                $this->cc->overSet($id);
                echo "ok! return to {get} in cache" . "\n";
                goto get;
            }
        }
    }


    public function test()
    {
        //示例
        $obj = new HashRing([
            "139.129.168.101",
            "139.129.168.102",
            "139.129.168.103",
            "139.129.168.104",
            "139.129.168.105",
        ]);

        $countArr = [
            "139.129.168.101" => 0,
            "139.129.168.102" => 0,
            "139.129.168.103" => 0,
            "139.129.168.104" => 0,
            "139.129.168.105" => 0,
            "139.129.168.106" => 0
        ];

        $befor = [];
        for ($id = 0; $id < 100; $id++) {
            $ip = $obj->getIp($id);
            $befor[$id] = $ip;
            echo $id . ": at " . $ip . "\n";
            $countArr[$ip]++;
        }


//        $countArr2 = [
//            "139.129.168.101" => 0,
//            "139.129.168.102" => 0,
//            "139.129.168.103" => 0,
//            "139.129.168.104" => 0,
//            "139.129.168.105" => 0,
//            "139.129.168.106" => 0
//        ];
//        $count= 0;
//        $obj -> addIp("139.129.168.106");
//        for ($id = 0; $id < 100; $id++) {
//            $ip = $obj->getIp($id);
//            if($ip !=  $befor[$id]){
//                echo "id: $id  befor :" . $befor[$id] . "  after :".$ip."\n";
//                $count++;
//            }
//            $countArr2[$ip]++;
//        }
//        echo $count."\n";
//        print_r($countArr);
//        print_r($countArr2);

        //print_r($count);

        echo  $obj->getIp(5)."\n"; //139.129.168.101
        echo  $obj->getIp(23)."\n";//139.129.168.102
        echo  $obj->getIp(26)."\n";//139.129.168.103
        echo  $obj->getIp(63)."\n";//139.129.168.104
        echo  $obj->getIp(90)."\n";//139.129.168.105
        $obj -> delIp("139.129.168.103");
        // 因为103机械被移除，此处ID 匹配到了105,其他的ID对应的IP不受影响
        echo  "remove 139.129.168.103----------------------------"."\n";
        echo  $obj->getIp(5)."\n"; //139.129.168.101
        echo  $obj->getIp(23)."\n";//139.129.168.102
        echo  $obj->getIp(26)."\n";//139.129.168.105 (!important)
        echo  $obj->getIp(63)."\n";//139.129.168.104
        echo  $obj->getIp(90)."\n";//139.129.168.105
        echo  "add 139.129.168.103-------------------------------"."\n";
        // 恢复
        $obj -> addIp("139.129.168.103");
        echo  $obj->getIp(5)."\n"; //139.129.168.101
        echo  $obj->getIp(23)."\n";//139.129.168.102
        echo  $obj->getIp(26)."\n";//139.129.168.103 (!important)
        echo  $obj->getIp(63)."\n";//139.129.168.104
        echo  $obj->getIp(90)."\n";//139.129.168.105
        echo  "add 139.129.168.106-------------------------------"."\n";
        $obj -> addIp("139.129.168.106");
        echo  $obj->getIp(5)."\n"; //139.129.168.101
        echo  $obj->getIp(23)."\n";//139.129.168.102
        echo  $obj->getIp(26)."\n";//139.129.168.103
        echo  $obj->getIp(63)."\n";//139.129.168.106 (!important)
        echo  $obj->getIp(90)."\n";//139.129.168.105
        // 添加机械是在两节点之间加入新节点，能影响一到两台机械，具体要看hash在圆环上的值
        // 节点经过虚拟化较为为均匀的分散到圆环上，在HashRing 上是 32
        // 虚拟节点的优点
        // 1 均匀的分布hash 值，因为圆环上若机械数目较少，是很难接近平均分布的
        // 2 若一台机械坏掉，它的负担会分布到旁边那台机械，造成旁边的那台机双倍负载（假若分布平均）
        //   若旁边那台机械也不堪双倍负载（假若分布平均）挂起，会给第一台坏掉机械旁边的旁边造成三倍负载（假若分布平均）
        //   但若用了虚拟的节点，能较为均匀的分布在圆环上，一台坏掉，多台共同承担
        // 3 增加一台也是相同原理

    }

}