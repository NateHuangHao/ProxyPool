<?php
	return [
		// 从代理ip网站上总共要爬取的ip页数。每页100条，小项目(20-30个代理ip即可完成的)可以设置为1-2页。
		'page_num' => 3,

		// 对已经检测成功的ip测试轮次。
		'examine_round' => 5,

		// 超时时间。代理ip在测试过程中的超时时间。
		'timeout' => 200,

		//是否开启代理
		'set_proxy' => false,

		//请求代理网站的代理IP
		'proxy_host' => 'http://web-proxy.tencent.com:8080',
	];
