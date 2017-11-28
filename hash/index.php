<?php

require (__DIR__.'/lib/Algorithm.php');
require (__DIR__.'/lib/HashRing.php');
require (__DIR__.'/lib/HashOver.php');
require (__DIR__.'/lib/CacheDbContainer.php');
require (__DIR__.'/lib/CacheDb.php');
require (__DIR__.'/main.php');

(new \Main\App) -> main();
//(new \Main\App) -> test();


/**
 * 强化打印效果
 */
function dd()
{
    foreach (func_get_args() as $dump) {
        print_r($dump);
    }
    exit();
}