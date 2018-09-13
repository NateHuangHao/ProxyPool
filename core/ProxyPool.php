<?php
/**
*  ProxyPool核心文件
*/

namespace ProxyPool\core;
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/config.php';

use ProxyPool\core\Requests;
use ProxyPool\core\Queue;

class ProxyPool
{
	private $redis;
    private $httpClient;
	private $queueObj;

	function __construct()
    {
    	$redis = new \Redis();
        $redis->connect(config("database.redis_host"), config("database.redis_port"));
        $this->redis = $redis;
        $this->httpClient = new Requests(['timeout' => 10]);
        $this->queueObj = new Queue();
    }

    public function run()
    {
    	$ip_arr = $this->get_ip();

        $this->check_ip($ip_arr);
        $ip_pool = $this->redis->smembers('ip_pool');
		print_r($ip_arr);
        exit();
    }

    //获取各大网站代理IP
    private function get_ip()
    {
        $ip_arr = [];
        for ($i = 1; $i <= config('spider.page_num'); $i++) {
            list($infoRes, $msg) = $this->httpClient ->request(
                'GET',
                'http://www.xicidaili.com/nn/'.$i,
                []
            );
            if (!$infoRes) {
                print_r($msg);
                exit();
            }
            $infoContent = $infoRes->getBody();
            $this->convert_encoding($infoContent);
            preg_match_all('/<tr.*>[\s\S]*?<td class="country">[\s\S]*?<\/td>[\s\S]*?<td>(.*?)<\/td>[\s\S]*?<td>(.*?)<\/td>/', $infoContent, $match);

            $host_arr = $match[1];
            $port_arr = $match[2];
            foreach ($host_arr as $key => $value) {
                $ip_arr[] = $host_arr[$key].":".$port_arr[$key];
            }
        }
        return $ip_arr;
    }

    //检测IP可用性
    private function check_ip($ip_arr)
    {
        $this->queueObj = $this->queueObj->arr2queue($ip_arr);
        $queue = $this->queueObj->getQueue();

        foreach ($queue as $key => $value) {
            for ($i=0; $i < config('spider.examine_round'); $i++) { 
                $response = $this->httpClient->test_request('GET','https://www.baidu.com', ['proxy' => 'tcp://'.$value]);
                if (!$response) {
                    $response = $this->httpClient->test_request('GET','http://www.qq.com', ['proxy' => 'tcp://'.$value]);
                    if ($response && $response->getStatusCode() == 200) {
                        break;
                    }
                }
                else if($response->getStatusCode() == 200){
                    break;
                }
            }
            
        	if ($response && $response->getStatusCode() == 200) {
        		$this->set_ip2redis($value);
        		print_r($value." success!!!!!!!!!!!!!!!!!!!!!!!! ");
                echo "\n";
        	}
        	else{
        		print_r($value." error... ");
                echo "\n";
        	}

        }
    }

    //将可用ip存进redis
    private function set_ip2redis($ip)
    {
    	$this->redis->sadd('ip_pool',$ip);
    }

    //将可用ip存进mysql
    private function set_ip2mysql($ip_arr)
    {
       
    }

    private function convert_encoding(&$str)
    {
        $encode = mb_detect_encoding($str, ['GB2312','UTF-8','GBK','ASCII']);
        $str = iconv($encode, 'utf-8//TRANSLIT//IGNORE', $str);
    }
}