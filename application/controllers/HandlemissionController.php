<?php

/**
 * HandlemissionController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
class HandlemissionController extends Zend_Controller_Action {
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
		// TODO Auto-generated HandlemissionController::indexAction() default
	// action
	}
	
	public function applymissionAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('accessrefused', 'error');
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '申请测试任务';
		
		$this->view->missionid = $this->_getParam('missionid');

		$applymissiondb = new Application_Model_DbTable_ApplyMission();
		if($applymissiondb->fetchRow('Mission_ID = '.$this->_getParam('missionid').' And Tester_ID = '.$_SESSION['testerId']))
			$this->_helper->redirector('applysendsuccess');
		if ($this->getRequest()->isPost()) {
			$data = array(
					'Mission_ID' => $_POST['missionid'],
					'Tester_ID' => $_SESSION['testerId'],
					'Apply_Mission_Time' => date('Y-m-d H:i:s')
					);
			$result = $applymissiondb->insert($data);
			if($result) {
				$messagetitle = '您收到一份任务申请';
				$messagecontent = '<div>
		<h4>解决方案：</h4>
		<p>'.$_POST['missioncontent'].'</p></div>';
				$usermissiondb = new Application_Model_DbTable_UserMission();
				$user = $usermissiondb->fetchRow('Mission_ID = '.$_POST['missionid']);
				$datam = array(
						'Publisher_ID' => $_SESSION['testerId'],
						'Receiver_ID' => $user['User_ID'],
						'Related_Mission_ID' => $_POST['missionid'],
						'Publish_Time' => date('Y-m-d H:i:s'),
						'Message_Title' => $messagetitle,
						'Message_Content' => $messagecontent
						);
				$messagedb = new Application_Model_DbTable_Message();
				$resultm = $messagedb->insert($datam);
				
				if($resultm)
					$this->_helper->redirector('applysendsuccess');
				else 
					$this->_helper->redirector('dberror', 'error');
			}
		}
	}
	
	public function applysendsuccessAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('accessrefused', 'error');
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		
		$this->view->title = '申请发送成功';
		
		
	}
	
	public function delivermissionAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['user'])
			$this->_helper->redirector('accessrefused', 'error');
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));

		$this->view->title = '确认交付任务';
		
		$this->view->missionid = $this->_getParam('missionid');
		$this->view->testerid = $this->_getParam('testerid');
		$this->view->applytime = $this->_getParam('applytime');

		$testmissiondb = new Application_Model_DbTable_TestMission();
		if($testmissiondb->fetchRow('Mission_ID = '. $this->_getParam('missionid'))) {
			$this->_helper->redirector('delivermissionsuccess');
		}
		
		if($this->getRequest()->getPost()) {
			$testmissiondb = new Application_Model_DbTable_TestMission();
			$datatm = array(
					'Mission_ID' => $this->_getParam('missionid'),
					'Tester_ID' => $this->_getParam('testerid'),
					'Test_Apply_Time' => $this->_getParam('applytime'),
					'Test_Get_Time' => date('Y-m-d H:i:s')
					);
			$resulttm = $testmissiondb->insert($datatm);
			$missiondb = new Application_Model_DbTable_Mission();
			$resultmi = $missiondb->update(array('Bidding' => 0), 'Mission_ID = '.$this->_getParam('missionid'));
			
			$submission = $missiondb->fetchAll('Father_Mission = '.$this->_getParam('missionid'));
			if($submission) {
				$mission = $missiondb->fetchRow('Mission_ID = '.$this->_getParam('missionid'));
				if($mission['Mission_State'] != $_SESSION['test_str_num']['inte_test'])
					foreach($submission as $sm) {
						$datatm['Mission_ID'] = $sm['Mission_ID'];
		
						$resulttm = $testmissiondb->insert($datatm);
						$resultmi = $missiondb->update(array('Bidding' => 0), 'Mission_ID = '.$sm['Mission_ID']);
					}
			}
			
				
			if($resulttm && $resultmi) {
				
				$messagedb = new Application_Model_DbTable_Message();
				$messagetitle = '您的任务申请通过了';
				$messagecontent = '<div>
		<h4>恭喜恭喜，您的任务申请通过了！</h4>
		<p>既然通过了，那就赶快去开始任务吧~~</p></div>';
				$datam = array(
						'Publisher_ID' => $_SESSION['userId'],
						'Receiver_ID' => $this->_getParam('testerid'),
						'Related_Mission_ID' => $this->_getParam('missionid'),
						'Publish_Time' => date('Y-m-d H:i:s'),
						'Message_Title' => $messagetitle,
						'Message_Content' => $messagecontent
				);
				$resultm = $messagedb->insert($datam);
				
				$applymissiondb = new Application_Model_DbTable_ApplyMission();
				$resultall = $applymissiondb->fetchAll('Mission_ID = '.$this->_getParam('missionid')." And Tester_ID <> ".$this->_getParam('testerid'));
				foreach ($resultall as $r) {
					$messagetitle = '您的任务申请没有通过';
					$messagecontent = '<div>
			<h4>任务申请没有通过</h4>
			<p>没有通过，不灰心，是商家眼力神不好，再去别处逛逛~~</p></div>';
					$datam = array(
							'Publisher_ID' => $_SESSION['userId'],
							'Receiver_ID' => $r['Tester_ID'],
							'Related_Mission_ID' => $this->_getParam('missionid'),
							'Publish_Time' => date('Y-m-d H:i:s'),
							'Message_Title' => $messagetitle,
							'Message_Content' => $messagecontent
					);
					$resultm = $messagedb->insert($datam);
					if(!$resultm)
						$this->_helper->redirector('dberror', 'error');
				}
				
				$resultam = $applymissiondb->delete('Mission_ID = '.$this->_getParam('missionid'));
				if($resultam) {
					$this->_helper->redirector('delivermissionsuccess');
				}
				else $this->_helper->redirector('dberror', 'error');
			}
			else $this->_helper->redirector('dberror', 'error');
		}
		
	}
	
	public function delivermissionsuccessAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['user'])
			$this->_helper->redirector('accessrefused', 'error');
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));

		$this->view->title = '交付成功';
		
	}
}
