<?php

    if(php_sapi_name() == 'cli-server')
    {
        // var_dump($_SERVER);die();
        // die();
    }

	if(!defined('ROOT'))
        require_once __DIR__ . './../webroot/index.php';

    $router->get('controller', 'PagesController@loadControllerPage');
    $router->get('routing', 'PagesController@loadRoutingPage');
    $router->get('installation', 'PagesController@loadInstallationPage');
    $router->get('/{page}', 'PagesController@base');
    $router->get('', 'PagesController@base');

?>