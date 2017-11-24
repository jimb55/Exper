<?php
// 机子 IP
$ips = [
    "139.129.168.101",
    "139.129.168.102",
    "139.129.168.103",
    "139.129.168.104",
    "139.129.168.105",
];

// 统计插入数量
$count = [
    "139.129.168.101" => 0,
    "139.129.168.102" => 0,
    "139.129.168.103" => 0,
    "139.129.168.104" => 0,
    "139.129.168.105" => 0,
];

//模拟生成一个 cache 集群
$cc = new CacheDbContainer($ips);

// 模拟分布 100 个 ID 插入
for ($id=0;$id < 100;$id++){
    //根据余数取得 IP
    $ip = $ips[$id%count($ips)];
    //取得 cache 实例
    $cache = $cc -> link($ip);
    $cache -> set("key_".$id,$ip);
    $count[$ip]++;
}

// 模拟分布 100 个 ID 取出
for ($id=0;$id < 100;$id++){
    //根据余数取得 IP
    $ip = $ips[$id%count($ips)];
    //取得 cache 实例
    $cache = $cc -> link($ip);
    echo "ip is:".$cache -> get("key_".$id,"_")."\n";
}



class CacheDbContainer{
    private $cacheDbs = [];
    //构造函数模拟生成IP对应的键值对缓存db
    public function __construct($ips)
    {
        // 生成IP 对应的 内存
        foreach ($ips as $ip){
            $this -> cacheDbs[$ip] = new CacheDb($ip);
        }
    }

    //模拟链接cache服务
    public function link($ip){
        return $this -> cacheDbs[$ip];
    }
}

// cache item
class CacheDb{
    private $map = [];
    private $link = "";
    public function __construct($ip="")
    {
        $link = $ip;
    }
    public function set($key,$val){
        $this -> map[$key] = $val;
    }
    public function get($key){
        return $this -> map[$key];
    }
}

print_r($count);

return;
/**
 * 对服务器进行一致性hash分布算法
 */
class HashRing
{
    private $servers = array();
    private $nodeList = array();
    private $nodeHashList = array();
    private $nodeTotalNum = 0;
    private $virtualNodeNum = 32;
    private $keyHash = '';

    public function __construct($servers)
    {
        $this->servers = $servers;
        foreach ($servers as $server) {
            for ($i = 0; $i < $this->virtualNodeNum; $i++) {
                $this->nodeList[sprintf("%u", crc32($server.'-'.$i))] = array($server, $i);
            }
        }
        ksort($this->nodeList);
        $this->nodeHashList = array_keys($this->nodeList);
    }

    private function getNodeIndex($key)
    {
        $this->keyHash = sprintf("%u", crc32($key));
        if ($this->keyHash > end($this->nodeHashList)) {
            $this->keyHash = $this->keyHash % end($this->nodeHashList);
        }
        if ($this->keyHash <= reset($this->nodeHashList)) {
            return 0;
        }
        $this->nodeTotalNum = count($this->nodeHashList);
        return $this->binaryChopIndex(0, $this->nodeTotalNum);
    }

    private function binaryChopIndex($l=0, $r=0)
    {
        if ($l < $r) {
            $avg = intval(($l+$r) / 2);
            if ($this->nodeHashList[$avg] == $this->keyHash) {
                return $avg;
            } elseif ($this->keyHash < $this->nodeHashList[$avg] && ($avg > 0)) {
                return $this->binaryChopIndex($l, $avg-1);
            } else {
                return $this->binaryChopIndex($avg+1, $r);
            }
        } else {
            return $l;
        }
    }

    public function getServersByKey($key, $num=1)
    {
        $index = $this->getNodeIndex($key);
        $server = $this->nodeList[$this->nodeHashList[$index]];
        if ($num == 1) {
            return $server[0];
        }
        if ($num >= count($this->servers)) {
            $num = count($this->servers);
        }
        $result = array($server[0]);
        for ($i=$index+1; true; $i++) {
            if ($i >= $this->nodeTotalNum) {
                $i = 0;
            }
            $nextServer = $this->nodeList[$this->nodeHashList[$i]];
            if (!in_array($nextServer[0], $result)) {
                $result[] = $nextServer[0];
            }
            if (count($result) == $num) {
                break;
            }
        }
        return $result;
    }

    /**
     * 虚拟节点
     */
    public static function dummy(array $arr,$num){
        $res = [];
        foreach ($arr as $item){
            for ($i = 0 ; $i < $num ;$i++){
                $res[] = "$item#$i";
            }
        }
        return $res;
    }
}


//示例
$obj = new HashRing(HashRing::dummy([
    "139.129.168.101",
    "139.129.168.102",
    "139.129.168.103",
    "139.129.168.104",
    "139.129.168.105",
],100));

$count = [
    "139.129.168.101" => 0,
    "139.129.168.102" => 0,
    "139.129.168.103" => 0,
    "139.129.168.104" => 0,
    "139.129.168.105" => 0,
];

for ($i=100001;$i < 150000;$i++){
    $key = $i;
    $server = strstr($servers = $obj->getServersByKey($key), '#', TRUE);
    echo $key.": at " . $server . "\n";
    $count[$server] += 1;
}

print_r($count);


/**
Array
(
[139.129.168.101] => 8745
[139.129.168.102] => 10231
[139.129.168.103] => 12502
[139.129.168.104] => 12148
[139.129.168.105] => 6373
)



Array
(
[139.129.168.101] => 8714
[139.129.168.102] => 9695
[139.129.168.103] => 10548
[139.129.168.104] => 10717
[139.129.168.105] => 10325
)

 */