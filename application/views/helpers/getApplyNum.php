<?php
/**
 *
 * @author ��
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * getApplyNum helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_getApplyNum {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function getApplyNum($missionid) {
		// TODO Auto-generated Zend_View_Helper_getApplyNum::getApplyNum()
		// helper
		$applymissiondb = new Application_Model_DbTable_ApplyMission();
		$applymission = $applymissiondb->fetchAll('Mission_ID = '.$missionid);
		
		return $applymission->count();
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
