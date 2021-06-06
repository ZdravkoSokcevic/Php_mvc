<?php
    /*
    |--------------------------------------
    | Load necessary constants 
    | for application
    |--------------------------------------
    */
    $constants = include __DIR__ . '/./Helpers/constants.php';
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

    $autoload_classes = include __DIR__ . '/./../../config/autoloader.php';

    /*
    |--------------------------------------
    |  Set the application root directory
    |--------------------------------------
    */
    define('ROOT', realpath(__DIR__ . '/./../../'));

    /*
    |--------------------------------------
    |   Load helpers
    |--------------------------------------
    */
    require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Helpers' . DS . 'functions' . PHP_EXT;

    require_file('core.framework.Helpers.constants');
   
    // require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Helpers' . DS . 'constants' . PHP_EXT;

    require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'core_loader' . PHP_EXT;

	require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Application' . PHP_EXT;

    /*
    |--------------------------------------
    |   Main autoloader function
    |   Here we need to include all 
    |   files in app
    |--------------------------------------
    */
    for($x=0;$x<count($autoload_classes);$x++)
    {
        try{
            require_once ROOT . DS . $autoload_classes[$x] . PHP_EXT;
        }catch (\Exception $e) {
            throw new Error('Classpath does\'t exists');
        }
    }
    unset($autoload_classes);
	$app = new \App\Core\Framework\Application();

	function app()
	{
		return $app;
	}

    $router = new Router();

    /* 
    |****************************************************************
    |   Detect if is about console application
    |****************************************************************
    */
    if(!defined('CONSOLE'))
        require_once ROOT . DS . 'routing' . DS . 'index' . PHP_EXT;

?>