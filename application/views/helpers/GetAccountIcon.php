<?php
/**
 *
 * @author ��
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * GetAccountIcon helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetAccountIcon {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function getAccountIcon($id) {
    	
 		$iconfile = 'D:/www/TaskAllocatePro/pics/'.$id.'/head.jpg';
 		if(file_exists($iconfile)){
 			return 'http://localhost/TaskAllocatePro/pics/'.$id.'/head.jpg';
 		}else{
 			return 'http://localhost/TaskAllocatePro/public/image/head.jpg';
 		}
		// TODO Auto-generated Zend_View_Helper_GetAccountIcon::getAccountIcon()
		// helper
		return null;
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
