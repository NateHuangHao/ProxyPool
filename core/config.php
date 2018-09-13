<?php

function config($name = null)
{
	if ($name) {
		if(strpos($name,".")){
			$file_name = explode(".", $name)[0];
			$index = explode(".", $name)[1];
			$arr = false;
		}
		else{
			$file_name = $name;
			$arr = true;
		}

		$file = __DIR__ . "/../config/" . $file_name . ".php";
		if (is_file($file)) 
        {
            $config = include __DIR__ . "/../config/" . $file_name . ".php";
            if ($arr) {
            	return $config;
            }
            return $config[$index];
        }
	}
    return $name;
}