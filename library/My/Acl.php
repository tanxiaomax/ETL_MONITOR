<?php
class Acl extends Zend_Acl
{

	public function __construct()
	{
		$resourcesql = " SELECT ID,RESOURCENAME FROM EMDB.RESOURCES;";
		$rolesql = "SELECT ID,ROLENAME,PERMISSION FROM EMDB.ROLES;";
		

		
		
		$role = $this -> GetRole();
		
		
		
		//add connection
		
		if(!Zend_Registry::isRegistered('config'))
		{
			$config = new Zend_Config_Ini(APPLICATION_PATH ."/configs/application.ini", null, true);
			Zend_Registry::set('config', $config);
		}
		
		$config = Zend_Registry::get('config');
		
	
		$dbparams = array('host' => $config->production->resources->db->params->host,
				'username' => $config->production->resources->db->params->username,
				'password' => $config->production->resources->db->params->password,
				'dbname' => $config->production->resources->db->params->dbname
		);
		
		$dbAdapter = Zend_Db::factory($config->production->resources->db->adapter,$dbparams);
		
		//add user roles
		$this->addRole((String)$role);
		

		//add resources
		$resources = $dbAdapter->fetchAll($resourcesql);
		
		
		foreach ($resources as $resource)
		{
			$this->add(new Zend_Acl_Resource((String)$resource['RESOURCENAME']));
		}
		
		$roles = $dbAdapter->fetchAll($rolesql);
		
		

		
		//add allow logic
		$roles = $dbAdapter->fetchAll($rolesql);
		$permissions = self::getUserPermision($role, $roles,$resources);
		
		
		
		
		
		//add deny logic

			
	}
	



	private function GetRoleFromGroup($groupname)
	{
	
		//$rolesql = "select count(ID) from EMDB.ROLES;";
		$groupsql = "select  ID , GROUPNAME ,ROLEMEMBER from EMDB.GROUPS WHERE ID = ?";
	
		if(!Zend_Registry::isRegistered('config'))
		{
			$config = new Zend_Config_Ini(APPLICATION_PATH ."/configs/application.ini", null, true);
			Zend_Registry::set('config', $config);
		}
		
		$config = Zend_Registry::get('config');
		
		// set db connection
		$dbparams = array('host' => $config->production->resources->db->params->host,
		'username' => $config->production->resources->db->params->username,
		'password' => $config->production->resources->db->params->password,
		'dbname' => $config->production->resources->db->params->dbname
		   );

		$dbAdapter = Zend_Db::factory($config->production->resources->db->adapter,$dbparams);
	
		$grouproletemp = $dbAdapter->fetchRow($groupsql, Zend_Auth::getInstance()->getStorage()->read()->GROUP);
		
			

		
		$dbAdapter->closeConnection();
		$grouprole = (int)($grouproletemp['ROLEMEMBER']);
		
		
	
		if (!isset($grouprole))
		{
			//throw exception;
		}
	

	
	
		return $grouprole;
	

	
	}
	
	public function GetRole()
	{
		
		if (Zend_Auth::getInstance()->hasIdentity())
		{
			if (!isset(Zend_Auth::getInstance()->getStorage()->read()->GROUP))
			{
				if (isset(Zend_Auth::getInstance()->getStorage()->read()->ROLE))
				{
					$role = Zend_Auth::getInstance()->getStorage()->read()->ROLE;
				}
				else 
				{
					$role = '1';
				}
				
			}
			else 
			{
				
				$roletemp = self::GetRoleFromGroup(Zend_Auth::getInstance()->getStorage()->read()->GROUP);
				
				if (isset(Zend_Auth::getInstance()->getStorage()->read()->ROLE))
				{
					$role = decbin(bindec(Zend_Auth::getInstance()->getStorage()->read()->ROLE) |
							 bindec($roletemp));
					
					
				}
				else
				{
					$role = $roletemp;
				}
				
				
			}
				
			
			if (!isset(Zend_Auth::getInstance()->getStorage()->read()->ROLE) && 
					!isset(Zend_Auth::getInstance()->getStorage()->read()->GROUP))
				$role = '1';		
		}
		else
			$role = '1';

		
		return $role;
		
	}
	
	
	public function getUserInfo()
	{
		if (Zend_Auth::getInstance()->hasIdentity())
		{
			return Zend_Auth::getInstance()->getStorage()->read()->USERID;
		}
		else
			return false;
	}
	
	
	
	private function getUserPermision( $role,$roleslist,$resourcelist)
	{
		$role = (string)$role;
	
		
		$binrolearray = array();
		$permission = 0;
		
		if (!is_int($role) || is_array($roleslist)) 
		{
			//throw exception
			;
		}
		
		
		$resourcecount = count($roleslist);
		
		if ($resourcecount === 0)
		{
			//throw exception
			;
		}
		
		
		
		$binallresource = 0;
		
		//init
		for ($i = 0 ; $i < count($roleslist); $i++)
		{
			$binrolearray[$i] = 0;
		}
		
		for ($i = 1; $i <= strlen($role); $i ++)
		{
			
			$binrolearray[$i - 1] = $role[strlen($role) - $i];
	
		}
		
		
		
		for ($i = 0; $i < count($binrolearray); $i++)
		{
			if ($binrolearray[$i] == 1) 
			{
				$permission = decbin(bindec( $roleslist[count($binrolearray) -1 - $i]['PERMISSION']) |
				bindec($permission));
			}
		}
		
		
		
		//calc the num of resource the role has
		$modulecount = strlen($permission) / 3 ;
		
		
		
		if (!is_int($modulecount))
		{
			//throw exception;
		}
		
		for ($i = 0; $i< $modulecount; $i++)
		{

			$this->allow((String)$role, $resourcelist[count($resourcelist) -1 - $i]['RESOURCENAME']);
		}
		
		
		

		
	}

	
}