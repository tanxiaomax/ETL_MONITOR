<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload()
    {
        
        Zend_Loader_Autoloader::getInstance()->registerNamespace("Function")->pushAutoloader(
        new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => dirname(__FILE__),
        )));
        
    }

	public function _initConfig() {
		Zend_Registry::set('config', $this->getOption('configs'));
	}
	
	public function _initLogger(){
		$logger = new My_Logger();
		Zend_Registry::set("logger", $logger);
	}
	

}

