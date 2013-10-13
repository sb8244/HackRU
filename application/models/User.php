<?php

class Application_Model_User
{
    private $db;
    
    public function __construct()
    {
        $m = new MongoClient();
        $this->db = $m->selectDB('hackru');
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
}

