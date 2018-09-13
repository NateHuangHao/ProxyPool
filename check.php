<?php
require_once __DIR__ . '/autoloader.php';
require_once __DIR__ . '/vendor/autoload.php';

use ProxyPool\core\Requests;

$redis = new \Redis();
$redis->connect('127.0.0.1', '6379');

$httpClient = new Requests(['timeout' => 10]);

$ip_pool = $redis->smembers('ip_pool');
$ip_count = $redis->scard('ip_pool');

echo '开始检测,共有IP数目:' . $ip_count . PHP_EOL;

$success_ip = [];
$success_count = 0;
$error_count = 0;
for ($i=0; $i < $ip_count; $i++) {
	$ip = $redis->spop('ip_pool');
	echo '正在检测' . $ip . PHP_EOL;
	$response = $httpClient->test_request('GET','https://www.baidu.com', ['proxy' => 'tcp://'.$ip]);
	if (!$response) {
		echo '百度网检测失败,开始检验腾讯网。。。' . PHP_EOL;
	    $response = $httpClient->test_request('GET','http://www.qq.com', ['proxy' => 'tcp://'.$ip]);
	    if ($response && $response->getStatusCode() == 200) {
	    	$success_ip[] = $ip;
	    	$success_count += 1;
	        echo $ip.'检验成功，重回IP池' . PHP_EOL;
			echo PHP_EOL;
	    }
	    else{
	    	$error_count += 1;
	        echo $ip.'检验失败，移出IP池' . PHP_EOL;
	        echo PHP_EOL;
	    }
	}
	else if ($response->getStatusCode() == 200){
		echo '百度网检测成功!!!');
		echo "\n";
	    $success_ip[] = $ip;
	    $success_count += 1;
		echo $ip.'检验成功，重回IP池' . PHP_EOL;
		echo PHP_EOL;
	}
}

foreach ($success_ip as $key => $value) {
	$redis->sadd('ip_pool',$value);
}

echo '检查完成!! 检测失败IP数:'.$error_count.",检测成功IP数:".$success_count . PHP_EOL;
echo PHP_EOL;
