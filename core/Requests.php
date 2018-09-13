<?php

namespace ProxyPool\core;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

/**
*  http请求处理
*/
class Requests
{

	private $client;
	
	function __construct($options)
	{
		$this->client = new HttpClient($options);
	}
	
	public function request($method, $cgi, $params)
    {
    	//是否需要代理
        if (config("spider.set_proxy")) {
            $params += [
                'proxy' => config("spider.proxy_host")
            ];
        }
        try {
            $response = $this->client->request(
                $method,
                $cgi,
                $params
            );
        } catch (RequestException $e) {
            return [null, $e->getMessage()];
        }

        return [$response, ''];
    }

    public function test_request($method, $cgi, $params)
    {
        try {
            $response = $this->client->request(
                $method,
                $cgi,
                $params
            );
        } catch (RequestException $e) {
            return null;
        }
        return $response;
    }

    public function __call($name, $arguments)
    {
        return $this->client->$name($arguments);
    }
}
