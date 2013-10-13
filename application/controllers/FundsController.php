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
        
        $response = array('status' => 500);
        
        if($amount && $amount >= 10)
        {
            $userModel = new Application_Model_User();
            $res = $userModel->addFundsToUser($user, $amount);
            
            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here https://manage.stripe.com/account
            Stripe::setApiKey("sk_test_mkGsLqEW6SLnZa487HYfJVLf");

            // Get the credit card details submitted by the form
            $token = $_POST['stripeToken'];

            // Create the charge on Stripe's servers - this will charge the user's card
            try {
            $charge = Stripe_Charge::create(array(
              "amount" => $amount, // amount in cents, again
              "currency" => "usd",
              "card" => $token,
              "description" => "payinguser@example.com")
            );
            } catch(Stripe_CardError $e) {
              // The card has been declined
                $this->getResponse()->setHttpResponseCode(500);
            }

            $response['status'] = '200';
            $response['free'] = '$' . number_format($user['money']['free'] + $amount, 2);
        }
        else
        {
            $this->getResponse()->setHttpResponseCode(500);    
        }
        
        die(json_encode($response));
    }
}

