#!/bin/bash
# 2018��7��3��20:39:06
# auto install nginx
# by jimb55
#######################
NGINX_VERSION_FILE="nginx-1.12.0"
NGINX_TAR_NAME=".tar.gz"
NGINX_FILE=${NGINX_VERSION_FILE}${NGINX_TAR_NAME}
NGINX_DOWNLOAD_URL="http://nginx.org/download/"
ROOT_PATH=`pwd`

### �ж��ļ��Ƿ���ڣ������ھ�����
if [ -f ${ROOT_PATH}/${NGINX_FILE} ];then
    echo "file exist !!!"
else
    echo "file not find !!!"
    wget -c ${NGINX_DOWNLOAD_URL}${NGINX_FILE}
fi

### ��ѹ�������ļ���
tar -zxvf ${NGINX_FILE}
cd ${ROOT_PATH}/${NGINX_VERSION_FILE}

### ���»���
yum install pcre-devel pcre openssl openssl-devel -y

### ���İ汾��
sed -i -e 's/1.12.0//g' -e 's/nginx\//JWS/g' -e 's/"NGINX"/"JWS"/g' src/core/nginx.h

### �����������û�
useradd www;

### ��װ
./configure --user=www --group=www --prefix=/usr/local/nginx --with-http_stub_status_module --with-http_ssl_module
make && make install

### ��������
ln /usr/local/nginx/sbin/nginx /usr/bin/

### ����
nginx -t
nginx

### ���
echo "done!";