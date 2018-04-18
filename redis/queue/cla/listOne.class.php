<?php

/**
 * 队列逻辑执行文件一
 * Class listOne
 */

namespace queue\cla;

class listOne extends \queue\cla\listab
{
    public function run($data)
    {
        shell_exec("echo 'listOne    \n' >> log");
        shell_exec("echo 'st ".json_encode($data)." !    \n' >> log");
    }
}