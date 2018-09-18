<?php

/**
 * @author    NateHuang<397605079@qq.com>
 * @link      https://github.com/NateHuangHao/ProxyPool  github
 */

namespace AutoLoad;

class autoloader
{
    /**
     * 根据命名自动加载
     *
     * @param string $name
     * @return boolean
     */
    public static function load_namespace($name)
    {
        //兼容windows和linux的目录分隔符
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $name);

        //获取文件路径
        $class_file = __DIR__ . substr($class_path, strlen('ProxyPool')) . '.php';

        //如果不存在，去上一层目录寻找
        if (empty($class_file) || !is_file($class_file)) 
        {
            $class_file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . "$class_path.php";
        }

        //存在文件就require_once
        if (is_file($class_file)) 
        {
            require_once($class_file);
            if (class_exists($name, false)) 
            {
                return true;
            }
        }
        return false;
    }
}
//spl注册自动加载
spl_autoload_register('\AutoLoad\autoloader::load_namespace');
