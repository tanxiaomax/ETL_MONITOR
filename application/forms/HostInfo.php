<?php
class Application_Form_HostInfo extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $hostname = $this->createElement('text', 'hostname',array(
        	'label' => 'Host Name:'
        ));
        $hostname->setRequired(true);
        
        $ip = $this->createElement('text', 'ip',array(
        	'label' => 'IP:',
            'validators' => array(
             array('validator' => 'StringLength', 'options' => array(7, 15))
            )
        ));
        
        $ip->setRequired(true);
        
        $username = $this->createElement('text', 'username',array(
        		'label' => 'User Name:'
        ));
        
        $username->setRequired(true);

        $password = $this->createElement('password', 'password',array(
        		'label' => 'User password:'
        ));
        
        $password->setRequired(true);
        
       
 
        $this->addElement($hostname);
        $this->addElement($ip);
        $this->addElement($username);
        $this->addElement($password);
      
        $this->addElement('submit', 'submit', array(
         'ignore'   => true,
         'label'    => 'Submit',
        ));
    }
}