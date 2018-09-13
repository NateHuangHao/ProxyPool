<?php
require_once __DIR__ . '/autoloader.php';
require_once __DIR__ . '/vendor/autoload.php';

use ProxyPool\core\ProxyPool;

$proxy = new ProxyPool();
$proxy->run();