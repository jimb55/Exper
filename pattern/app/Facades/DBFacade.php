<?php
namespace app\Facades;

class DBFacade extends \lib\Facade\Facade{
    public static function getFacadeAccessor(){
        return \app\DB::class;
    }
}