<?php
$application->registerModules(array(
	'admin' => array(
        'className' => 'Dsc\Admin\Module',
        'path' => __dir__ . '/src/Module.php'
    )
), true);

$di = $application->getDI();  
$di->get('theme')->registerViewPath( PATH_ROOT . 'vendor/dioscouri/phalcon-admin/src/Views/', 'Admin/Views' );

$router = $di->get('router');
$router->mount( new \Dsc\Admin\Routes );
?>