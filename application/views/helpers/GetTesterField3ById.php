<?php
/**
 *
 * @author Administrator
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * GetTesterField3ById helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetTesterField3ById {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function getTesterField3ById($id) {
		// TODO Auto-generated
		// Zend_View_Helper_GetTesterField3ById::getTesterField3ById() helper
		$searcher = new Application_Model_DbTable_Tester();
		$db = $searcher->getAdapter();
		$where = $db->quoteInto('Tester_ID = ?',$id);
		$res = $searcher->fetchRow($where)->toArray();
		return $res['Expert_Field3'];
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
