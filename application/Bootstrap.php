<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initSession()
    {
        $myNamespace = new Zend_Session_Namespace('hackru');
        Zend_Registry::set('session', $myNamespace);
        
        $myNamespace->id = '525a0f5fe1aa3bd84540cd86';
    }
    
    public function _initCurrentUser()
    {
        $session = Zend_Registry::get('session');
        $id = $session->id; 
        $userModel = new Application_Model_User();
        $user = $userModel->findOne($id);

        return $user;
    }
    
    public function _initCache()
    {
    	$frontendOptions = array(
    			'lifetime' => 3600 * 24 * 5, // cache lifetime of 5 days
    			'automatic_serialization' => true
    	);
    
    	$backendOptions = array(
    			'cache_dir' => '../tmp/' // Directory where to put the cache files
    	);
    
    	// getting a Zend_Cache_Core object
    	$cache = Zend_Cache::factory('Core',
    			'File',
    			$frontendOptions,
    			$backendOptions);
    
    	Zend_Registry::set('cache', $cache);
    }
}

