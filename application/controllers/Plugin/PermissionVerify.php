<?php
class PermissionVerify extends Zend_Controller_Plugin_Abstract
{
	protected $_acl;
	
	protected $_userid;
	
	public function __construct($acl)
	{
		$this -> _acl = $acl;
		$this -> _userid = Zend_Auth::getInstance()->getIdentity();
	}
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		//������Ϣ
		$module     = $request -> module;                //ģ��
		$controller = $request -> controller;            //����Ŀ�����
		$action     = $request -> action;                //�����action
	
		$resource = strtoupper($controller);    //��Դ��һ�����Ʒ��ʵĶ���
		$action   = strtoupper($action);
		$role     = strtoupper($this->_acl->GetRole());                //��ɫ��һ�����Է�������ȥ����Resource�Ķ���

		
		
		
		//�ж��Ƿ�ӵ����Դ
		if(!($this -> _acl -> has($resource)))
		 {
			$resource = null;
			
		}
	
		//        $this->_acl->removeAllow($role,$resource);        //�������ĳ��role�Ƴ�Ȩ��
		//�жϵ�ǰ�û�����Ȩ��ִ��ĳ������
		if(!($this -> _acl -> isAllowed($role, $resource, $action))) 
		{
			if (!$this -> _userid) 
			{//δ��½�����
				$controller = 'signpage';
				$action     = 'signin';

			}
			else 
			{                      //û��Ȩ�޵����
				
				echo "<script>
                        $.messager.alert('����','��û�в���Ȩ��', 'warning');
                    </script>";
				exit();
			}
		}

		
		
		$request -> setModuleName($module);
		$request -> setControllerName($controller);
		$request -> setActionName($action);
	}
	



}