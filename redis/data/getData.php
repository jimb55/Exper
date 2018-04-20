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

/**
 * 模拟插入数据
 *
 * @param string $name
 * @param $data
 * @return null
 */
function setData($name = "users", $data)
{
    $res = file_get_contents(__DIR__."/$name.json");
    $resJson = json_decode($res,true);

    $s = end($resJson["data"]);
    $key = 0;

    if($s){
        $key = $s["id"] * 1;
    }
    foreach ($data as $k => $item){
        $key++;
        $data[$k]["id"] = $key;
        array_push($resJson["data"],$data[$k]);
    }
    shell_exec("echo '".json_encode($resJson)."' > ".__DIR__."/$name.json");
    return $data;
}


/**
 *
 * ≡(▔﹏▔)≡
 *
 * 根据页数取得条目
 *
 * @param string $name
 * @param int $sleep
 * @param int $page
 * @param int $pageSize
 */
function getDataPage($name = "users", $sleep = 1,$page=1,$pageSize=100){
    $data = getData($name,$sleep);
    krsort($data);
    return array_slice($data,($page-1)*$pageSize,$pageSize);
}


/**
 * $num为生成汉字的数量
 *
 * @param $num
 * @return string
 */
function getChar($num)  //
{
    $b = '';
    for ($i=0; $i<$num; $i++) {
        // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
        $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
        // 转码
        $b .= iconv('GB2312', 'UTF-8', $a);
    }
    return $b;
}