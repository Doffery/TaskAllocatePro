<?php
/**
 *
 * @author ��
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * GetAccountNameById helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetAccountNameById {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */

	public function getAccountNameById($id) {
		$accountdb = new Application_Model_DbTable_Account();
		$result = $accountdb->fetchRow('Account_ID = '.$id);
		return $result['Account_Name'];
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
