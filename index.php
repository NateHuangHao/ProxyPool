<?php
$redis = new \Redis();
$redis->connect('127.0.0.1', '6379');

//随机获取单个ip
if ($_SERVER['REQUEST_URI'] == '/get') {
	$ip = $redis->srandmember('ip_pool');
	$result = [
		'data' => $ip
	];
	echo json_encode($result);
}
//获取全部ip
else if ($_SERVER['REQUEST_URI'] == '/get_all') {
	$ip_pool = $redis->smembers('ip_pool');
	$result = [
		'data' => $ip_pool
	];
	echo json_encode($result);
}
//获取ip池数量
else if ($_SERVER['REQUEST_URI'] == '/status') {
	$ip_count = $redis->scard('ip_pool');
	$result = [
		'pool_count' => $ip_count
	];
	echo json_encode($result);
}
//随机取出一个ip，并在redis池中删除它
else if ($_SERVER['REQUEST_URI'] == '/pop') {
	$ip = $redis->spop('ip_pool');
	echo $ip;
}
//插入一个ip地址到redis池
else if ($_SERVER['REQUEST_URI'] == '/push') {
	$ip = $redis->sadd('ip_pool','192.168.0.1:80');
	echo 'push success!!!';
}
else{
	echo "HTTP:404 NOT FOUND";
}