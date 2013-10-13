<?php

class Application_Model_VerifyLocation
{
    private $user;
    private $tasks;
    
    public function __construct($user)
    {
        $this->user = $user;
        $this->tasks = $user['tasks'];
    }
    
    public function setVerifiedTasksFourSquare($checkins)
    {
        $userModel = new Application_Model_User();
        $moneyPool = new Application_Model_MoneyPool();
        $processedTask = array();
        foreach($checkins as $checkin)
        {
            $time = $checkin['createdAt'];
            $categoryIDs = array();
            foreach($checkin['venue']['categories'] as $category)
            {
                $categoryIDs[] = $category['id'];
            }
            
            foreach($this->tasks as $key=>$task)
            {
                if(in_array($task, $processedTask))
                {
                    continue;
                }
                if( $task['completed'] === false && 
                    $task['start'] <= $time 
                    //doing this to adjust for the fact that a date is set to midnight
                    // but must be midnight of the next day
                    && $task['end'] >= $time - 3600*24
                    && in_array($task['category'], $categoryIDs))
                {
                    $this->user['tasks'][$key]['completed'] = true;
                    $this->user['tasks'][$key]['success'] = true;
                    $this->user['money']['onhold'] -= $task['wager'];
                    $this->user['money']['free'] += $task['wager'];
                    $processedTask[] = $task;
                }
                else if($task['completed'] === false && 
                    $task['end'] < $time )
                {
                    $this->user['tasks'][$key]['completed'] = true;
                    $this->user['tasks'][$key]['success'] = false;
                    $this->user['money']['onhold'] -= $task['wager'];
                    $moneyPool->addMoneyToPool($task['wager']);
                    $processedTask[] = $task;
                }
            }
        }
        $userModel->saveUser($this->user);
        return true;
    }
}

