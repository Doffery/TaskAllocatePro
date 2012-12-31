<?php
/**
 *
 * @author Administrator
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * GetUserSubCountsById helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetUserSubCountsById {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function getUserSubCountsById($id) {
		// TODO Auto-generated
		// Zend_View_Helper_GetUserSubCountsById::getUserSubCountsById() helper
		$searcher = new Application_Model_DbTable_User();
		$db = $searcher->getAdapter();
		$where = $db->quoteInto('User_ID = ?',$id);
		$res = $searcher->fetchRow($where)->toArray();
		return $res['Submittedmission_counts'];
		
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
