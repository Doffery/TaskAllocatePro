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

		$iconFile = 'D:/www/TaskAllocatePro/pics/'.$_SESSION['testerId'].'/head.jpg';
		if(file_exists($iconFile)){
			$this->view->iconaddress = 'http://localhost/TaskAllocatePro/pics/'.$_SESSION['testerId'].'/head.jpg';
		}else{
			$this->view->iconaddress = 'http://localhost/TaskAllocatePro/public/image/head.jpg';
		}
		
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
 		$this->view->name = $result['Account_Name'];
		$this->view->mobile = $result['Account_mobile'];
		$this->view->icon = $result['Account_Icon'];
		$this->view->selfdiscription = $result['Self_Discription'];
	
		$this->view->email = $_SESSION['testerEmail'];
		if($this->getRequest()->getPost()) {
			if($_POST['change'] == '确定') {
				$datachange = array(
						'Account_Sex' => $_POST['sex'],
						'Account_mobile' => $_POST['mobile'],
						'Self_Discription' => $_POST['selfdiscription'].''
				);
				$result = $account->fetchRow('Account_ID = '.$_SESSION['testerId']);
			}else if($_POST['upload'] == '上传头像'){
				$uploadDir = 'http://localhost/TaskAllocatePro/pics/'; //file upload path
				$filename=iconv("utf-8","gbk",$_FILES['uploadfile']['name']);
				$uploadFile = $uploadDir.$filename;
				echo "<pre>";
				if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadFile))
				{
					echo "File is valid, and was successfully uploaded. ";
					echo "Here's some more debugging info:\n";
				}
				else
				{
					echo "ERROR!  Here's some debugging info:\n";
					echo "remember to check valid path, max filesize\n";
					echo "$-POST-upload: ".$_POST['upload'];
				}
				echo "</pre>";
			}
		}
		$iconFile = 'D:/www/TaskAllocatePro/pics/'.$_SESSION['testerId'].'/head.jpg';
		if(file_exists($iconFile)){
 			$this->view->iconaddress = 'http://localhost/TaskAllocatePro/pics/'.$_SESSION['testerId'].'/head.jpg';
		}else{
			$this->view->iconaddress = 'http://localhost/TaskAllocatePro/public/image/head.jpg';
		}
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
    	if(!$testmission) {
    		$recorddb = new Application_Model_DbTable_Record();
    		$rrecord = $recorddb->fetchRow('Mission_ID = '.$missionid);
    		if(!$rrecord['User_Givenscore'] && $rrecord['Tester'] == $_SESSION['testerId'])
    			$this->_redirect('/tester/valuateuser?missionid='.$missionid);
    		else if($rrecord)
    			$this->_redirect('/index/mission?missionid='.$missionid);
    	}
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
			
			$uploadDir = 'D:/TaskAllocatePro/files/'; //file upload path
			$filename=iconv("utf-8","gbk",$_FILES['uploadfile']['name']);
			$folder = $this->_getParam('missionid');
			$uploadFile = $uploadDir.$folder.'/tester/'.$filename;
			if(!file_exists($uploadFile)){
				mkdir($uploadDir.$folder,0777);
				mkdir($uploadDir.$folder.'/tester',0777);
			}else{
			}
			echo "<pre>";
			if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadFile))
			{
				echo "File is valid, and was successfully uploaded. ";
				echo "Here's some more debugging info:\n";
			}
			else
			{
				echo "ERROR!  Here's some debugging info:\n";
				echo "remember to check valid path, max filesize\n";
				echo "$-POST-upload: ".$_POST['upload'];
			}
			echo "</pre>";
			
			$testmission = $testmissiondb->fetchRow('Mission_ID = '. $missionid);
		}

		$missiondb = new Application_Model_DbTable_Mission();
		$mission = $missiondb->fetchRow('Mission_ID = '. $missionid);
		
		$this->view->title = '测试人员任务管理中心';
		$this->view->missionname = $mission['Mission_Name'];
		
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

	public function tradeinfoAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('testerlogon', 'logon');
		
		$missionid = $this->_getParam('missionid');
		$testmissiondb = new Application_Model_DbTable_TestMission();
		$testmission = $testmissiondb->fetchRow('Mission_ID = '. $missionid);
		if($_SESSION['testerId'] != $testmission['Tester_ID'])
			$this->_helper->redirector('accessrefused', 'error');
	
		 
		 
		if($this->getRequest()->getPost())
		{
			$missiondb = new Application_Model_DbTable_Mission();
			$mission = $missiondb->fetchRow('Mission_ID = '.$missionid);
			if($_POST['price'] < $mission['Lowest_Price'])
				$this->view->notice = '您的提交的价格低于您设定的最低价格！';
			else {
				$data = array(
						'Price' => $_POST['price'],
						'Finished_Time' => date('Y-m-d H:i:s')
						);
				$recorddb = new Application_Model_DbTable_Record();
				$re = $recorddb->update($data, 'Mission_ID = '.$missionid);
				if($re) {

					//+1的实现！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
					$testerdb = new Application_Model_DbTable_Tester();
					$tester = $testerdb->fetchRow('Tester_ID = '.$_SESSION['testerId']);
					$where = $testerdb->getAdapter()->quoteInto('Tester_ID = ?', $_SESSION['testerId']);
					$data = array(
							'Finished_Counts' => $tester['Finished_Counts'] + 1
					);
					$result = $testerdb->update($data, $where);
					
					
					$usermissiondb = new Application_Model_DbTable_UserMission();
					$user = $usermissiondb->fetchRow('Mission_ID = '.$missionid);
					
					$resultdelete = $testmissiondb->delete('Mission_ID = '.$missionid);
					//删测试日志
					$usermissiondb->delete('Mission_ID = '.$missionid);
					$missiondb->delete('Mission_ID = '.$missionid);
					
					$missiondialydb = new Application_Model_DbTable_MissionDialy();
					$missiondialydb->delete('Test_Mission_ID = '.$missionid);
					//////////////////////////////////////////////////////////////////////至此任务结束
					
					if($resultdelete) {
	
						$messagetitle = '测试人员已经上传了交易信息了';
						$messagecontent = '<div>
			<p>您可以去评价了！</p></div>';
						$datam = array(
								'Publisher_ID' => $_SESSION['testerId'],
								'Receiver_ID' => $user['User_ID'],
								'Related_Mission_ID' => $missionid,
								'Publish_Time' => date('Y-m-d H:i:s'),
								'Message_Title' => $messagetitle,
								'Message_Content' => $messagecontent
						);
						$messagedb = new Application_Model_DbTable_Message();
						$resultm = $messagedb->insert($datam);
		
						if($resultm)
							$this->_redirect('/tester/valuateuser?missionid='.$missionid);
					}
				}
				else
					$this->_helper->redirector('dberror', 'error');
			}
		}
		$this->view->missionid = $this->_getParam('missionid');
		$this->view->title = '提交交易信息';
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
	}
	
	public function valuateuserAction() {

		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('testerlogon', 'logon');

		$missionid = $this->_getParam('missionid');
		$recorddb = new Application_Model_DbTable_Record();
		$record = $recorddb->fetchRow('Mission_ID = '.$missionid);
		if($record['Tester'] != $_SESSION['testerId'])
			$this->_helper->redirector('accessrefused', 'error');
			
		if(!$record['Price'])
			$this->_redirect('/tester/tradeinfo?missionid='.$missionid);
		//$this->_helper->redirector('tradeinfo');
		if($record['User_Givenscore'])
			$this->_redirect('/index/mission?missionid='.$missionid);
		
// 		$testmissiondb = new Application_Model_DbTable_TestMission();
// 		$testmission = $testmissiondb->fetchRow('Mission_ID = '. $missionid);
// 		if($_SESSION['testerId'] != $testmission['Tester_ID'])
// 			$this->_helper->redirector('accessrefused', 'error');
		
		
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		
		$this->view->title = '评价商家';
    	$this->view->notice = '您已经完善好了交易信息，请填个评价吧！';
    	$this->view->missionid = $missionid;
    	
    	if($this->getRequest()->getPost()) {
    		$data = array(
    				'User_Givenscore' => $_POST['score']/100,
    				'User_Comment' => $_POST['comment']
    				);
    		$re = $recorddb->update($data, 'Mission_ID = '.$missionid);
    		if($re) {

    			
    			$userdb = new Application_Model_DbTable_User();
    			$user = $userdb->fetchRow('User_ID = '.$record['Release_User']);
    			$oscore = $user['User_Averagescore'];
    			$nscore = ($oscore*9 + $_POST['score']/100)/10;
    			$userdb->update(array('User_Averagescore' => $nscore), 'User_ID = '.$record['Release_User']);
    			
    			
    			
    			
    			if($record['Tester_Giventimingscore'] == null)
    				$add = '您还未评论，去评论吧！';
						$messagetitle = '测试人员已经评价好了';
						$messagecontent = '<div>
			<h4>评价内容</h4>
								<p>分数：'.$_POST['score'].'</p>
								<p>内容：</p>
								<p>'.$_POST['comment'].'</p>
								<p>'.$add.'</p>
								</div>';
						$datam = array(
								'Publisher_ID' => $_SESSION['testerId'],
								'Receiver_ID' => $record['Release_User'],
								'Related_Mission_ID' => $missionid,
								'Publish_Time' => date('Y-m-d H:i:s'),
								'Message_Title' => $messagetitle,
								'Message_Content' => $messagecontent
						);
						$messagedb = new Application_Model_DbTable_Message();
						$resultm = $messagedb->insert($datam);
		
						if($resultm)
    							$this->_helper->redirector('valuatesuccess');
    			
    		}else {

    			$this->_helper->redirector('dberror', 'error');
    		}
    	}
		
	}
	
	public function valuatesuccessAction() {

		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['tester'])
			$this->_helper->redirector('testerlogon', 'logon');

		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
	}
}
