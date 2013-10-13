<?php

class Application_Model_FourSquare
{
    private $client_id = 'HWX4LG3G1CHBYGCQMI3J0P2G1EMTD4EWD2JVI55I1GNKKH0R';
    private $client_secret = 'XDUVOX0BXVVF4WSVTBCS2QDE5WHFXNGII3JJISHVOZ0AJOVW';
    
    private $cache;
    
    public function __construct()
    {
        $this->cache = Zend_Registry::get('cache');
    }
    
    private function generateCredentialsQuery()
    {
        return "?client_id=" . $this->client_id . "&client_secret=" . $this->client_secret;
    }
    
    public function getCategories()
    {
        $results = $this->cache->load("categories");
        if($results === false)
        {
            $url = 'https://api.foursquare.com/v2/venues/categories';
            $url .= $this->generateCredentialsQuery();
            
            $http = new Zend_Http_Client($url);
            $results = $http->request()->getBody();
            
            $this->cache->save($results, "categories");
        }
        $results = json_decode($results, true);
        
        return $results['response']['categories'];
    }
    
    public function getAllowedCategories()
    {
        $results = array();
        $results['4bf58dd8d48988d175941735'] = "Gym / Fitness Center";
        $results['4bf58dd8d48988d118951735'] = 'Grocery Store';
        $results['4bf58dd8d48988d172941735'] = 'Post Office';
        $results['4bf58dd8d48988d131941735'] = 'Spiritual Center';
        $results['4bf58dd8d48988d17c941735'] = 'Casino';
        
        return $results;
    }
}

