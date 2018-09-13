# ProxyPool
IP代理池项目（目前仅支持西刺和快代理，使用redis做存储）

## 环境要求
PHP 5.4 以上版本,强烈推荐php7，性能更好

## 项目初始化
``` 
composer install
``` 

## 执行脚本爬取IP并插入redis

``` 
php run.php
``` 

## 获取IP，项目地址指向根目录的index.php
``` 
例：http://127.0.0.1/get       //获取单个IP
例：http://127.0.0.1/get_all   //获取数据库中全部IP
例：http://127.0.0.1/status    //获取数据库中IP数量
例：http://127.0.0.1/pop       //随机取出一个ip，并删除它
``` 

## 检查数据库中的IP可用性，丢弃不可用IP
``` 
php check.php
``` 
