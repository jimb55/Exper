<?php

namespace lib;

// cache item
class CacheDb{
    private $map = [];
    private $link = "";
    public function __construct($ip="")
    {
        $link = $ip;
    }

    /**
     * 模拟储存，目标为内存中
     *
     * @param $key
     * @param $val
     * @return mixed
     */
    public function set($key,$val){
        $this -> map[$key] = $val;
        return $val;
    }

    /**
     * 模拟取出数据，目标为内存中
     *
     * @param $key
     * @return mixed|string
     */
    public function get($key){
        return key_exists($key,$this -> map) ? $this -> map[$key] : "";
    }

    /**
     * 模拟取出所有数据，目标为内存中
     *
     * @param $key
     * @return mixed|string
     */
    public function getAll(){
        return $this -> map ? $this -> map : [];
    }
}
