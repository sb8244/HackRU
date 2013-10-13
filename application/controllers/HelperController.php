<?php

class HelperController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function categoriesAction()
    {
        $fourSq = new Application_Model_FourSquare();
        $categories = $fourSq->getAllowedCategories();
        
        die(json_encode($categories));
    }


}

