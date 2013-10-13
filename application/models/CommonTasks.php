<?php

class Application_Model_CommonTasks
{
    private $user;
    public function __construct($user)
    {
        $this->user = $user;
    }
    
    public function rankCategories()
    {
        $allCategories = array();
        
        $fourSquare = new Application_Model_FourSquare();
        $fourSquareCats = $fourSquare->getAllowedCategories();
        
        foreach($this->user['tasks'] as $task)
        {
            $category = $task['category'];
            if($task['completed'] && $task['success'])
            {
                if(!isset($allCategories[$category]))
                    $allCategories[$category] = 0;
                $allCategories[$category] ++;
            }
        }
        natsort($allCategories);
        $allCategories = array_reverse($allCategories);
        
        array_splice($allCategories, 2);
        
        foreach($allCategories as $key=>$count)
        {
            $name = $fourSquareCats[$key];
            $allCategories[$key] = array('name' => $name, 'count' => $count, 'id'=>$key);
        }
        
        return $allCategories;
    }
    
    public function countMissed()
    {
        $count = 0;
        $sumMissed = 0;
        foreach($this->user['tasks'] as $task)
        {
        	if($task['completed'] === true && $task['success'] === false)
        	{
        	    $sumMissed += $task['wager'];
        		$count++;
        	}
        }
        return array('sum'=>$sumMissed, 'count'=>$count );
    }
}

