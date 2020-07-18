<?php

	if(!defined('ROOT')) {
		require_once __DIR__ . './../index.php';
	}
    // die();
    // var_dump($_SERVER);die();
    $router= new Router();
    $router->get('', 'UserController@home');
    $router->post('', 'UserController@home');
    $router->get('user/pera', 'UserController@loadAll');
    $router->get('user/{all}/{pera}', 'UserController@loadAll');
    var_dump($router);die();

?>