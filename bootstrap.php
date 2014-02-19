<?php
// Registered as dsc-admin (rather than admin)
// so that /:module/:controller/:action/:params can be defined as a global route
// without always routing to this module
$application->registerModules(array(
    'phalcon-admin' => array(
        'className' => 'Dsc\Admin\Module',
        'path' => __dir__ . '/src/Module.php'
    )
), true);


$di = $application->getDI();
switch ($di->get('APP_NAME'))
{
	case "admin":
	    // set this as the theme
	    $di->get('theme')->setTheme('AdminTheme', PATH_ROOT . 'vendor/dioscouri/phalcon-admin/src/Theme/' );
	    $di->get('theme')->registerViewPath( PATH_ROOT . 'vendor/dioscouri/phalcon-admin/src/Views/', 'Admin/Views' );
	    $di->get('theme')->registerEngine(
            array(
                ".php" => 'Phalcon\Mvc\View\Engine\Php'
            )
	    );
	    
	    $router = $di->get('router');
	    $router->mount( new \Dsc\Admin\Routes );
	    
	    /**
	     * Custom authentication component
	     */
	    $di->set('auth', function () {
	        return new \Dsc\Admin\Lib\Auth();
	    });
	    
        /**
         * Mail service uses AmazonSES
        */
        $di->set('mail', function () {
            return new \Dsc\Admin\Lib\Mail();
        });
    
        /**
         * Access Control List
        */
        $di->set('acl', function () {
            return new \Dsc\Admin\Lib\Acl();
        });
                 
	     
	    break;
}
?>