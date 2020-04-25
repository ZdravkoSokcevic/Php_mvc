<?php
    $autoload_classes = include __DIR__ . '/../config/autoloader.php';
    $constants = include __DIR__ . '/../config/constants.php';

    /*
    |--------------------------------------
    |   Load helpers
    |--------------------------------------
    */
    require_once __DIR__.'/./helpers.php';
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
    |   Main autoloader function
    |   Here we need to include all 
    |   files in app
    | 
    */
    for($x=0;$x<count($autoload_classes);$x++)
    {
        try{
            require_once PWD . BACK . $autoload_classes[$x] . PHP_EXT;
        }catch (\Exception $e) {
            throw new Error('Classpath does\'t exists');
        }
    }
    unset($autoload_classes);

    require_once PWD . BACK . 'routing' . DS . 'index' . PHP_EXT;
?>