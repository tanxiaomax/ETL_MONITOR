<?php
class Application_Form_DBInfo extends Zend_Form
{
    public function init()
    {
        
        $host = new Application_Model_DbTable_Hosts();
        $hostinfo= $host->fetchAll();
        
        
        for($i = 0; $i < $hostinfo->count(); $i++)
        {
            $row = $hostinfo->current();     
            $array = $row->toArray();
            
            $hostname[$array['HOSTNAME']] = $array['HOSTNAME'];
            
            $hostinfo->next();
        }
   
      
        
        $this->addElement('select','hosts',
                 array(
                'label'        => 'Hosts',
                'multiOptions' =>  $hostname
                )  
        );
        
        
        $username = $this->createElement('text', 'username',array(
        		'label' => 'User name:'
        ));
        $username->setRequired(true);
        
          
        $password = $this->createElement('password', 'password',array(
        		'label' => 'Password:'
        ));     
        $password->setRequired(true);
        
       
        $database = $this->createElement('text', 'database',array(
        		'label' => 'DataBase:'
        ));
        $database->setRequired(true);
        

        
        $port = $this->createElement('text', 'port',array(
        		'label' => 'Port:'
        ));
       
        $port->addValidator('regex', false, array('/^[0-9]/i'));
        $port->setRequired(true);
        
        
        
        
        $this->addElement($username);
        $this->addElement($password);
        $this->addElement($database);
        $this->addElement($port);
        
        $this->addElement('submit', 'submit', array(
        		'ignore'   => true,
        		'label'    => 'Submit',
        ));
     
        
        
    }
}  