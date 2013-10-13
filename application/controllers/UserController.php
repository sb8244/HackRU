<?php

class UserController extends Zend_Controller_Action
{

    private $user = null;

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $user = $this->getInvokeArg('bootstrap')->getResource('currentUser');
        $fourSq = new Application_Model_FourSquare();
        $userModel = new Application_Model_User();
        
        $categories = $fourSq->getAllowedCategories();
        $this->view->categories = $categories;
        
        $todayTasks = $userModel->getTodaysTasksForUser($user);
        $this->view->todayTasks = $todayTasks;
    }

    public function settingsAction()
    {
        // action body
    }


}



