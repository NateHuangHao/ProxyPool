<?php
require_once __DIR__ . '/autoloader.php';
require_once __DIR__ . '/vendor/autoload.php';

use ProxyPool\core\Requests;

$redis = new \Redis();
$redis->connect('127.0.0.1', '6379');

$httpClient = new Requests(['timeout' => 10]);

$ip_pool = $redis->smembers('ip_pool');
$ip_count = $redis->scard('ip_pool');

print_r('开始检测,共有IP数目:'.$ip_count);
echo "\n";

$success_ip = [];
$success_count = 0;
$error_count = 0;
for ($i=0; $i < $ip_count; $i++) {
	$ip = $redis->spop('ip_pool');
	print_r('正在检测'.$ip);
	echo "\n";
	$response = $httpClient->test_request('GET','https://www.baidu.com', ['proxy' => 'tcp://'.$ip]);
	if (!$response) {
		print_r('百度网检测失败,开始检验腾讯网。。。');
		echo "\n";
	    $response = $httpClient->test_request('GET','http://www.qq.com', ['proxy' => 'tcp://'.$ip]);
	    if ($response && $response->getStatusCode() == 200) {
	    	$success_ip[] = $ip;
	    	$success_count += 1;
	        print_r($ip.'检验成功，重回IP池');
			echo "\n";
			echo "\n";
	    }
	    else{
	    	$error_count += 1;
	        print_r($ip.'检验失败，移出IP池');
	        echo "\n";
	        echo "\n";
	    }
	}
	else if ($response->getStatusCode() == 200){
		print_r('百度网检测成功!!!');
		echo "\n";
	    $success_ip[] = $ip;
	    $success_count += 1;
		print_r($ip.'检验成功，重回IP池');
		echo "\n";
		echo "\n";
	}
}

foreach ($success_ip as $key => $value) {
	$redis->sadd('ip_pool',$value);
}

print_r('检查完成!! 检测失败IP数:'.$error_count.",检测成功IP数:".$success_count);
echo "\n";
