<?php
/**
 * Created by PhpStorm.
 * User: Jimb
 * Date: 2017/11/28
 * Time: 16:04
 */

namespace lib;


class HashOver implements \lib\Algorithm
{
    public $ips = [];

    /**
     * HashRing constructor.
     * @param array $ips
     * @param int $_virtualCounts
     */
    function __construct(array $ips)
    {
        $this->setIpList($ips);
    }

    public function getIp($id)
    {
        // TODO: Implement getIp() method.
        return $this -> ips[$id % count($this -> ips)];
    }

    public function setIpList($ips)
    {
        // TODO: Implement setIpList() method.
        $this -> ips = $ips;
    }

    public function delIp($ip)
    {
        // TODO: Implement delIp() method.
        array_splice($this -> ips,array_search($ip,$this -> ips),1);
    }

    public function addIp($ip)
    {
        // TODO: Implement addIp() method.
        $this -> ips[] = $ip;
    }

    public function getIps()
    {
        // TODO: Implement getIps() method.
        return $this -> ips;
    }

}