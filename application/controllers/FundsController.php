<?php

class FundsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function addAction()
    {
        $user = $this->getInvokeArg('bootstrap')->getResource('currentUser');
        
        $amount = (int)$this->_getParam("amount");
        $token = $this->_getParam("token");
        
        $response = array('status' => 500);
        
        if($amount && $amount >= 10 && $token)
        {
            require_once("Stripe.php");
            
            Stripe::setApiKey("sk_test_mkGsLqEW6SLnZa487HYfJVLf");

            try {
                $charge = Stripe_Charge::create(array(
                  "amount" => $amount * 100, // amount in cents, again
                  "currency" => "usd",
                  "card" => $token,
                  "description" => $user['_id']->__toString())
                );
    
                $userModel = new Application_Model_User();
                $res = $userModel->addFundsToUser($user, $amount);

                $response['status'] = '200';
                $response['free'] = '$' . number_format($user['money']['free'] + $amount, 2);
            } catch(Stripe_CardError $e) {
                $response['error'] = $e->getMessage();
              // The card has been declined
                $this->getResponse()->setHttpResponseCode(500);
            }
        }
        else
        {
            $response['error'] = "Incorrect parameters";
            $this->getResponse()->setHttpResponseCode(500);    
        }
        
        die(json_encode($response));
    }
}

