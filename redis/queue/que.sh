#!/bin/bash

#操作
insert_a=0;
if [ $1 ];then
insert_a=$1;
fi


#操作start
if [ $insert_a == "start" ];then

# php 数组  转  shell 数组
json=`php -r '$rs=require __DIR__."/queue.php";foreach($rs as $item){ echo $item."  ";};'`
for element in ${json[@]}
do
# 队列名
quename=list_queue_$element
echo $quename." start to listen"
php blockList.php $element &
echo "$!" >> pid
done

#stop
elif [ $insert_a == "stop" ];then
kill `cat pid`
rm pid log
else
echo "NOT FIND ACTION $insert_a !";
exit
fi

sleep 1

echo "...ACTION SUCCESSFUL!"
