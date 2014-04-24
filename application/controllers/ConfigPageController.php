<?php

class ConfigPageController extends Zend_Controller_Action
{
    public function hostinfoAction()
    {
        $request = $this->getRequest();
    	$form    = new Application_Form_HostInfo();
    	
    	
    	if($this->getRequest()->isPost())
    	{
    	    if ($form->isValid($request->getPost())) {
    	        $hostinfo = new Application_Model_DbTable_Hosts();    	        
    	        $newRow = $hostinfo->createRow();
    	        $newRow->HOSTNAME= $form->getValue('hostname');
    	        $newRow->IP= $form->getValue('ip');
    	        $newRow->USER= $form->getValue('username');
    	        $newRow->PASSWORD= $form->getValue('password');
    	        $newRow->save();
    	    }
    	}
   	
    	$this->view->form = $form;
     
    }
    
    public function dbinfoAction()
    {
        //$request = $this->getRequest();
        $form = new Application_Form_DBInfo();
        $request = $this->getRequest();
       
       
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($request->getPost()))
            {
                $DBINFO = new Application_Model_DbTable_DBS();
                $newrow = $DBINFO->createRow();
                $newrow->HOSTNAME = $form->getValue('hosts');
                $newrow->PORT = $form->getValue('port');
                $newrow->USERNAME = $form->getValue('username');
                $newrow->PASSWORD = $form->getValue('password');
                $newrow->DBNAME = $form->getValue('database');
                $newrow->save();
               
                
            }
        }
        $this->view->form = $form;
            
   
    }
    
}