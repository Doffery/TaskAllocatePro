<?php

//require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'user';
	protected $_primary = 'User_ID';
	

	public function checkUnique($username, $email){
		$query = $this->_db->select()->from('User')->where('User_Name = ?',$username);
		$result = $this->getAdapter()->fetchOne($query);
		if($result){
			return true;
		}else{
			$query = $this->_db->select()->from('User')->where('User_Email = ?',$email);
			$result = $this->getAdapter()->fetchOne($query);
			if($result){
				return true;
			}
			else return false;
		}
	}
}
