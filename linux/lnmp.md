#linux 基础
安装PHP7.0 nginx mysql

### 安装 PHP7
登录 php.net/ 打开downloads 页面
选个最新的（php.net 上的php版本都为linux 源码包，需要window 的到window.php.net/ 上下载，另需要旧版本在download 的 Old archives 页面下寻找）

下载php7 源码包
```
wget http://cn2.php.net/distributions/php-7.2.3.tar.gz
```
>ps：假如用window查找的资源，可直接在浏览器上复制其下载地址到linux 上下载！

解压下载包
```
tar -zxvf  php-7.2.3.tar.gz
```

到解压目录
```
cd php-7.2.3
```
会发现一大堆文件，这些都是源码，主要是./configure 文件 网上教程都是 `./configure && make && make install` 

打开配置帮助
```
./configure --help
```
会打印一大堆配置要求，但什么curl giz pdo 先不管，主要是三处
- --prefix=/... 配置安装路径
- --with-config-file-path=/... 这个是php.ini存放路径，只能现在配置，编译安装后就不能指定了，要重新编译，所以这里得先指定
- --enable-fpm  安装 php-fpm,nginx需要的fast-cig需指定php-fpm ,也就是传统的php-fpm 9000端口

如例子
```
./configure --prefix=/usr/local/es/php7 --with-config-file-path=/usr/local/es/php7/etc/ --enable-fpm
```
成功如下
```linux
Generating files
configure: creating ./config.status
creating main/internal_functions.c
creating main/internal_functions_cli.c
+--------------------------------------------------------------------+
| License:                                                           |
| This software is subject to the PHP License, available in this     |
| distribution in the file LICENSE.  By continuing this installation |
| process, you are bound by the terms of this license agreement.     |
| If you do not agree with the terms of this license, you must abort |
| the installation process at this point.                            |
+--------------------------------------------------------------------+

Thank you for using PHP.
```
出现以上内容表示安装成功。
但经常会出现各种问题，最常见的是缺少环境
如下：
```
configure error xml2-config not found. please check your libxml2 installation
```
就是输出了一大串，然后在结尾（差不多结尾的地方）出现这样一个东东，基本上就是安装失败了。
如字面意思，就是缺少某个库 `libxml2` 
用 yum 直接补上缺失的库就行（这些库基本上yum 都可以装，不用自己去找下载地址和配置）
```linux
yum install libxml2
yum install libxml2-devel
```
把上面报错的包名（就是 please check your `libxml2` installation ） 中的libxml2 ，+ libxml2-devel 有的一并安装上
这是开发的源码，但有些可能检测不到的包名可以把前面error `xml2-config` not found 中 xml2-config 百度其支持的包，进行相应下载.
然后
```
make && make install
```
这步失败基本上就是上面的没有成功执行引起，仔细检测上面的`./configure`操作


