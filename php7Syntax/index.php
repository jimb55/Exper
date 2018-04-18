<?php
function arraysSum(array ...$arrays): array
{
    return array_map(function(array $array): int {
        return array_sum($array);
    }, $arrays);
}

list($a,$b,$c) = arraysSum([1,2,3], [4,5,6], [7,8,9]);
print_r([$a,$b,$c]);