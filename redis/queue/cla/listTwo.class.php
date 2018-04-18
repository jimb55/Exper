<?php

/**
 * 队列逻辑执行文件二
 * Class listTwo
 */
namespace queue\cla;

class listTwo extends \queue\cla\listab
{
    public function run($data)
    {
        shell_exec("echo 'listTwo    \n' >> log");
        shell_exec("echo 'st ".json_encode($data)." !    \n' >> log");
    }
}