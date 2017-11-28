<?php
/**
 * Created by PhpStorm.
 * User: Jimb
 * Date: 2017/11/28
 * Time: 10:15
 */
namespace lib;

/**
 * 对服务器进行一致性hash分布算法
 */
class HashRing implements \lib\Algorithm
{
    //虚拟节点数
    public $virtualCounts;
    //虚拟节点集合
    public $virtualIps = array();
    //实际节点
    public $ips = array();
    //实际节点数
    private $ipCount = 0;
    //排序
    private $needSort = false;

    /**
     * HashRing constructor.
     * @param array $ips
     * @param int $_virtualCounts
     */
    function __construct(array $ips, $_virtualCounts = 64)
    {
        $this->virtualCounts = $_virtualCounts;
        $this->setIpList($ips);
    }

    /**
     * 根据ID 取得对应 IP
     *
     * @param $id
     * @return mixed
     */
    public function getIp($id)
    {
        // TODO: Implement getIp() method.
        $hashIp = $this->hashIp($id);
        if (!$this->needSort) {
            $this->sortItem();
        }
        if (!$this->ips) {
            return false;
        }
        //遍历对比
        foreach ($this->virtualIps as $k => $val) {
            if ($hashIp <= $k) {
                return $val;
            }
        }
        return end($this->virtualIps);
    }

    /**
     * 设置 Ip List
     *
     * @param $ips
     */
    public function setIpList($ips)
    {
        // TODO: Implement setIpList() method.
        foreach ($ips as $ip) {
            $this->addIp($ip);
        }
    }

    /**
     * 删除IP
     *
     * @param $ip
     */
    public function delIp($ip)
    {
        // TODO: Implement delIp() method.
        if (!isset($this->ips[$ip])) {
            throw new Exception("ip is not exists");
        }
        //删除虚拟节点
        foreach ($this->ips[$ip] as $val) {
            unset($this->virtualIps[$val]);
        }
        //删除节点
        unset($this->ips[$ip]);
        $this->ipCount--;
    }

    /**
     * 添加IP 节点
     *
     * @param $ip
     */
    public function addIp($ip)
    {
        // TODO: Implement addIp() method.
        //添加虚拟节点
        $this->ips[$ip] = [];
        for ($i = 0; $i < $this->virtualCounts; $i++) {
            //侯建虚拟节点hash值
            $hash = $this->hashIp($ip . "_" . $i);
            //添加虚拟节点
            $this->virtualIps[$hash] = $ip;
            //世界节点对应添加虚拟节点值
            $this->ips[$ip][] = $hash;
        }
        $this->needSort = false;
        //实际节点--
        $this->ipCount++;
    }

    /**
     * 取得str hash
     *
     * @param string $str
     * @return int
     */
    function hashIp($str = "")
    {
        $hash = 0;
        $s = md5($str);
        for ($i = 0; $i < 32; $i++) {
            $hash = ($hash * 33 + ord($s{$i})) & 0x7FFFFFFF;
        }
        return $hash & 0x7FFFFFFF;
    }

    /**
     * 排序
     */
    function sortItem()
    {
        ksort($this->virtualIps);
        $this->needSort = true;
    }

    /**
     * 取得IPs
     */
    public function getIps()
    {
        // TODO: Implement getIps() method.
        return array_keys($this -> ips);
    }


}