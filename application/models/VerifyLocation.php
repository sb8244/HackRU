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
            
            //For each tasks, check that $task['start'] >= $time && $task['end'] <= $time
            // AND $task['category'] is in $categoryIDs, then it was a success and needs to be marked
            // Don't fail tasks here though
        }
    }
}

