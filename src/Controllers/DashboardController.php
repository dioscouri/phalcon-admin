<?php
namespace Dsc\Admin\Controllers;

class DashboardController extends \Dsc\Admin\Controllers\BaseAuth
{
    public function indexAction()
    {
        $this->view->disable();
        echo $this->theme->renderTheme('Admin/Views::Dashboard/index');
    }

}
?>