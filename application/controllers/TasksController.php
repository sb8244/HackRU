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
        
        $duration = floatval($this->_getParam("duration"));
        $wager = floatval($this->_getParam("wager"));
        $category = $this->_getParam("category");
        $start = $this->_getParam("start");
        $end = $this->_getParam("end");

        $redirect = $this->_getParam("redirect");
        
        if($category && $wager && $duration && $start && $end && $redirect)
        {
            $start = strtotime($start);
            $end = strtotime($end);
            
            $task = array(
                    'category' => $category,
                    'wager' => $wager,
                    'duration' => $duration,
                    'start' => $start,
                    'end' => $end,
                    'success' => false,
                    'completed' => false     
            );
            
            $userModel = new Application_Model_User();
            $userModel->addTaskToUser($user, $task);
            
            header("Location: " . $redirect);
            die();
        }
        die();
    }

}

