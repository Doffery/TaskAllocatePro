<?php

/**
 * Mission
 *  
 * @author ��
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_Mission extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'mission';
	protected $_primary = 'Mission_ID';
	
	public function getUsermissionr($userId) {//integeration
		$um = new Application_Model_DbTable_UserMission();
		$query = $this->_db->select()->from('usermission')->where('User_ID = ?',$userId);
		$usermission = $um->getAdapter()->fetchAll($query);
		foreach ($usermission as $um) {
			$r = $this->fetchRow("Mission_ID = ".$um['Mission_ID']." and Mission_State < 3");
			if($r)
				$usermissionall[$um['Mission_ID']] = $r;
		}
		rsort($usermissionall);
		return $usermissionall;
		//$usermission
		
	}
	public function getTestermissionr($userId) {//integeration
		$um = new Application_Model_DbTable_TestMission();
		$query = $this->_db->select()->from('testmission')->where('Tester_ID = ?',$userId);
		$usermission = $um->getAdapter()->fetchAll($query);
		foreach ($usermission as $um) {
			$r = $this->fetchRow("Mission_ID = ".$um['Mission_ID']." and Mission_State < 3");
			if($r)
				$usermissionall[$um['Mission_ID']] = $r;
		}
		rsort($usermissionall);
		return $usermissionall;	 	
		//$usermission
	
	}
	
	public function getUsermissionrecently($userId) {
		$um = new Application_Model_DbTable_UserMission();
		$query = $this->_db->select()->from('usermission')->where('User_ID = ?',$userId);
		$usermission = $um->getAdapter()->fetchAll($query);
		if(date('m') == 1)
			$lastmonth = mktime(date('H'),date('i'),date('s'),12,date('d'),date('Y')-1);
		else $lastmonth = mktime(date('H'),date('i'),date('s'),date('m')-1,date('d'),date('Y'));
		$recenttime = date('Y-m-d-H-i-s', $lastmonth);
		foreach ($usermission as $um) {
			$r = $this->fetchRow("Mission_ID = ".$um['Mission_ID']." and Release_Time > ".$recenttime);
			if($r)
				$usermissionall[$um['Mission_ID']] = $r;
		}

		rsort($usermissionall);
		return $usermissionall;
		//$usermission
		
	}
	public function getTestermissionrecently($userId) {
		$um = new Application_Model_DbTable_TestMission();
		$query = $this->_db->select()->from('testmission')->where('Tester_ID = ?',$userId);
		$usermission = $um->getAdapter()->fetchAll($query);
		if(date('m') == 1)
			$lastmonth = mktime(date('H'),date('i'),date('s'),12,date('d'),date('Y')-1);
		else $lastmonth = mktime(date('H'),date('i'),date('s'),date('m')-1,date('d'),date('Y'));
		$recenttime = date('Y-m-d-H-i-s', $lastmonth);
		foreach ($usermission as $um) {
			$r = $this->fetchRow("Mission_ID = ".$um['Mission_ID']." and Release_Time > ".$recenttime);
			if($r)
				$usermissionall[$um['Mission_ID']] = $r;
		}
	
		rsort($usermissionall);
		return $usermissionall;
		//$usermission
	
	}
	
	public function getUsermissionAll($userId) {
		$um = new Application_Model_DbTable_UserMission();
		$query = $this->_db->select()->from('usermission')->where('User_ID = ?',$userId);
		$usermission = $um->getAdapter()->fetchAll($query);
		foreach ($usermission as $um) {
			$r = $this->fetchRow("Mission_ID = ".$um['Mission_ID']);
			if($r)
				$usermissionall[$um['Mission_ID']] = $r;
		}

		rsort($usermissionall);
		return $usermissionall;
		//$usermission
		
	}

	public function getTestermissionAll($userId) {
		$tum = new Application_Model_DbTable_TestMission();
		$query = $this->_db->select()->from('testmission')->where('Tester_ID = ?',$userId);
		$testermission = $tum->getAdapter()->fetchAll($query);
		foreach ($testermission as $tum) {
			$r = $this->fetchRow("Mission_ID = ".$tum['Mission_ID']);
			if($r)
				$testermissionall[$tum['Mission_ID']] = $r;
		}
	
		rsort($testermissionall);
		return $testermissionall;
		//$usermission
	
	}

	public function getmissioncompleted($userId, $num) {
		
	}

	public function getmissiontesting($userId, $num) {
		$result = $this->fetchAll('Bidding = 0 And Visible = 1','Mission_ID DESC',$num)->toArray();
		rsort($result);
		return $result;
	}

	public function getmissionnew($userId, $num) {
		$result = $this->fetchAll('Bidding = 1 And Visible = 1','Mission_ID DESC',$num)->toArray();
		rsort($result);
		return $result;
	}
}
