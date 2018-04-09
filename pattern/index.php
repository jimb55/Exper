<?php

//require 'vendor/autoload.php';
//
//use app\Facades\DBFacade;
//DBFacade::Write("hello world!!!");

print_r("---------------------".PHP_EOL);

$append_iterator = new \AppendIterator();

$generator = ReportModel::come_generator();

// Only works if first value in generator is not empty
// useful when yielding arrays
foreach ($append_iterator as $value)
{
    //If first $value not empty, generator is not empty.
    if(!empty($value))
    {
        $append_iterator->append($errors);
        //break out of loop after appending.
        break;
    }
}
