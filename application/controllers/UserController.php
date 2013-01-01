<?php

/**
 * UserController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
class UserController extends Zend_Controller_Action {
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
		// TODO Auto-generated UserController::indexAction() default action
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
 		
		$this->view->title = '用户中心';

// 		$userdb = new Application_Model_DbTable_User();
// 		$user = $userdb->fetchRow('User_ID = '.$_SESSION['userId']);
		$this->view->user = $_SESSION['user'];
		
		$userdb = new Application_Model_DbTable_User();
		$result = $userdb->fetchRow('User_ID = '.$_SESSION['userId']);
		$this->view->score = $result['User_Averagescore'];
		$this->view->missioncount = $result['Submittedmission_counts'];

		$iconfile = 'D:/www/TaskAllocatePro/pics/'.$_SESSION['userId'].'/head.jpg';
		if(file_exists($iconfile)){
			$this->view->iconaddress = 'http://localhost/TaskAllocatePro/pics/'.$_SESSION['userId'].'/head.jpg';
		}else{
			$this->view->iconaddress = 'http://localhost/TaskAllocatePro/public/image/head.jpg';
		}


		$missions = new Application_Model_DbTable_Mission();
			
		$usermission = $missions->getUsermissionrecently($_SESSION['userId']);
			
		$this->view->usermissions = $usermission;
	}
	
	public function userdataAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));

 		$account = new Application_Model_DbTable_Account();
		if($this->getRequest()->getPost()) {
 			if($_POST['change'] == '确定') {
 				$datachange = array(
 						'Account_Sex' => $_POST['sex'],
 						'Account_mobile' => $_POST['mobile'],
 						'Self_Discription' => $_POST['selfdiscription'].''
 						);
 				$account->update($datachange, 'Account_ID = '.$_SESSION['userId']);
 			}
 			else if($_POST['upload'] == '上传头像'){
 				$uploadDir = 'D:/www/TaskAllocatePro/pics/'.$_SESSION['userId'].'/'; //file upload path
 				$filename=iconv("utf-8","gbk",$_FILES['uploadfile']['name']);
 				$uploadFile = $uploadDir.'head.jpg';
 				if(!file_exists($uploadFile)){
 					mkdir($uploadDir,0777);
 				}else{
 				}
 				echo "<pre>";
 				if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadFile))
 				{
 					echo "File is valid, and was successfully uploaded. ";
 					echo "Here's some more debugging info:\n";
 					echo 'D:/www/TaskAllocatePro/pics/'.$_SESSION['userId'].'/head.jpg';
 				}
 				else
 				{
 					echo "ERROR!  Here's some debugging info:\n";
 					echo "remember to check valid path, max filesize\n";
 					echo "$-POST-upload: ".$_POST['upload'].'D:/www/localhost/TaskAllocatePro/pics/'.$_SESSION['userId'].'/head.jpg';
 				}
 				echo "</pre>";
 				
 			}
 			
 		}
 		$iconfile = 'D:/www/TaskAllocatePro/pics/'.$_SESSION['userId'].'/head.jpg';
 		if(file_exists($iconfile)){
 			$this->view->iconaddress = 'http://localhost/TaskAllocatePro/pics/'.$_SESSION['userId'].'/head.jpg';
 		}else{
 			$this->view->iconaddress = 'http://localhost/TaskAllocatePro/public/image/head.jpg';
 		}
			$this->view->title = '用户中心';
 			$result = $account->fetchRow('Account_ID = '.$_SESSION['userId']);
 			$this->view->name = $result['Account_Name'];
 			$this->view->sex = $result['Account_Sex'];
 			$this->view->mobile = $result['Account_mobile'];
 			$this->view->icon = $result['Account_Icon'];
 			$this->view->selfdiscription = $result['Self_Discription'];
 				
 			$this->view->email = $_SESSION['userEmail'];
	}
	
	public function usermissionAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
 		
		$this->view->title = '用户中心';


		$missions = new Application_Model_DbTable_Mission();
			
		$usermission = $missions->getUsermissionAll($_SESSION['userId']);
			
		$this->view->usermissions = $usermission;
	}
	
	public function usermessageAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
 		
		$this->view->title = '用户中心';
		
		$messagedb = new Application_Model_DbTable_Message();
		
		$this->view->messagePublish = $messagedb->getMessagePublishedRecently($_SESSION['userId']);
// 		$t = $messagedb->getMessageReceivedRecently($_SESSION['userId']);
// 		rsort($t);
		$this->view->messageReceive = $messagedb->getMessageReceivedRecently($_SESSION['userId']);
		
	}
	
	public function missionmanageAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action').'?missionid='.$this->_getParam('missionid'));
 		
    	$missionid = $this->_getParam('missionid');
    	$usermissiondb = new Application_Model_DbTable_UserMission();
    	$usermission = $usermissiondb->fetchRow('Mission_ID = '.$missionid);
    	if(!$usermission) {
    		$recorddb = new Application_Model_DbTable_Record();
    		$rrecord = $recorddb->fetchRow('Mission_ID = '.$missionid);
    		if(!$rrecord['Tester_Giventimingscore'] && $rrecord['Release_User'] == $_SESSION['userId'])
    			$this->_redirect('/user/valuatetester?missionid='.$missionid);
    		else if($rrecord)
    			$this->_redirect('/index/mission?missionid='.$missionid);
    	}
		if($_SESSION['userId'] != $usermission['User_ID'])
			$this->_helper->redirector('accessrefused', 'error');
		if($this->getRequest()->getPost()) {
			$uploadDir = 'http://localhost/TaskAllocatePro/files/'; //file upload path
			$filename=iconv("utf-8","gbk",$_FILES['uploadfile']['name']);
			$folder = $this->_getParam('missionid');
			$uploadFile = $uploadDir.$folder.'/user/'.$filename;
			if(!file_exists($uploadFile)){
				mkdir($uploadDir.$folder,0777);
				mkdir($uploadDir.$folder.'/user',0777);
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
		}
    	
    	$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
		

 		$missiondb = new Application_Model_DbTable_Mission();
 		$mission = $missiondb->fetchRow('Mission_ID = '.$this->_getParam('missionid'));

 		$this->view->missionid = $mission['Mission_ID'];
		$this->view->title = '商家任务管理中心';
		$this->view->missionname = $mission['Mission_Name'];
 		$this->view->lowprice = $mission['Lowest_Price'];
 		$this->view->highprice = $mission['Highest_Price'];
 		$this->view->missiontype = $_SESSION['test_num_str'][$mission['Mission_State']];
 		$this->view->missioncontent = $mission['Mission_Content'];
 		$this->view->missionabout = $mission['Mission_About'];
 		$this->view->tecfield = $mission['Tec_Field'];
 		$this->view->applydeadline = $mission['Apply_Deadline'];
 		$this->view->finishdeadline = $mission['Finish_Deadline'];
 		$this->view->releasetime = $mission['Release_Time'];
 		$this->view->bidding = $mission['Bidding'];
 		$this->view->fathermission = $mission['Father_Mission'];
 		$this->view->premission = $mission['Pre_Mission'];
 		
 		if($mission['Bidding']) {
 			$applymissiondb = new Application_Model_DbTable_ApplyMission();
 			$applymission = $applymissiondb->fetchAll('Mission_ID = '.$missionid);
 			$this->view->applymission = $applymission;
 		}
 		else {
 			$testmissiondb = new Application_Model_DbTable_TestMission();
 			$testmission = $testmissiondb->fetchRow('Mission_ID = '.$missionid);
 			$this->view->testmission = $testmission;
 		}
 		
 		
	}
	
	
	public function missiondialyAction() {

		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		
		
		if($this->getRequest()->getPost()) {
		    $uploadDir = 'D:\\www\\TaskAllocatePro\\files\\'; //file upload path
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
	
	public function missionfinalreportAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action').'?missionid='.$this->_getParam('missionid'));
 		
    	$missionid = $this->_getParam('missionid');
    	$usermissiondb = new Application_Model_DbTable_UserMission();
    	$usermission = $usermissiondb->fetchRow('Mission_ID = '.$missionid);
		if($_SESSION['userId'] != $usermission['User_ID'])
			$this->_helper->redirector('accessrefused', 'error');
		
		if($this->getRequest()->getPost()) {
			if($_POST['submit'] == '通过') {
				
				$missiondb = new Application_Model_DbTable_Mission();
				$mission = $missiondb->fetchRow('Mission_ID = '.$missionid);
				
				$testmissiondb = new Application_Model_DbTable_TestMission();
				$tester = $testmissiondb->fetchRow('Mission_ID = '.$missionid);
				
				$datarecord = array(
						'Mission_ID' => $missionid,
						'Mission_Name' => $mission['Mission_Name'],
						'Release_User' => $_SESSION['userId'],
						'Release_Time' => $mission['Release_Time'],
						'Tofinish_Time' => $mission['Finish_Deadline'],
						'Tec_Field' => $mission['Tec_Field'],
						'Mission_About' => $mission['Mission_About'],
						'Mission_Content' => $mission['Mission_Content'],
						'Father_Mission' => $mission['Father_Mission'],
						'Pre_Mission' => $mission['Pre_Mission'],
						'Tester' => $tester['Tester_ID'],
						'Agreed_Time' => date('Y-m-d H:i:s'),
						'Applied_Time' => $tester['Test_Apply_Time'],
						'Start_Time' => $tester['Test_Get_Time'],
						'End_Time' => $tester['Test_End_Time'],
						'Mission_State' => $mission['Mission_State']
						);
				$recorddb = new Application_Model_DbTable_Record();
				$resultrecord = $recorddb->insert($datarecord);
 
				if($resultrecord) {
					//$testmissiondb->delete('Mission_ID = '.$missionid);
					
					
					$messagetitle = '您的测试任务完成报告通过了';
					$messagecontent = '<div>
			<p>请等待卖家联系付款！付过了吗？付过了那就填付款信息吧。</p></div>';
					
					$datam = array(
							'Publisher_ID' => $_SESSION['userId'],
							'Receiver_ID' => $tester['Tester_ID'],
							'Related_Mission_ID' => $missionid,
							'Publish_Time' => date('Y-m-d H:i:s'),
							'Message_Title' => $messagetitle,
							'Message_Content' => $messagecontent
					);
					$messagedb = new Application_Model_DbTable_Message();
					$resultm = $messagedb->insert($datam);
					
					if($resultm)
						$this->_helper->redirector('passsuccess');
					else
						$this->_helper->redirector('dberror', 'error');
				}
			}else {
				
				
				$messagetitle = '您的测试任务完成报告没有通过';
				$messagecontent = '<div>
		<h4>商家解释：</h4>
		<p>'.$_POST['failreason'].'</p></div>';
				$testmissiondb = new Application_Model_DbTable_TestMission();
				$tester = $testmissiondb->fetchRow('Mission_ID = '.$missionid);
				$datam = array(
						'Publisher_ID' => $_SESSION['userId'],
						'Receiver_ID' => $tester['Tester_ID'],
						'Related_Mission_ID' => $missionid,
						'Publish_Time' => date('Y-m-d H:i:s'),
						'Message_Title' => $messagetitle,
						'Message_Content' => $messagecontent
				);
				$messagedb = new Application_Model_DbTable_Message();
				$resultm = $messagedb->insert($datam);
				
				if($resultm) 
					$this->view->notice = '您的不通过消息已经发送';
				else
					$this->_helper->redirector('dberror', 'error');
			}
		}
		
		
    	$this->view->title = '验收测试任务';
    	$this->view->missionid = $missionid;
    	
    	$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
 		
 		$testmissiondb = new Application_Model_DbTable_TestMission();
 		$testmission = $testmissiondb->fetchRow('Mission_ID = '.$missionid);
 		
 		$this->view->submittime = $testmission['Test_End_Time'];
 		$this->view->reportcontent = $testmission['Conclusion_Report'];
	}
	
	public function passsuccessAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action').'?missionid='.$this->_getParam('missionid'));

    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));

    	$this->view->title = '验收测试任务成功完成';
    	
	}
	
	
	public function valuatetesterAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action').'?missionid='.$this->_getParam('missionid'));

    	$missionid = $this->_getParam('missionid');
    	$usermissiondb = new Application_Model_DbTable_UserMission();
    	$usermission = $usermissiondb->fetchRow('Mission_ID = '.$missionid);
    	if($_SESSION['userId'] != $usermission['User_ID']) {
    		$recorddb = new Application_Model_DbTable_Record();
    		$record = $recorddb->fetchRow('Mission_ID = '.$missionid);
    		if($record['Release_User'] != $_SESSION['userId'])
	    		$this->_helper->redirector('accessrefused', 'error');
    	}
    	
    	
    	
    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));

    	$this->view->title = '评价测试人员';
    	$this->view->missionid = $missionid;
    	
    	if($this->getRequest()->getPost()) {
    		$data = array(
    				'Tester_Giventimingscore' => $_POST['timingscore']/100,
    				'Tester_Givenqualityscore' => $_POST['qualityscore']/100,
    				'Tester_Givenmatescore' => $_POST['matescore']/100,
    				'Tester_Comment' => $_POST['comment']
    				);
    		$re = $recorddb->update($data, 'Mission_ID = '.$missionid);
    		if($re) {
    			

    			$testerdb = new Application_Model_DbTable_Tester();
    			$tester = $testerdb->fetchRow('Tester_ID = '.$record['Tester']);
    			$oscoreq = $tester['Quality_Score'];
    			$oscoret = $tester['Timing_Score'];
    			$oscorem = $tester['Mate_Score'];
    			$nscoreq = ($oscoreq*9 + $_POST['qualityscore']/100)/10;
    			$nscoret = ($oscoret*9 + $_POST['timingscore']/100)/10;
    			$nscorem = ($oscorem*9 + $_POST['matescore']/100)/10;
    			$testerdb->update(array('Quality_Score' => $nscoreq,
    					'Timing_Score' => $nscoreq,
    					'Mate_Score' => $nscorem
    					), 'Tester_ID = '.$record['Tester']);
    			
    			
    			
    			
						if($record['User_Givenscore'] == null)
							$add = '您还未评论，去评论吧！';
						$messagetitle = '商家已经评价好了';
						$messagecontent = '<div>
			<h4>评价内容</h4>
								<p>完成速度：'.$_POST['timingscore'].'</p>
								<p>完成质量：'.$_POST['qualityscore'].'</p>
								<p>配合态度：'.$_POST['matescore'].'</p>
								<p>内容：</p>
								<p>'.$_POST['comment'].'</p>
								<p>'.$add.'</p>
								</div>';
						$datam = array(
								'Publisher_ID' => $record['Release_User'],
								'Receiver_ID' => $_SESSION['testerId'],
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
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action').'?missionid='.$this->_getParam('missionid'));
		

		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		
	}
}
