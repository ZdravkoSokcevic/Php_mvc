<?php
    // die();
    // var_dump($_SERVER);die();
	require_once './base/helpers.php';
    $router= new Router();
    $router->get('user/pera', 'UserController@loadAll');
    $router->get('user/{all}/{pera}', 'UserController@loadAll');
    var_dump($router);die();

?>