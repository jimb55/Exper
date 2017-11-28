<?php
/**
 * Created by PhpStorm.
 * User: Jimb
 * Date: 2017/11/28
 * Time: 11:14
 */
namespace lib;

use lib\CacheDb;

class CacheDbContainer{

    private $cacheDbs = [];
    private $algorithm;

    /*
     * 构造函数模拟生成IP对应的键值对缓存db
     */
    public function __construct(\lib\Algorithm $algorithm)
    {
        // 策略计算类注入
        $this -> algorithm = $algorithm;
        // 生成IP 对应的 内存
        foreach ($this -> algorithm -> getIps() as $ip){
            $this -> cacheDbs[$ip] = new CacheDb($ip);
        }
    }

    /*
     * 模拟链接cache服务
     */
    public function link($ip){
        return $this -> cacheDbs[$ip];
    }

    /**
     * 毁坏一台机子
     * @param $ip
     */
    public function bad($ip){
        $this -> algorithm -> delIp($ip);
        unset($this -> cacheDbs[$ip]);
    }

    /**
     * 缓存服务列表
     */
    public function getlist(){
        return $this -> cacheDbs;
    }

    /**
     * 使用取余法-取出 cacheDb 内容
     */
    public function overGet($id){
        // 根据id用取余法取得数据存储的ip
        $ip = $this -> algorithm -> getIp($id);;

        // 根据IP 取出储存实例
        $cache = $this -> link($ip);
        if($val = $cache -> get("key_".$id))
            return $val;
        return false;
    }

    /**
     * 使用取余法-存入 cacheDb 内容
     */
    public function overSet($id,$val=false){
        // 根据id用取余法取得数据存储的ip
        $ip = $this -> algorithm -> getIp($id);

        // 根据IP 取出储存实例
        $cache = $this -> link($ip);
        if($cache -> set("key_".$id,$val ? $val : $ip))
            return $ip;
        return false;
    }

}