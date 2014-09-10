<?php
class Application_Form_ScheduleInfo extends Zend_Form
{
	public function init()
	{
		$scheduleinfo = $this->createElement('text', 'Schedule',array(
				'label' => 'Add Schedule Information:'
		));
		//((\*)|(\d+((-\d+)|(\d+)+))\s+){5}
		
		
// 		$pattern1 = '/^\*\/[\d+]/';
// 		$pattern2 = '/^(\d+-\d+)\/\d+/';
// 		$pattern3 = '/\*/';
// 		$pattern4 = '/^-?\d*$/';
// 		$pattern5 = '/^[\d]*\-[\d]*$/';
		$scheduleinfo -> addValidator('Regex', false, array('/((\*\s)|(\*\/[\d+]\s)|((\d+-\d+)\/\d+\s)|(\d+\s)|([\d]+\-[\d]+\s)){4}((\*)|(\*\/[\d+])|((\d+-\d+)\/\d+)|(\d+)|([\d]+\-[\d]+))/'));
		$scheduleinfo->setRequired(true);
		
		$this->addElement($scheduleinfo);
		
		
		$this->addElement('submit', 'submit', array(
				'ignore'   => true,
				'label'    => 'Add'
				
		));
		
	}
}