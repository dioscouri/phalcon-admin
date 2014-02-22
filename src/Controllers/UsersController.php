<?php
namespace Dsc\Admin\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Dsc\Lib\Paginator;
use Dsc\Admin\Forms\ChangePasswordForm;
use Dsc\Admin\Forms\UsersForm;
use Dsc\Admin\Models\Users;
use Dsc\Admin\Models\PasswordChanges;

/**
 * Dsc\Admin\Controllers\UsersController
 * CRUD to manage users
 */
class UsersController extends BaseAuth
{
    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
        $this->view->disable();
        $this->persistent->conditions = null;
        $this->view->form = new UsersForm();
        
        $this->view->model = new Users;
        
        $this->view->state = $this->view->model->populateState()->getState();
        $this->view->params = $this->view->model->getParam();
        $this->view->page = $this->view->model->paginate(); 
        
        //$users = Users::find();
        //echo \Dsc\Lib\Debug::dump( $users );
        //echo \Dsc\Lib\Debug::dump( $this->view->page );
        //echo \Dsc\Lib\Debug::dump( $this->view->params );
        
        //$this->flash->notice( \Dsc\Lib\Debug::dump( $this->view->page ) );
        
        /*
        $numberPage = 1;
        if ($this->request->isPost()) {
            $this->persistent->searchParams = array();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
                
        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        
        $users = Users::find($parameters);
        //$this->flash->notice( \Dsc\Lib\Debug::dump( $users ) );
        
        $paginator = new Paginator(array(
                        "data" => $users,
                        "limit" => 10,
                        "page" => $numberPage
        ));
        
        $this->view->pagination = $paginator->getPaginate();
        
        //echo \Dsc\Lib\Debug::dump( $this->view->pagination->items[0] );
        
        
        //echo \Dsc\Lib\Debug::dump( get_object_vars($this->view->pagination->items[0]) );
        
        //$this->view->pagination->items[0]->{'some.deep.nested.value'} = 'rafeee';
        //$this->flash->notice( \Dsc\Lib\Debug::dump( $this->view->pagination->items[0]->{'some.deep.nested.value'} ) );
        
        //$this->flash->notice( \Dsc\Lib\Debug::dump( $this->view->pagination ) );
         * 
         */
        
        echo $this->theme->renderTheme('Admin/Views::Users/index');
    }

    /**
     * Searches for users
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Dsc\Admin\Models\Users', $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $users,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a User
     */
    public function createAction()
    {
        if ($this->request->isPost()) {

            $user = new Users();

            $user->assign(array(
                'first_name' => $this->request->getPost('first_name', 'striptags'),
                'last_name' => $this->request->getPost('last_name', 'striptags'),
                'username' => $this->request->getPost('username', 'striptags'),
                'email' => $this->request->getPost('email', 'email')
            ));

            $this->flash->notice( \Dsc\Lib\Debug::dump($user) );
            
            if (!$user->save()) {
                $this->flash->error($user->getMessages());
            } else {

                $this->flash->success("User was created successfully");

                Tag::resetInput();
            }
        }

        $this->view->form = new UsersForm(null);
        
        $this->view->disable();
        $this->view->event = \Dsc\Lib\System::instance()->trigger( 'onDisplayAdminUserEdit', array( 'item' => array(), 'tabs' => array(), 'content' => array() ) );
        echo $this->theme->renderTheme('Admin/Views::Users/create');        
    }

    /**
     * Saves the user from the 'edit' action
     */
    public function editAction($id)
    {
        $user = Users::findById($id);
        if (!$user) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        if ($this->request->isPost()) {

            $user->assign(array(
                'name' => $this->request->getPost('name', 'striptags'),
                'profilesId' => $this->request->getPost('profilesId', 'int'),
                'email' => $this->request->getPost('email', 'email'),
                'banned' => $this->request->getPost('banned'),
                'suspended' => $this->request->getPost('suspended'),
                'active' => $this->request->getPost('active')
            ));

            if (!$user->save()) {
                $this->flash->error($user->getMessages());
            } else {

                $this->flash->success("User was updated successfully");

                Tag::resetInput();
            }
        }

        $this->view->user = $user;

        $this->view->form = new UsersForm($user, array(
            'edit' => true
        ));
        
        echo $this->theme->renderTheme('Admin/Views::Users/edit');
    }

    /**
     * Deletes a User
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        if (!$user->delete()) {
            $this->flash->error($user->getMessages());
        } else {
            $this->flash->success("User was deleted");
        }

        return $this->dispatcher->forward(array(
            'action' => 'index'
        ));
    }

    /**
     * Users must use this action to change its password
     */
    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = $this->auth->getUser();

                $user->password = $this->security->hash($this->request->getPost('password'));
                $user->mustChangePassword = 'N';

                $passwordChange = new PasswordChanges();
                $passwordChange->user = $user;
                $passwordChange->ipAddress = $this->request->getClientAddress();
                $passwordChange->userAgent = $this->request->getUserAgent();

                if (!$passwordChange->save()) {
                    $this->flash->error($passwordChange->getMessages());
                } else {

                    $this->flash->success('Your password was successfully changed');

                    Tag::resetInput();
                }
            }
        }

        $this->view->form = $form;
    }
}
