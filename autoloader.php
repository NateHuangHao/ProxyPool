<?php
/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    NateHuang<397605079@qq.com>
 * @link      https://github.com/NateHuangHao/ProxyPool  github
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace ProxyPool;

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
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $name);

        if (strpos($name, 'phpspider\\') === 0) 
        {
            $class_file = __DIR__ . substr($class_path, strlen('phpspider')) . '.php';
        }
        else 
        {
            if (empty($class_file) || !is_file($class_file)) 
            {
                $class_file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . "$class_path.php";
            }
        }

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

spl_autoload_register('\ProxyPool\autoloader::load_namespace');
