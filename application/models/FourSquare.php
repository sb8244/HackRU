<?php
require_once("FoursquareAPI.class.php");
class Application_Model_FourSquare
{
    private $client_id = 'HWX4LG3G1CHBYGCQMI3J0P2G1EMTD4EWD2JVI55I1GNKKH0R';
    private $client_secret = 'XDUVOX0BXVVF4WSVTBCS2QDE5WHFXNGII3JJISHVOZ0AJOVW';
    private $redirect_uri = "http://localhost/hackru/public/index/callback";
    
    private $cache;
    private $model;
    private $token;
    
    public function __construct()
    {
        $this->cache = Zend_Registry::get('cache');
        $this->model = new FoursquareAPI($this->client_id, $this->client_secret);
    }
    
    public function getRedirectUrl()
    {
        return $this->redirect_uri;
    }
    
    private function generateCredentialsQuery()
    {
        return "?client_id=" . $this->client_id . "&client_secret=" . $this->client_secret;
    }
    
    public function setTokenFromCode($code)
    {
        $token = $this->model->GetToken($code,$this->redirect_uri);
        $this->setToken($token);
    }
    
    public function setToken($token)
    {
        $this->model->SetAccessToken($token);
        $this->token = $token;
        $session = Zend_Registry::get('session');
        $session->token = $token;
    }
    
    public function loadSession()
    {
        $session = Zend_Registry::get('session');
        if(isset($session->token))
        {
            $this->setToken($session->token);
        }
        else
        {
            $this->redirect();
        }
    }
    
    private function redirect()
    {
        header("Location: " . $this->getOAuthUrl());
        die();
    }
    public function isTokenSet()
    {
        return isset($this->token) && $this->token != null ;
    }
    
    public function getOAuthUrl()
    {
        $uri = "https://foursquare.com/oauth2/authenticate?client_id=";
        $uri .= $this->client_id . "&response_type=code&redirect_uri=" . $this->redirect_uri;
        return $uri;
    }
    public function getSelfCheckins()
    {
        $response = $this->model->GetPrivate("users/self/checkins");
        $res = json_decode($response,true);
        if(isset($res['meta']['errorType']) && $res['meta']['errorType'] == 'invalid_auth')
        {
            $this->redirect();
        }
        
        
        return $res['response']['checkins']['items'];
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

