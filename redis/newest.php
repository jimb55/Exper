<?php

require "data/getData.php";

$redis = new Redis();
$res = $redis->connect('127.0.0.1', 6379);
$redis->auth('jimb55');

/**
 * 缓存
 *
 * 场景，获取最新的50条新闻数据
 *
 * bug 按快插入6条....
 *
 */
//
//$redis -> del("list_newest");exit();

key_exists("act",$_REQUEST) ? null : $_REQUEST["act"] = "get";

$pageSize = 3;

if($_REQUEST["act"] == "get"){
    !key_exists("page",$_REQUEST) ? $_REQUEST["page"] = 1 : null;
    $page = is_numeric($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

    if($page == 1){
        $data = $redis -> lRange("list_newest",0,$redis->lLen("list_newest"));
        if(!$data){
            $data = getDataPage("persion1",2,$page,$pageSize);
            foreach ($data as $key => $val){
                $redis -> rpush("list_newest",json_encode($val));
            }
        }
    }else{
        $data = getDataPage("persion1",2,$page,$pageSize);
    }
    print_r($data);
}


//插入2条数据看看
if($_REQUEST["act"] == "insert"){
    $inserData = [
        ["name" => getChar(3),"age" => rand(5,100)],
        ["name" => getChar(3),"age" => rand(5,100)]
    ];
    $inserData = setData("persion1",$inserData);

    //更新redis 数据
    foreach ($inserData as $k => $val){
        $redis -> rpop("list_newest");
        $redis -> lpush("list_newest",json_encode($inserData[$k]));
        print_r($inserData[$k]);
    }
}
