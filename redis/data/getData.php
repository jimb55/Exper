<?php

/**
 * 模拟从数据库取回数据
 *
 * @param string $name
 * @param int $sleep
 * @return bool|mix|string
 */
function getData($name = "users", $sleep = 1)
{
    sleep($sleep);
    $res = file_get_contents(__DIR__."/$name.json");
    return json_decode($res,true)["data"];
}