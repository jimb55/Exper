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





###安装 nginx-1.13.12
依然是`./configure` make make install
```
#可能遇到
./configure: error: the HTTP rewrite module requires the PCRE library.
You can either disable the module by using --without-http_rewrite_module
option, or install the PCRE library into the system, or build the PCRE library
statically from the source with nginx by using --with-pcre=<path> option.
```

pcre pcre-devel
```
# yum search pcre              
Loaded plugins: fastestmirror
Loading mirror speeds from cached hostfile
=================================================================================== N/S matched: pcre ====================================================================================
ghc-pcre-light-devel.x86_64 : Haskell pcre-light library development files
mingw32-pcre.noarch : MinGW Windows pcre library
mingw32-pcre-static.noarch : Static version of the mingw32-pcre library
mingw64-pcre.noarch : MinGW Windows pcre library
mingw64-pcre-static.noarch : Static version of the mingw64-pcre library
pcre-devel.i686 : Development files for pcre
pcre-devel.x86_64 : Development files for pcre
pcre-static.i686 : Static library for pcre
pcre-static.x86_64 : Static library for pcre
pcre-tools.x86_64 : Auxiliary utilities for pcre
pcre2-devel.i686 : Development files for pcre2
pcre2-devel.x86_64 : Development files for pcre2
pcre2-static.i686 : Static library for pcre2
pcre2-static.x86_64 : Static library for pcre2
pcre2-tools.x86_64 : Auxiliary utilities for pcre2
pcre2-utf16.i686 : UTF-16 variant of PCRE2
pcre2-utf16.x86_64 : UTF-16 variant of PCRE2
pcre2-utf32.i686 : UTF-32 variant of PCRE2
pcre2-utf32.x86_64 : UTF-32 variant of PCRE2
ghc-pcre-light.x86_64 : Perl5 compatible regular expression library
opensips-regex.x86_64 : RegExp via PCRE library
pcre.i686 : Perl-compatible regular expression library
pcre.x86_64 : Perl-compatible regular expression library
pcre2.i686 : Perl-compatible regular expression library
pcre2.x86_64 : Perl-compatible regular expression library

  Name and summary matches only, use "search all" for everything.
```
直接安装即可


nginx 在编译安装时能添加许多包，如 ./configure --with-debug ,就是能打开debug模式，能够在log 上输出请求这整个过程
输出由 http 到 server 到location的生命周期发生的日志，包括参数等

with-pcre-jit   --用于pcre的动态编译，听说动态编译可以省去静态资源的选择语句的时间，安装了正则表达式会快点，./configure的时候追加


with-ipv6 能够监听ip6 的地址，listen ip4:port => listen ip6:port https://blog.csdn.net/jmingbh/article/details/69388647
with-http_ssl_module 能够开启ssl 支持，即https
with-http_stub_status_module 能够开启nginx监听 https://www.cnblogs.com/94cool/p/3872492.html
with-http_realip_module 获取真实IP 尤其是经过代理啊，负载均衡之类 的 https://blog.csdn.net/cscrazybing/article/details/50789234
with-http_auth_request_module 认证，复习 https://www.cnblogs.com/wangxiaoqiangs/p/6184181.html
with-http_addition_module 响应之前或者之后追加文本内容 http://www.ttlsa.com/linux/nginx-modules-ngx_http_addition_module/
with-http_dav_module  启用ngx_http_dav_module支持（增加PUT,DELETE,MKCOL：创建集合,COPY和MOVE方法）默认情况下为关闭，需编译开启
with-http_geoip_module 针对地区访问控制 http://ju.outofmemory.cn/entry/16264
with-http_gunzip_module  为不支持“gzip”编码方式的客户端解压缩头部包含“Content-Encoding: gzip”响应的过滤器。https://blog.lyz810.com/article/2016/05/ngx_http_gunzip_module_doc_zh-cn/
with-http_gzip_static_module 解压缩头部包
with-http_image_filter_module 来裁剪图片 https://blog.csdn.net/revitalizing/article/details/52714198
with-http_v2_module  
with-http_sub_module 它修改网站响应内容中的字符串 http://www.ttlsa.com/linux/nginx-modules-ngx_http_sub_module/
with-http_xslt_module 启用ngx_http_xslt_module支持 http://www.ttlsa.com/nginx/nginx-configure-descriptions/
with-stream 负载均衡 https://blog.csdn.net/zhiyuan_2007/article/details/71238216
with-stream_ssl_module  ssl负载均衡 
with-mail 邮件
with-mail_ssl_module 邮件ssl
with-threads https://segmentfault.com/a/1190000002924458 !import
