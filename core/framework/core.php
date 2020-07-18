<?php

    $autoload_classes = include __DIR__ . './../../config/autoloader.php';
    $constants = include __DIR__ . './../../config/constants.php';

        /*
    |--------------------------------------
    |   Load helpers
    |--------------------------------------
    */
    require_once __DIR__.'./../../base/helpers.php';
    /*
    |--------------------------------------
    |       Constants include
    |--------------------------------------
    |
    */
    $keys= array_keys($constants);
    $vals= array_values($constants);
    for($x=0;$x<count($keys);$x++)
        define($keys[$x], $vals[$x]);

    unset($constants);
    unset($keys);
    unset($vals);

    /*
    |
    |  Set the application root directory
    |
    */
    define('ROOT', __DIR__ . '/./../../');


    /*
    |   Main autoloader function
    |   Here we need to include all 
    |   files in app
    | 
    */
    for($x=0;$x<count($autoload_classes);$x++)
    {
        try{
            require_once ROOT . $autoload_classes[$x] . PHP_EXT;
        }catch (\Exception $e) {
            throw new Error('Classpath does\'t exists');
        }
    }
    unset($autoload_classes);

    require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'core_loader' . PHP_EXT;

    require_once ROOT . DS . 'base' . DS . 'helpers' . PHP_EXT;
    
	require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Application' . PHP_EXT;

	$app = new \App\Core\Framework\Application();

	function app()
	{
		return $app;
	}

    /* 
    |****************************************************************
    |   Detect if is about console application
    |****************************************************************
    */
    if(!defined('CONSOLE'))
        require_once ROOT . DS . 'routing' . DS . 'index' . PHP_EXT;

?>