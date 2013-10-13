<?php

class TasksController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function addAction()
    {
        $user = $this->getInvokeArg('bootstrap')->getResource('currentUser');
        
        $amount = floatval($this->_getParam("amount"));
        $wager = floatval($this->_getParam("wager"));
        $category = $this->_getParam("category");
        
        if($category && $wager && $amount)
        {
            
        }
        die();
    }


}

