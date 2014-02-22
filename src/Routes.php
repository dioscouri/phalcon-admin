<?php
namespace Dsc\Admin;

class Routes extends \Phalcon\Mvc\Router\Group
{
    public function initialize()
    {
        //Default paths
        $this->setPaths(array(
            'module' => 'phalcon-admin',
            'namespace' => 'Dsc\Admin\Controllers\\'
        ));

        //All the routes start with /admin
        $this->setPrefix('/admin');
        
        $this->add('', array(
                'controller' => 'Dashboard',
                'action' => 'index'
        ));
        
        $this->add('/session/login', array(
                'controller' => 'Session',
                'action' => 'login'
        ));
        
        $this->add('/session/logout', array(
                'controller' => 'Session',
                'action' => 'logout'
        ));
        
        $this->add('/users', array(
                'controller' => 'Users',
                'action' => 'index'
        ))->setName('list-users');

        $this->add('/users/create', array(
                'controller' => 'Users',
                'action' => 'create'
        ))->setName('create-user');
        
        $this->add('/users/edit/:params', array(
                        'controller' => 'Users',
                        'action' => 'edit',
                        'params' => 1
        ))->setName('edit-user');
        
	}
}