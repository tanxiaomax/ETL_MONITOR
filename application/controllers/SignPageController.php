<?php

require_once 'My/Controller.php';


Class SignPageController extends My_Controller
{
	public function signinAction()
	{
		$form = new Application_Form_SigninInfo();
		$this->view->form = $form;
		$request = $this->getRequest();
		
		
		$config = new Zend_Config_Ini(APPLICATION_PATH ."/configs/application.ini", null, true);
		Zend_Registry::set('config', $config);
		
		$dbAdapter=Zend_Db::factory($config->production->resources->db->adapter, 
				$config->production->resources->db->params->toArray());

		
		if (empty($dbAdapter))
		{
			//
			;
		}
		
		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
		
		$authAdapter
		->setTableName('USERS')
		->setIdentityColumn('USERID')
		->setCredentialColumn('PASSWORD');
	
		
		
		if($this->getRequest()->isPost())
		{
			if($form->isValid($request->getpost()))
			{
				$username = $form->getValue("username");
				$password = $form->getValue("password");
				
				$authAdapter
				->setIdentity($username)
				->setCredential($password);
				
				$result = $authAdapter->authenticate();
				
				if ($result->isValid())
				{
					$storage = Zend_Auth::getInstance()->getStorage();	
					
					$storage->write($authAdapter->getResultRowObject(array(
							'USERID',
							'GROUP',
							'ROLE'
					)));
					
					
					$this->_redirect('/monitorpage/monitorresult');
	
				}
				

			}
			else
			{
				$this->view->form = $form;
				return $this->render('form');
			}
		}
		
		
	}
}