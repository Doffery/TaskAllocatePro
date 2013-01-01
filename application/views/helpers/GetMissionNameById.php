<?php
/**
 *
 * @author ��
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * GetMissionNameById helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetMissionNameById {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	
	public function getMissionNameById($id) {
		$missiondb = new Application_Model_DbTable_Mission();
		$result = $missiondb->fetchRow('Mission_ID = '.$id);
		if($result)
			return $result['Mission_Name'];
		else {
			$recorddb = new Application_Model_DbTable_Record();
			$result = $recorddb->fetchRow('Mission_ID = '.$id);
			return $result['Mission_Name'];
		}
	}
	
	/**
	 * Sets the view field
	 * 
	 * @param $view Zend_View_Interface        	
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
