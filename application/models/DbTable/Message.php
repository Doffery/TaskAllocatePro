<?php

/**
 * Message
 *  
 * @author ��
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_Message extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'message';
	protected $_primary = 'Message_ID';
	
	
	public function getMessageReceivedAll($accountId) {
// 		$query = $this->_db->select()->from('Message')->where('Receiver_ID = ?',$accountId);
// 		$result = $this->getAdapter()->fetchAll($query);

		$result = $this->fetchAll('Receiver_ID = '.$accountId)->toArray();
/* 		$accountdb = new Application_Model_DbTable_Account();
		$missiondb = new Application_Model_DbTable_Mission();
		foreach ($result as $r) {
			$r['Publisher'] = $accountdb->fetchRow('Account_ID = '.$r['Publisher_ID']);
			$r['Related_Mission'] = $missiondb->fetchRow('Mission_ID = '.$r['Related_Mission_ID']);
		} */
		rsort($result);
		return $result;
	}
	
	public function getMessageReceivedRecently($accountId) {
		if(date('m') == 1)
			$lastmonth = mktime(date('H'),date('i'),date('s'),12,date('d'),date('Y')-1);
		else $lastmonth = mktime(date('H'),date('i'),date('s'),date('m')-1,date('d'),date('Y'));
		$recenttime = date('Y-m-d-H-i-s', $lastmonth);
		//$query = $this->_db->select()->from('Message')->where('Receiver_ID = ?',$accountId)->where('Publish_Time > ?', $recenttime);
		$result = $this->fetchAll('Receiver_ID = '.$accountId.' And Publish_Time > '. $recenttime)->toArray();
/* 		$accountdb = new Application_Model_DbTable_Account();
		$missiondb = new Application_Model_DbTable_Mission();
		foreach ($result as $r) {
			$r['Publisher'] = $accountdb->fetchRow('Account_ID = '.$r['Publisher_ID']);
			$r['Related_Mission'] = $missiondb->fetchRow('Mission_ID = '.$r['Related_Mission_ID']);
		} */
		rsort($result);
		return $result;
	}
	
	public function getMessagePublishedAll($accountId) {
		//$query = $this->_db->select()->from('Message')->where('Publisher_ID = ?',$accountId);
		$result = $this->fetchAll('Publisher_ID = '.$accountId)->toArray();
/* 		$accountdb = new Application_Model_DbTable_Account();
		$missiondb = new Application_Model_DbTable_Mission();
		foreach ($result as $r) {
			$r['Receiver'] = $accountdb->fetchRow('Account_ID = '.$r['Receiver_ID']);
			$r['Related_Mission'] = $missiondb->fetchRow('Mission_ID = '.$r['Related_Mission_ID']);
		} */
		rsort($result);
		return $result;
		
	}
	
	public function getMessagePublishedRecently($accountId) {

		if(date('m') == 1)
			$lastmonth = mktime(date('H'),date('i'),date('s'),12,date('d'),date('Y')-1);
		else $lastmonth = mktime(date('H'),date('i'),date('s'),date('m')-1,date('d'),date('Y'));
		$recenttime = date('Y-m-d-H-i-s', $lastmonth);
		//$query = $this->_db->select()->from('Message')->where('Publisher_ID = ?',$accountId)->where('Publish_Time > ?', $recenttime);
		//$result = $this->getAdapter()->fetchAll($query);
		$result = $this->fetchAll('Publisher_ID = '.$accountId.' And Publish_Time > '. $recenttime)->toArray();
/* 		$accountdb = new Application_Model_DbTable_Account();
		$missiondb = new Application_Model_DbTable_Mission();
		foreach ($result as $r) {
			$r['Receiver'] = $accountdb->fetchRow('Account_ID = '.$r['Receiver_ID']);
			$r['Related_Mission'] = $missiondb->fetchRow('Mission_ID = '.$r['Related_Mission_ID']);
		} */
		rsort($result);
		return $result;
	}
}
