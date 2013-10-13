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
        
    }

    public function settingsAction()
    {
        // action body
    }


}



