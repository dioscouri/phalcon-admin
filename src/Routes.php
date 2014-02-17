<?php
namespace Dsc\Admin;

class Routes extends \Phalcon\Mvc\Router\Group
{
    public function initialize()
    {
        //Default paths
        $this->setPaths(array(
            'module' => 'admin',
            'namespace' => 'Dsc\Admin\Controllers\\'
        ));

        //All the routes start with /admin
        $this->setPrefix('/admin');
	}
}