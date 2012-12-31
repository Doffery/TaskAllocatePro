<?php
/**
 *
 * @author Administrator
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * GetUserScoreById helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetUserScoreById {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function getUserScoreById($id) {
		// TODO Auto-generated
		// Zend_View_Helper_GetUserScoreById::getUserScoreById() helper
		$searcher = new Application_Model_DbTable_User();
		$db = $searcher->getAdapter();
		$where = $db->quoteInto('User_ID = ?',$id);
		$res = $searcher->fetchRow($where)->toArray();
		return $res['User_Averagescore'];
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
