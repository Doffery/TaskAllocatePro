<?php
/**
 *
 * @author ��
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * getMessageNew helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_getMessageNew {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function getMessageNew($receiverid) {
		// TODO Auto-generated Zend_View_Helper_getMessageNew::getMessageNew()
		// helper
		$messagedb = new Application_Model_DbTable_Message();
		$messages = $messagedb->fetchAll('Receiver_ID = '.$receiverid.' And ReadOrnot = 0');
		return $messages->count();
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
