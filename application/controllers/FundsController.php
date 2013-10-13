<?php

class FundsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function addAction()
    {
        $user = $this->getInvokeArg('bootstrap')->getResource('currentUser');
        
        $amount = (int)$this->_getParam("amount");
        
        $response = array('status' => 500);
        
        if($amount && $amount >= 10)
        {
            $userModel = new Application_Model_User();
            $res = $userModel->addFundsToUser($user, $amount);
            
            $response['status'] = '200';
            $response['free'] = '$' . number_format($user['money']['free'] + $amount, 2);
        }
        else
        {
            $this->getResponse()->setHttpResponseCode(500);    
        }
        
        die(json_encode($response));
    }

}

