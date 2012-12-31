<?php

/**
 * MessageController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
class MessageController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */

    public function init()
    {
     	$response = $this->getResponse();
    	$response->insert('footer', $this->view->render('footer.phtml'));
    	$response->insert('menu', $this->view->render('menu.phtml'));
    	//$response->insert('userstate', $this->view->render('userstate.phtml'));
    	$response->insert('searchbar', $this->view->render('searchbar.phtml'));
    }
    
	public function indexAction() {
		// TODO Auto-generated MessageController::indexAction() default action
	}
	
	public function detailAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'] && !$_SESSION['tester'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
    	
    	//$this->_helper->layout()->enableLayout();
    	
    	
    	$messageid = $this->_getParam('messageid');
    	$messagedb = new Application_Model_DbTable_Message();
    	$message = $messagedb->fetchRow('Message_ID = '. $messageid);
    	$messagedb->update(array('ReadOrnot' => 1), 'Message_ID = '. $messageid);
//     	if($_SESSION['userId'] != $message['Publisher_ID'] && $_SESSION['userId'] != $message['Reciever_ID'] &&
//     			$_SESSION['testerId'] != $message['Publisher_ID'] && $_SESSION['testerId'] != $message['Reciever_ID'])
// 			$this->_helper->redirector('accessrefused', 'error');
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
 		
 		
		$this->view->title = '消息查看';
		
		$this->view->message = $message;
		
		//$this->view->backurl = $_GET['backurl'];
		
		
	}
}
