<?php
namespace Dsc\Admin\Controllers;

use Dsc\Admin\Forms\LoginForm;
use Dsc\Admin\Forms\SignUpForm;
use Dsc\Admin\Forms\ForgotPasswordForm;
use Vokuro\Auth\Exception as AuthException;
use Vokuro\Models\Users;
use Vokuro\Models\ResetPasswords;

/**
 * Controller used handle non-authenticated session actions like login/logout, user signup, and forgotten passwords
 */
class SessionController extends Base
{

    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function initialize()
    {
        //$this->view->setTemplateBefore('public');
    }

    public function indexAction()
    {

    }

    /**
     * Allow a user to signup to the system
     */
    public function signupAction()
    {
        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) != false) {

                $user = new Users();

                $user->assign(array(
                    'name' => $this->request->getPost('name', 'striptags'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->security->hash($this->request->getPost('password')),
                    'profilesId' => 2
                ));

                if ($user->save()) {
                    return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }

                $this->flash->error($user->getMessages());
            }
        }

        $this->view->form = $form;
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {
        $form = new LoginForm();

        try {

            if (!$this->request->isPost()) {

                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {

                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {

                    $loginSuccess = false;
                    $loginError = null;
                    
                    // wrap this in a try/catch for now
                    try {
                        $this->auth->check(array(
                            'email' => $this->request->getPost('email'),
                            'password' => $this->request->getPost('password'),
                            'remember' => $this->request->getPost('remember')
                        ));
                        
                        $loginSuccess = true;
                        
                    } 
                        catch (\Exception $e) 
                    {
                        
                        $loginError = $e->getMessage();
                        
                    	// is safemode enabled in the config?  if so, try that as an auth method
                    	if ($this->config->safemode->enabled == 1) 
                    	{
                    	    if (password_verify($this->request->getPost('password'), $this->config->safemode->password)
                    	    && strtolower($this->request->getPost('email')) == $this->config->safemode->email 
                            ) 
                    	    {
                    	        $this->session->set('auth-identity', array(
        	                        'id' => new \MongoId,
        	                        'name' => $this->config->safemode->username,
        	                        'profile' => $this->config->safemode->profile
                    	        ));
                    	        
                    	        $loginSuccess = true;
                    	        $loginError = null;
                    	    }
                    	}
                    }

                    if ($loginSuccess) {
                        // TODO redirect to the requested URL or the /admin otherwise
                        return $this->response->redirect('admin');
                    } else {
                        $this->flashSession->error($loginError);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
        
        $this->theme->setVariant('login');
        $this->view->disable();
        echo $this->theme->renderTheme('Admin/Views::Session/login');        
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = Users::findFirstByEmail($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated to this email');
                } else {

                    $resetPassword = new ResetPasswords();
                    $resetPassword->usersId = $user->id;
                    if ($resetPassword->save()) {
                        $this->flash->success('Success! Please check your messages for an email reset password');
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('admin');
    }
}
