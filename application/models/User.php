<?php

class Application_Model_User
{
    private $db;
    
    public function __construct()
    {
        $m = new MongoClient();
        $this->db = $m->selectDB('hackru');
    }
    
    public function create($user)
    {
        
    }
    
    public function findOne($id)
    {
        if($id)
        {
            $user = $this->db->collection->findOne(array("_id"=>new MongoId($id)));
            return $user;
        }
        return null;
    }
    
    public function getHeldFundsForUser($user)
    {
        if($user != null)
        {
            $id = $user['_id'];
            $query = array(
                    "_id" => $id,
                    "tasks.success" => false,
                    "tasks.completed" => false
            );
            $projection = array(
                    "tasks.wager" => true      
            );
            $res = $this->db->collection->findOne($query, $projection);
            $wager = 0;
            if(is_array($res['tasks']))
            {
                foreach($res['tasks'] as $task)
                {
                    $wager += $task['wager'];
                }
            }
            return $wager;
        }
        return null;
    }
    
    public function getTodaysTasksForUser($user)
    {
        if($user != null)
        {
            $ret = array();
            $now = time();
            
            $fourSqModel = new Application_Model_FourSquare();
            $categories = $fourSqModel->getAllowedCategories();
            
        	foreach($user['tasks'] as $task)
        	{
        	    //doing this to adjust for the fact that a date is set to midnight
        	    // but must be midnight of the next day
        	    if($task['completed'] === false && 
        	       $task['start'] <= $now && 
        	       $task['end'] >= $now - 3600*24)
        	    {
        	        $task['category-name'] = $categories[$task['category']];
        	        $ret[] = $task;
        	    }
        	}
        	return $ret;
        }
        return array();
    }
    
    public function addFundsToUser($user, $amount)
    {
        if($user != null && $amount != null)
        {
            $amount = (int)$amount;
            $id = $user['_id'];
            $incAll = array(
                    '$inc' => array('money.alltime' => $amount, 'money.free' => $amount)
            );
            return $this->db->collection->update(array("_id"=>$id), $incAll);
        }
        throw new Exception("User or amount was not given");
    }
    
    public function editFreeMoney($user, $amount)
    {
        if($user != null && $amount != null)
        {
        	$amount = (int)$amount;
        	$id = $user['_id'];
        	$incAll = array(
        			'$inc' => array('money.free' => $amount)
        	);
        	return $this->db->collection->update(array("_id"=>$id), $incAll);
        }
        throw new Exception("User or amount was not given");
    }
    
    public function editHeldMoney($user, $amount)
    {
    	if($user != null && $amount != null)
    	{
    		$amount = (int)$amount;
    		$id = $user['_id'];
    		$incAll = array(
    				'$inc' => array('money.onhold' => $amount)
    		);
    		return $this->db->collection->update(array("_id"=>$id), $incAll);
    	}
    	throw new Exception("User or amount was not given");
    }
    
    public function addTaskToUser($user, $task)
    {
        if($user != null && $task != null)
        {
            $id = $user['_id'];
            $push = array(
                  '$push' => array ('tasks' => $task)
            );
            $this->db->collection->update(array("_id"=>$id), $push);
            
            $this->editFreeMoney($user, -1 * $task['wager']);
            $this->editHeldMoney($user, $task['wager']);
            
            return true;
        }
        throw new Exception("Shit");
    }
    
    public function saveUser($user)
    {
        if($user)
        {
            return $this->db->collection->save($user);
        }
        throw new Exception("New User");
    }
}

