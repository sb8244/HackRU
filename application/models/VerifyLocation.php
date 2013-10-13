<?php

class Application_Model_VerifyLocation
{
    private $tasks;
    public function __construct($user)
    {
        $this->tasks = $user['tasks'];
    }
    
    public function getVerifiedTasksFourSquare($checkins)
    {
        $verifiedTasks = array();
        foreach($checkins as $checkin)
        {
            $time = $checkin['createdAt'];
            $categoryIDs = array();
            foreach($checkin['venue']['categories'] as $category)
            {
                $categoryIDs[] = $category['id'];
            }
            
            $checkinInfo = array(
              'time' => $time,
              'categories' => $categoryIDs     
            );
            
            foreach($this->tasks as $task)
            {
                if($task['start'] <= $time 
                    //doing this to adjust for the fact that a date is set to midnight
                    // but must be midnight of the next day
                    && $task['end'] >= $time - 3600*24
                    && in_array($task['category'], $categoryIDs))
                {
                    $verifiedTasks[] = $task;
                }
            }
        }
        
        return $verifiedTasks;
    }
}

