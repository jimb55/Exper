#!/bin/bash
# 2018年7月3日20:39:06
# auto install apache
# by jimb55
#######################
APACHE_VERSION_FILE="httpd-2.4.33"
APACHE_TAR_NAME=".tar.gz"
APACHE_FILE=${APACHE_VERSION_FILE}${APACHE_TAR_NAME}
APACHE_DOWNLOAD_URL="http://mirrors.shu.edu.cn/apache//httpd/"
ROOT_PATH=`pwd`

### 更新环境
yum install pcre-devel pcre openssl openssl-devel  gcc libc6-dev pcre pcre-devel apr apr-devel apr-util apr-util-devel wget -y

### 判断文件是否存在，不存在就下载
if [ -f ${ROOT_PATH}/${APACHE_FILE} ];then
    echo "file exist !!!"
else
    echo "file not find !!!"
    wget -c ${APACHE_DOWNLOAD_URL}${APACHE_FILE}
fi

### 解压并进入文件夹
tar -zxvf ${APACHE_FILE}
cd ${ROOT_PATH}/${APACHE_VERSION_FILE}

### 添加apr
cd srclib
wget http://mirrors.shu.edu.cn/apache//apr/apr-1.6.3.tar.gz
wget http://mirrors.shu.edu.cn/apache//apr/apr-util-1.6.1.tar.gz
tar -zxvf apr-1.6.3.tar.gz
tar -zxvf apr-util-1.6.1.tar.gz
mv apr-1.6.3 apr
mv apr-util-1.6.1 apr-util
rm apr-1.6.3.tar.gz
rm apr-util-1.6.1.tar.gz
cd ../

### 安装
mkdir -p /usr/local/apache2/
./configure --prefix=/usr/local/apache2/ --enable-rewrite --enable-so --enable-mpms-shared=all   --with-included-apr
make && make install

#修改配置文件
sed -i  '/^#ServerName/a\ServerName localhost' /usr/local/apache2/conf/httpd.conf

### 设置软连
ln /usr/local/apache2/bin/apachectl /usr/bin/

### 启动
apachectl -k start

### 完成
echo "done!";
