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
		//请求信息
		$module     = $request -> module;                //模块
		$controller = $request -> controller;            //请求的控制器
		$action     = $request -> action;                //请求的action
	
		$resource = strtoupper($controller);    //资源：一个限制访问的对象
		$action   = strtoupper($action);
		$role     = strtoupper($this->_acl->GetRole());                //角色：一个可以发出请求去访问Resource的对象

		
		
		
		//判断是否拥有资源
		if(!($this -> _acl -> has($resource)))
		 {
			$resource = null;
			
		}
	
		//        $this->_acl->removeAllow($role,$resource);        //可以针对某个role移除权限
		//判断当前用户是有权限执行某个请求
		if(!($this -> _acl -> isAllowed($role, $resource, $action))) 
		{
			if (!$this -> _userid) 
			{//未登陆的情况
				$controller = 'signpage';
				$action     = 'signin';

			}
			else 
			{                      //没有权限的情况
				
				echo "<script>
                        $.messager.alert('提醒','您没有操作权限', 'warning');
                    </script>";
				exit();
			}
		}

		
		
		$request -> setModuleName($module);
		$request -> setControllerName($controller);
		$request -> setActionName($action);
	}
	



}