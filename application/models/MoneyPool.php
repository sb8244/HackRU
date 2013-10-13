<?php

class Application_Model_MoneyPool
{
    private $db;
    
    public function __construct()
    {
    	$m = new MongoClient();
    	$this->db = $m->selectDB('hackru');
    }

    public function addMoneyToPool($amount)
    {
        return $this->db->pool->insert(array('amount' => $amount));
    }
}

