<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $fsModel = new Application_Model_FourSquare();
        $fsModel->loadSession();
        $res = $fsModel->getSelfCheckins();
    }

    public function callbackAction()
    {
        $user = $this->getInvokeArg('bootstrap')->getResource('currentUser');
        $fsModel = new Application_Model_FourSquare();
        $code = $this->_getParam("code");
        if($code)
        {
            $fsModel->setTokenFromCode($code);
        }
        
        $checkins = $fsModel->getSelfCheckins();
        
        if($checkins)
        {
            $verifyModel = new Application_Model_VerifyLocation($user);
            $userModel = new Application_Model_User();
            $verifyModel->setVerifiedTasksFourSquare($checkins);
        }
        
        $this->redirect('/user');
        
        die();
    }
}

