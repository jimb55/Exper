<?php
/**
 * Created by PhpStorm.
 * User: Jimb
 * Date: 2017/11/28
 * Time: 10:14
 */

namespace lib;

/**
 * 算法接口
 *
 * Interface Algorithm
 * @package lib
 */
interface Algorithm
{

    /**
     * 根据 $Id 取得IP
     *
     * @param $id
     * @return mixed
     */
    public function getIp($id);

    /**
     * 设置 IP list
     *
     * @param $id
     * @return mixed
     */
    public function setIpList($ips);

    /**
     * 从IP List 中删除 IP
     *
     * @param $id
     * @return mixed
     */
    public function delIp($ip);

    /**
     * 从IP List 中添加 IP
     *
     * @param $id
     * @return mixed
     */
    public function addIp($ip);

    /**
     * 取得Ips
     * @return mixed
     */
    public function getIps();
}