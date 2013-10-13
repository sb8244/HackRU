<?php

class Zend_View_Helper_UserFunds extends Zend_View_Helper_Abstract
{	
	public function userFunds()
	{
	    $user = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('currentUser');
	    
	    $html = "";
	    if($user)
	    {
    	    $available = '$' . number_format($user['money']['free'], 2);
    	    $held = '$' . number_format($user['money']['onhold'], 2);
    	    
    	    $html .= "<span class='funds'>";
    	       $html .= "<span class='available'>" . $available . "</span>";
    	       $html .= "<span class='held'>" . $held . "</span>";
    	       
    	    $html .= "</span>";;
	    }
    	    
    	return $html;
	}
}

?>