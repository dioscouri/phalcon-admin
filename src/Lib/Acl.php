<?php
namespace Dsc\Admin\Lib;

use Dsc\Admin\Models\Profiles;

/**
 * Dsc\Admin\Lib\Acl
 */
class Acl extends \Phalcon\Mvc\User\Component
{

    /**
     * The ACL Object
     *
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $acl;

    /**
     * Define the resources that are considered "private". These controller => actions require authentication.
     *
     * @var array
     */
    private $privateResources = array();

    /**
     * Human-readable descriptions of the actions used in {@see $privateResources}
     *
     * @var array
     */
    private $actionDescriptions = array(
        'index' => 'Access',
        'search' => 'Search',
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'changePassword' => 'Change password'
    );

    /**
     * Checks if a controller is private or not
     *
     * @param string $controllerName
     * @return boolean
     */
    public function isPrivate($controllerName)
    {
        return isset($this->privateResources[$controllerName]);
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $profile
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed($profile, $controller, $action)
    {
        return $this->getAcl()->isAllowed($profile, $controller, $action);
    }

    /**
     * Returns the ACL list
     *
     * @return Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        // Check if the ACL is already created
        if (is_object($this->acl)) {
            return $this->acl;
        }

        // Check if the ACL is in APC
        /*
        if (function_exists('apc_fetch')) {
            $acl = apc_fetch('phalcon-admin-acl');
            if (is_object($acl) && !empty($acl)) {
                $this->acl = $acl;
                return $acl;
            }
        }*/

        // Check if the ACL is already generated
        $data = unserialize( $this->mongo_cache->get('phalcon-admin-acl') );
        if (empty($data)) 
        {
            $data = $this->rebuild();       	
        }
        
        // TODO Re-enable this
        //$this->mongo_cache->save('phalcon-admin-acl', serialize($acl));
        // Store the ACL in APC
        /*
        if (function_exists('apc_store')) {
            apc_store('phalcon-admin-acl', $this->acl);
        }
        */

        $this->acl = $data;
        
        //$this->mongo_cache->save('phalcon-admin-acl-memory', \Dsc\Lib\Debug::dump($this->acl, false) );

        return $this->acl;
    }

    /**
     * Returns the permissions assigned to a profile
     *
     * @param Profiles $profile
     * @return array
     */
    public function getPermissions(Profiles $profile)
    {
        $permissions = array();
        foreach ($profile->getPermissions() as $permission) {
            $permissions[$permission->resource . '.' . $permission->action] = true;
        }
        return $permissions;
    }

    /**
     * Returns all the resoruces and their actions available in the application
     *
     * @return array
     */
    public function getResources()
    {
        return $this->privateResources;
    }

    /**
     * Returns the action description according to its simplified name
     *
     * @param string $action
     * @return $action
     */
    public function getActionDescription($action)
    {
        if (isset($this->actionDescriptions[$action])) {
            return $this->actionDescriptions[$action];
        } else {
            return $action;
        }
    }

    /**
     * Rebuilds the access list into a file
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function rebuild()
    {
        $options = array(
        	'dbhost' => $this->config->mongo->host,
            'dbname' => $this->config->mongo->dbname,
            'roles' => 'acl.roles',
            'resources' => 'acl.resources',
            'resourcesAccesses' => 'acl.resourcesAccesses',
            'accessList' => 'acl.accessList'
        );
        $acl = new \Dsc\Admin\Lib\Acl\Adapter\Mongo($options);
        //$acl = new \Phalcon\Acl\Adapter\Memory();
        
        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        // Register roles
        $profiles = Profiles::find(array(
        	array('active' => 'Y')
        ));

        // give super profile access to everything
        $acl->addRole(new \Phalcon\Acl\Role('super'));
        $acl->allow('super', '*', '*');
        $acl->allow('*', 'Dsc\Admin\Controllers\DashboardController', 'index');
        
        foreach ($profiles as $profile) {
            $acl->addRole(new \Phalcon\Acl\Role($profile->name));
        }

        foreach ($this->privateResources as $resource => $actions) {
            $acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
        }

        // Grant acess to private area to role Users
        foreach ($profiles as $profile) {

            // Grant permissions in "permissions" model
            foreach ($profile->getPermissions() as $permission) {
                $acl->allow($profile->name, $permission->resource, $permission->action);
            }

            // Always grant these permissions
            $acl->allow($profile->name, 'users', 'changePassword');
        }

        return $acl;
    }
}
