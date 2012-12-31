<?php

/**
 * TesterController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
class TesterController extends Zend_Controller_Action {
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
		// TODO Auto-generated TesterController::indexAction() default action
		session_start();
		if(!$_SESSION['tester'])
			$this->_redirect('logon/testerlogon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		
		$this->view->title = '测试人员中心';
		
		$this->view->tester = $_SESSION['tester'];
		
		$testerdb = new Application_Model_DbTable_Tester();
		$result = $testerdb->fetchRow('Tester_ID = '.$_SESSION['testerId']);
		$this->view->is_working = $result['Is_Working'];
		$this->view->quality_score = $result['Quality_Score'];
		$this->view->timing_score = $result['Timing_Score'];
		$this->view->mate_score = $result['Mate_Score'];
		$this->view->missioncount = $result['Finished_Counts'];
		$this->view->expert_field1 = $result['Expert_Field1'];
		$this->view->expert_field2 = $result['Expert_Field2'];
		$this->view->expert_field3 = $result['Expert_Field3'];
		
		$missions = new Application_Model_DbTable_Mission();
			
		$testermission = $missions->getTestermissionrecently($_SESSION['testerId']);
			
		$this->view->testermissions = $testermission;}


	public function testerdataAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_redirect('/logon/testerlogon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
			
		$this->view->title = '测试人员中心';
		$account = new Application_Model_DbTable_Account();
		$result = $account->fetchRow('Account_ID = '.$_SESSION['testerId']);
		$this->view->sex = $result['Account_Sex'] == 1? '男' : '女';
		$this->view->mobile = $result['Account_mobile'];
		$this->view->icon = $result['Account_Icon'];
		$this->view->selfdiscription = $result['Self_Discription'];
	
		$this->view->email = $_SESSION['testerEmail'];
	}
	
	public function testermissionAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_redirect('/logon/testerlogon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
			
		$this->view->title = '测试人员中心';
	
	
		$missions = new Application_Model_DbTable_Mission();
			
		$testermission = $missions->getTestermissionAll($_SESSION['testerId']);
			
		$this->view->testermissions = $testermission;
	}
	
	public function testermessageAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_redirect('/logon/testerlogon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
			
		$this->view->title = '测试人员中心';
	
		$messagedb = new Application_Model_DbTable_Message();
	
		$this->view->tmessagePublish = $messagedb->getMessagePublishedRecently($_SESSION['testerId']);
		$this->view->tmessageReceive = $messagedb->getMessageReceivedRecently($_SESSION['testerId']);
	
	}
	
	public function missionfulfilAction() {

		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('testerlogon', 'logon');
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		
		$missionid = $this->_getParam('missionid');
		$testmissiondb = new Application_Model_DbTable_TestMission();
		$testmission = $testmissiondb->fetchRow('Mission_ID = '. $missionid);
		if($_SESSION['testerId'] != $testmission['Tester_ID'])
			$this->_helper->redirector('accessrefused', 'error');
		
		
		if($this->getRequest()->getPost()) {
			if($_POST['commandtype'] == 'start') {
				$datastart = array(
						'Test_Begin_Time' => date('Y-m-d H:i:s')
						);
				$resultstart = $testmissiondb->update($datastart, 'Mission_ID = '. $missionid);
				if(!$resultstart) {
					$this->_helper->redirector('dberror', 'error');
				}
			}
			
			$testmission = $testmissiondb->fetchRow('Mission_ID = '. $missionid);
		}

		$missiondb = new Application_Model_DbTable_Mission();
		$mission = $missiondb->fetchRow('Mission_ID = '. $missionid);
		
		$this->view->title = $mission['Mission_Name'];
		
		$this->view->missionid = $missionid;
		$this->view->applytime = $testmission['Test_Apply_Time'];
		$this->view->gettime = $testmission['Test_Get_Time'];
		$this->view->begintime = $testmission['Test_Begin_Time'];
		if(!$testmission['Test_Begin_Time'])
			return ;
		
		$this->view->deadline = $mission['Finish_Deadline'];
		$this->view->tecfield = $mission['Tec_Field'];
		$this->view->missionabout = $mission['Mission_About'];
		$this->view->missioncontent = $mission['Mission_Content'];
		$this->view->visible = $mission['Visible'];
		$this->view->fathermission = $mission['Father_Mission'];
		
		if($this->view->visible) {
			$result = $missiondb->fetchAll('Father_Mission = '. $mission['Mission_ID']);
			if($result) {
				$this->view->submission = $result;
			}
		}
		
	}
	
	public function missiondialyAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('testerlogon', 'logon');
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		$missionid = $this->_getParam('missionid');
		$testmissiondb = new Application_Model_DbTable_TestMission();
		$testmission = $testmissiondb->fetchRow('Mission_ID = '. $missionid);
		if($_SESSION['testerId'] != $testmission['Tester_ID'])
			$this->_helper->redirector('accessrefused', 'error');
	}

	public function submissionfulfilAction() {

		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('testerlogon', 'logon');
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		
		$missionid = $this->_getParam('missionid');
		$missiondb = new Application_Model_DbTable_Mission();
		$mission = $missiondb->fetchRow('Mission_ID = '. $missionid);
		$testmissiondb = new Application_Model_DbTable_TestMission();
		if($mission['Father_Mission']) {
			$testmission = $testmissiondb->fetchRow('Mission_ID = '. $mission['Father_Mission']);
			if($_SESSION['testerId'] != $testmission['Tester_ID'])
				$this->_helper->redirector('accessrefused', 'error');
		} else $this->_helper->redirector('accessrefused', 'error');
		
		
		if($this->getRequest()->getPost()) {
			if($_POST['commandtype'] == 'start') {
				$datastart = array(
						'Test_Begin_Time' => date('Y-m-d H:i:s')
						);
				$resultstart = $testmissiondb->update($datastart, 'Mission_ID = '. $missionid);
				if(!$resultstart) {
					$this->_helper->redirector('dberror', 'error');
				}
			}
			
		}

		$this->view->title = $mission['Mission_Name'];
		
		$this->view->applytime = $testmission['Test_Apply_Time'];
		$this->view->gettime = $testmission['Test_Get_Time'];
		$this->view->begintime = $testmission['Test_Begin_Time'];
		if(!$testmission['Test_Begin_Time'])
			return ;
		
		$this->view->deadline = $mission['Finish_Deadline'];
		//$this->view->tecfield = $mission['Tec_Field'];
		$this->view->missionabout = $mission['Mission_About'];
		$this->view->missioncontent = $mission['Mission_Content'];
		$this->view->visible = $mission['Visible'];
		$this->view->fathermission = $mission['Father_Mission'];
		
// 		if($this->view->visible) {
// 			$result = $missiondb->fetchAll('Father_Mission = '. $mission['Mission_ID']);
// 			if($result) {
// 				$this->view->submission = $result;
// 			}
// 		}
		
	}
	
	public function missionsubmitAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('testerlogon', 'logon');
		
		$missionid = $this->_getParam('missionid');
		$testmissiondb = new Application_Model_DbTable_TestMission();
		$testmission = $testmissiondb->fetchRow('Mission_ID = '. $missionid);
		if($_SESSION['testerId'] != $testmission['Tester_ID'])
			$this->_helper->redirector('accessrefused', 'error');

		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));

		$this->view->missionid = $missionid;
		if($this->getRequest()->getPost()) {
			$dataupdate = array(
					'Test_End_Time' => date('Y-m-d H:i:s'),
					'Conclusion_Report' => $_POST['report']
					);
			$result = $testmissiondb->update($dataupdate, 'Mission_ID = '.$_POST['missionid']);
			if(!$result)
				$this->_helper->redirector('dberror', 'error');
			else {
				$messagetitle = '测试完成通告';
				$messagecontent = '<div>
		<h4>测试任务总结报告提交</h4>
		<p>您的该测试任务，测试的测试人员已经确认完成，并提交了测试总结报告，快去看看吧！</p></div>';
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
					$this->_helper->redirector('missionsubmitsuccess');
				else 
					$this->_helper->redirector('dberror', 'error');
			}

			
		
		}
	}

	public function missionsubmitsuccessAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('testerlogon', 'logon');
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = "任务完成报告提交成功！";
		
	}
}
