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
        
        $wager = floatval($this->_getParam("wager"));
        $category = $this->_getParam("category");
        $start = $this->_getParam("start");
        $end = $this->_getParam("end");
        $title = $this->_getParam("title");
        
        $redirect = $this->_getParam("redirect");
        
        if($category && 
            $wager && 
            $start && 
            $end && 
            $title && 
            $redirect)
        {
            $start = strtotime($start);
            $end = strtotime($end);
            
            $task = array(
                    'category' => $category,
                    'wager' => $wager,
                    'start' => $start,
                    'end' => $end,
                    'success' => false,
                    'completed' => false,
                    'title' => $title   
            );
            
            $userModel = new Application_Model_User();
            $userModel->addTaskToUser($user, $task);
            
            header("Location: " . $redirect);
            die();
        }
        die();
    }

}

