<?php

class UserController extends Zend_Controller_Action
{
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
        
        $this->view->allTasks = $user['tasks'];
        
        $rankModel = new Application_Model_CommonTasks($user);
        $this->view->top = $rankModel->rankCategories();
        
        $this->view->missed = $rankModel->countMissed();
        
    }

    public function settingsAction()
    {
        // action body
    }
    
    public function forcepassAction()
    {
        die();
        $user = $this->getInvokeArg('bootstrap')->getResource('currentUser');
        $verifyModel = new Application_Model_VerifyLocation($user);
        $verifyModel->forceOverrideVerifiedTasksFourSquare();
        
        die('completed le task override');
    }


}



