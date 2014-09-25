<?php
require_once 'My/Controller.php';
class IndexController extends My_Controller
{

    public function init()
    {
        /* Initialize action controller here */
    	
 
    	
    }

    public function indexAction()
    {
        // action body

    	if (Zend_Auth::getInstance()->hasIdentity())
    	{
    		$this->_forward('monitorpage/monitorresult');
    	}
    	else
    	{
    		$this->_forward('signin','signpage');
    		
    		
    	}
 	
    }


}

