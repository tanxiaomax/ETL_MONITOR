<?php
class Application_Form_SigninInfo extends Zend_Form
{
	public function init()
	{
		Zend_Layout::getMvcInstance()->disableLayout();
		
		
		$this->setMethod('post');
		$this->setAttrib('class', 'form-signin');
	
		$username = $this->createElement('text', 'username');
		$username -> setLabel('Intranet ID');
		$username -> setRequired(true);
		
		$username -> setAttrib('class', 'form-control');
		
		$password = $this->createElement('password', 'password');
		$password -> setLabel('Password');
		$password -> setRequired(true);
		
		$password -> setAttrib('class', 'form-control');
		
		
		$submit = $this -> createElement('submit', 'submit');
		$submit -> setAttrib('class', 'btn btn-lg btn-primary btn-block');
		$submit -> setLabel('Signin');
		
		
		$this->addElement($username);
		$this->addElement($password);
		$this->addElement($submit);
		
	}
}