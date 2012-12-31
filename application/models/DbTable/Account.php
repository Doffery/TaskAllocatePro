<?php

/**
 * Account
 *  
 * @author ��
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_Account extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'account';
	protected $_primary = 'Account_ID';

	public function checkUnique($username, $email){
		$query = $this->_db->select()->from('account')->where('Account_Name = ?',$username);
		$result = $this->getAdapter()->fetchOne($query);
		if($result){
			return true;
		}else{
			$query = $this->_db->select()->from('account')->where('Account_Email = ?',$email);
			$result = $this->getAdapter()->fetchOne($query);
			if($result){
				return true;
			}
			else return false;
		}
	}
}
