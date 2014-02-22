<?php
namespace Dsc\Admin\Controllers;

class BaseAuth extends \Dsc\Admin\Controllers\Base
{
    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName(); // this is not namespaced
        $controllerName = $dispatcher->getHandlerClass(); // this IS namespaced
    
        // Only check permissions on private controllers
        // By virtue of extending BaseAuth, this is a private controller
        // Get the current identity
        $identity = $this->auth->getIdentity();
        
        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
        
            $this->flashSession->warning('Please sign in.');
        
            $dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'login'
            ));
        
            return false;
        }
        
        //$this->flash->notice( \Dsc\Lib\Debug::dump( $identity ) );
        
        // Check if the user have permission to the current option
        $actionName = $dispatcher->getActionName();
        if (!$this->acl->isAllowed($identity['profile'], $controllerName, $actionName)) {
        
            $this->flash->warning('You don\'t have access to: ' . $controllerName . ' : ' . $actionName);
        
            if ($this->acl->isAllowed($identity['profile'], $controllerName, 'index')) {
                $dispatcher->forward(array(
                    'controller' => $controllerName,
                    'action' => 'index'
                ));
            } else {
                $dispatcher->forward(array(
                    'controller' => 'User_Control',
                    'action' => 'index'
                ));
            }
        
            return false;
        }
    }
}
?>