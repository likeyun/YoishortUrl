# YoishortUrl
自研的短网址源码，使用自己的域名生成短网址，本套代码及其简洁，可以二次开发，增加各种具备安全性的措施，代码基于php5.6开发，要求php环境5.6及以上，并且要求在Apache服务器部署使用。

# 使用方法
1、打开db_connect.php配置数据库和域名

2、创建数据库字段


| id | dwzkey | long_url | creat_time | pageview |
| :-----| ----: | :----: | :----: | :----: |
| int(11) | varchar(10) | text | timestamp(CURRENT_TIMESTAMP) | varchar(10) |
| 自增、主键 | 短网址Key | 长链接 | 创建时间 | 访问次数 |

注意，creat_time字段是timestamp类型，默认值是CURRENT_TIMESTAMP，即自动插入当前的时间。

3、配置Apache URL重写规则，该规则只适用于Apache服务器，Nginx服务器自己写哈

在本套代码的根目录新建一个txt文件，把下面代码复制进去
```
RewriteEngine On
#RewriteBase / 
RewriteRule ^(\w+)$ index.php?id=$1
```
然后另存为，选择所有文件，命名为`.htaccess`即可

4、创建短网址，请求网址
```
http://你的域名/代码目录/creat_dwz.php?url=需要缩短的URL
```

5、创建结果示例
```
{"code":"200","msg":"创建成功","short_url":"http:\/\/abc.cn\/dwz\/\/a38J"}
```

# 注意
因为生成的短网址会带上代码目录，如果你不想创建带上代码所在目录的短网址，有两个方法，一是在服务器根目录搭建这套程序，二是把域名指向代码所在的目录作为根目录。这样的话就可以生成一个没有二级目录的短网址

```
{"code":"200","msg":"创建成功","short_url":"http:\/\/abc.cn\/a38J"}
```
