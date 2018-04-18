<?php

/**
 * 抽象类
 * Class listOne
 */
namespace queue\cla;

abstract class listab
{
    /**
     * 运行逻辑
     * @return mixed
     */
    abstract function run($data);

    /**
     * 数据整理
     */
    public function action($data){
        $this -> run($data);
    }
}