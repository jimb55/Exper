<?php

namespace app;

class DB{
    public function __construct($args){

    }
    public function Write($str){
        echo 'Write:'.$str.PHP_EOL;
    }
    public function Read($str){
        echo 'Read:'.$str.PHP_EOL;
    }
}