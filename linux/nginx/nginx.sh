#!/bin/bash
# 2018年7月3日20:39:06
# auto install nginx
# by jimb55
#######################
NGINX_VERSION_FILE="nginx-1.12.0"
NGINX_TAR_NAME=".tar.gz"
NGINX_FILE=${NGINX_VERSION_FILE}${NGINX_TAR_NAME}
NGINX_DOWNLOAD_URL="http://nginx.org/download/"
ROOT_PATH=`pwd`

### 判断文件是否存在，不存在就下载
if [ -f ${ROOT_PATH}/${NGINX_FILE} ];then
    echo "file exist !!!"
else
    echo "file not find !!!"
    wget -c ${NGINX_DOWNLOAD_URL}${NGINX_FILE}
fi

### 解压并进入文件夹
tar -zxvf ${NGINX_FILE}
cd ${ROOT_PATH}/${NGINX_VERSION_FILE}

### 更新环境
yum install pcre-devel pcre openssl openssl-devel -y

### 更改版本号
sed -i -e 's/1.12.0//g' -e 's/nginx\//JWS/g' -e 's/"NGINX"/"JWS"/g' src/core/nginx.h

### 添加运行组和用户
useradd www;

### 安装
./configure --user=www --group=www --prefix=/usr/local/nginx --with-http_stub_status_module --with-http_ssl_module
make && make install

### 设置软连
ln /usr/local/nginx/sbin/nginx /usr/bin/

### 启动
nginx -t
nginx

### 完成
echo "done!";