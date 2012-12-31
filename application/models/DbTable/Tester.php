<?php

/**
 * Tester
 *  
 * @author ��
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_Tester extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'tester';
	protected $_primary = 'Tester_ID';
	
/* 	public function checkUnique($testername, $email){
		$query = $this->_db->select()->from('Tester')->where('Tester_Name = ?',$testername);
		$result = $this->getAdapter()->fetchOne($query);
		if($result){
			return true;
		}else{
			$query = $this->_db->select()->from('Tester')->where('Tester_Email = ?',$email);
			$result = $this->getAdapter()->fetchOne($query);
			if($result){
				return true;
			}
			else return false;
		}
	} */
	
	public function getApplyTesterAccount($missionid) {
	
		$tm = new Application_Model_DbTable_ApplyMission();
		$query = $this->_db->select()->from('applymission')->where('Mission_ID = ?',$missionid);
		$usermission = $tm->getAdapter()->fetchAll($query);
		foreach ($usermission as $t) {
			$account = new Application_Model_DbTable_Account();
			$r = $account->fetchRow("Account_ID = ".$t['Tester_ID']);
			if($r)
				$testmissionall[$t['Tester_ID']] = $r;
		}
	
		return $testmissionall;
		//$usermission
	}
}
