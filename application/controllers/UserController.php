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
        $categories = $fourSq->getAllowedCategories();
        $this->view->categories = $categories;
    }

    public function settingsAction()
    {
        // action body
    }


}



