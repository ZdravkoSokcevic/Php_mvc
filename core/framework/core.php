<?php
    session_start();
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

    /*
    |--------------------------------------
    |   Load helpers
    |--------------------------------------
    */
    require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Helpers' . DS . 'functions' . PHP_EXT;

    require_file('core.framework.Helpers.constants');
    require_file('core.framework.core_loader');
    require_file('core.framework.Application');

    /*
    |--------------------------------------
    |   Load config
    |--------------------------------------
    */
    require_file('core.framework.Classes.Config');
    $config = new Config();

	$app = new \App\Core\Framework\Application();

    $router = new Router();

    /* 
    |****************************************************************
    |   Detect if is about console application
    |****************************************************************
    */
    if(!defined('CONSOLE'))
        require_once ROOT . DS . 'routing' . DS . 'index' . PHP_EXT;

?>