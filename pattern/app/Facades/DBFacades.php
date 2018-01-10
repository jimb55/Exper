<?php
namespace app\Facades;

class DBFacades extends \lib\Facade\Facade{
    public static function getFacadeAccessor(){
        return \app\DB::class;
    }
}