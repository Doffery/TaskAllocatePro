<?php

/**
 * MissionController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

//$missionUnsolved = array();

class PublishmissionController extends Zend_Controller_Action {
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
		// TODO Auto-generated MissionController::indexAction() default action
	}
	
	public function chooseredirectorAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
    		//$this->_helper->redirector('logon', 'logon','', $this->_getAllParams());
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
    	
    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '选择任务类型';
    	
    	
		if ($this->getRequest()->isPost()) {
			$test = $this->getRequest()->getPost('test');
			if ($test == '确定') {

				for($i = 1, $j = 0, $ind = 'testtype'. $i; $i <= 13; $i ++, $ind = 'testtype'. $i) {
					if($_POST[$ind]) {
						$missionUnsolved[$j] = $i + 3;
						$j ++;
					}
				}
				$_SESSION['missionUnsolved'] = $missionUnsolved;
				
				$this->_helper->redirector('publishmissions');
				
			} else if ($test == '单元测试') {
				$this->_helper->redirector('publishmission1');
				
			} else if ($test == '集成测试') {
				$this->_helper->redirector('publishmissionr');
				
			} else if ($test == '其他测试') {
				$this->_helper->redirector('publishmissione');
				
			}
		} else {
			//$this->_helper->redirector('ChooseMi');
		}
	}
	
	public function publishmission1Action() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '单元测试发布';
    	
		if ($this->getRequest()->isPost()) {
			$data = array(
					'Mission_Name' => $_POST['missionname'],
					'Release_Time' => date('Y-m-d H:i:s'),
					'Apply_Deadline' => $_POST['applicationdate'],
					'Finish_Deadline' => $_POST['finishdate'],
					'Tec_Field' => $_POST['tecfield'],
					'Mission_About' => $_POST['missioncontent'],
					'Lowest_Price' => $_POST['lowprice'],
					'Highest_Price' => $_POST['highprice'],
					'Mission_State' => $_POST['missiontype'],
					'Mission_Content' => 't',/////////////////////////////////上传文件的地址
					'Father_Mission' => $_POST['fathermission'],
					'Visible' => $_POST['missionvisible']
					);
			$mission = new Application_Model_DbTable_Mission();
			$result = $mission->insert($data);

			$dataum = array (
					'User_ID' => $_SESSION['userId'],
					'Mission_ID' => $result
					);
			$usermission = new Application_Model_DbTable_UserMission();
			$resultum = $usermission->insert($dataum);
			
			if($result && $resultum) {
				if($_POST['missiontype'] == "1")
					$this->_helper->redirector('success');
			}
			else $this->_helper->redirector('error', 'error');
		}
	}

	public function publishmissionsAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
		if(!$_SESSION['missionUnsolved'])
			$this->_helper->redirector('chooseredirector');
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '系统测试发布';
		if ($this->getRequest()->isPost()) {
			//首先要有一个父任务
			$data = array(
					'Mission_Name' => $_POST['missionname'],
					'Release_Time' => date('Y-m-d H:i:s'),
					'Tec_Field' => '系统测试',
					'Mission_About' => 'It\'s a father mission!',
					'Mission_State' => 3,
					'Visible' => '0'
			);
			$mission = new Application_Model_DbTable_Mission();
			$fathermission = $mission->insert($data);
	
			$dataum = array (
					'User_ID' => $_SESSION['userId'],
					'Mission_ID' => $fathermission
			);
			$usermission = new Application_Model_DbTable_UserMission();
			$resultum = $usermission->insert($dataum);
				
			if($fathermission && $resultum) {
				$mission = new Application_Model_DbTable_Mission();
				$usermission = new Application_Model_DbTable_UserMission();
				foreach ($_SESSION['missionUnsolved'] as $m) {
					$missionseperate = $_POST['separate']? 1 : 0;
					$datas = array(
							'Mission_Name' => $_POST['missionname'. $m],
							'Release_Time' => date('Y-m-d H:i:s'),
							'Apply_Deadline' => $_POST['applicationdate'. $m],
							'Finish_Deadline' => $_POST['finishdate'. $m],
							'Tec_Field' => $_SESSION['test_num_str'] [$m],
							'Mission_About' => $_POST['missioncontent'. $m],
							'Lowest_Price' => $_POST['lowprice'. $m],
							'Highest_Price' => $_POST['highprice'. $m],
							'Mission_State' => $_POST['missiontype'. $m],
							'Mission_Content' => 't',/////////////////////////////////上传文件的地址
							'Father_Mission' => $fathermission,
							'Visible' => 1
					);
					$result = $mission->insert($datas);
			
					$dataum = array (
							'User_ID' => $_SESSION['userId'],
							'Mission_ID' => $result
					);
					$resultums = $usermission->insert($dataum);
			
					if($result && $resultums) {
					}
					else $this->_helper->redirector('error', 'error');
				}
				$_SESSION['missionUnsolved'] = null;
				$this->_helper->redirector('success');
			
			}
			else $this->_helper->redirector('error', 'error');
				
		}
	}

	public function publishmissionsiAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));

		if(!$_SESSION['missionUnsolved'])
			$this->_helper->redirector('chooseredirector');
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '系统测试发布';
		if ($this->getRequest()->isPost()) {
			//首先要有一个父任务
			$data = array(
					'Mission_Name' => $_POST['missionname'],
					'Release_Time' => date('Y-m-d H:i:s'),
					'Tec_Field' => '系统测试',
					'Mission_About' => 'It\'s a father mission!',
					'Mission_State' => 3,
					'Lowest_Price' => $_POST['lowprice'],
					'Highest_Price' => $_POST['highprice'],
					'Apply_Deadline' => $_POST['applicationdate'],
					'Finish_Deadline' => $_POST['finishdate'],
					'Visible' => '1'
			);
			$mission = new Application_Model_DbTable_Mission();
			$fathermission = $mission->insert($data);
	
			$dataum = array (
					'User_ID' => $_SESSION['userId'],
					'Mission_ID' => $fathermission
			);
			$usermission = new Application_Model_DbTable_UserMission();
			$resultum = $usermission->insert($dataum);
				
			if($fathermission && $resultum) {
				$mission = new Application_Model_DbTable_Mission();
				$usermission = new Application_Model_DbTable_UserMission();
				foreach ($_SESSION['missionUnsolved'] as $m) {
					$missionseperate = $_POST['separate']? 1 : 0;
					$datas = array(
							'Mission_Name' => $_POST['missionname'. $m],
							'Release_Time' => date('Y-m-d H:i:s'),
							'Apply_Deadline' => $_POST['applicationdate'. $m],
							'Finish_Deadline' => $_POST['finishdate'. $m],
							'Tec_Field' => $_SESSION['test_num_str'] [$m],
							'Mission_About' => $_POST['missioncontent'. $m],
							'Lowest_Price' => $_POST['lowprice'. $m],
							'Highest_Price' => $_POST['highprice'. $m],
							'Mission_State' => $_POST['missiontype'. $m],
							'Mission_Content' => 't',/////////////////////////////////上传文件的地址
							'Father_Mission' => $fathermission,
							'Visible' => 0
					);
					$result = $mission->insert($datas);
			
					$dataum = array (
							'User_ID' => $_SESSION['userId'],
							'Mission_ID' => $result
					);
					$resultums = $usermission->insert($dataum);
			
					if($result && $resultums) {
					}
					else $this->_helper->redirector('error', 'error');
				}
				$_SESSION['missionUnsolved'] = null;
				$this->_helper->redirector('success');
			
			}
			else $this->_helper->redirector('error', 'error');
				
		}
	}
	
	public function publishmissionrAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));

		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '集成测试发布';

		if ($this->getRequest()->isPost()) {
			$data = array(
					'Mission_Name' => $_POST['missionname'],
					'Release_Time' => date('Y-m-d H:i:s'),
					'Apply_Deadline' => $_POST['applicationdate'],
					'Finish_Deadline' => $_POST['finishdate'],
					'Tec_Field' => $_POST['tecfield'],
					'Mission_About' => $_POST['missioncontent'],
					'Lowest_Price' => $_POST['lowprice'],
					'Highest_Price' => $_POST['highprice'],
					'Mission_State' => $_POST['missiontype'],
					'Mission_Content' => 't',/////////////////////////////////上传文件的地址
					'Father_Mission' => $_POST['fathermission'],
					'Visible' => $_POST['integration']? 0 : 1
			);
			$mission = new Application_Model_DbTable_Mission();
			$result = $mission->insert($data);

			$_SESSION['fathermission2'] = $result;
			$dataum = array (
					'User_ID' => $_SESSION['userId'],
					'Mission_ID' => $result
			);
			$usermission = new Application_Model_DbTable_UserMission();
			$resultum = $usermission->insert($dataum);
				
			if($result && $resultum) {
				if($_POST['integration']) {
					$this->_helper->redirector('publishmissionrdetail');
				}
				else $this->_helper->redirector('success');
			}
			else $this->_helper->redirector('error', 'error');
		}
		
	}
	
	public function publishmissionrdetailAction() {
		session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
		if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));

		if(!$_SESSION['fathermission2'])
			$this->_helper->redirector('publishmissionr');
		$response = $this->getResponse();
		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '添加集成单元';

		$missions = new Application_Model_DbTable_Mission();
			
		$usermission = $missions->getUsermissionr($_SESSION['userId']);
			
		$this->view->usermissions = $usermission;
		
		if ($this->getRequest()->isPost()) {
			foreach ($usermission as $um) {
				if($_POST['missionadd'.$um['Mission_ID']]) {
					$where = $missions->getAdapter()->quoteInto('Mission_ID = ?', $um['Mission_ID']);
					$fm2 = $_SESSION['fathermission2'];
					$data = array(
							'Father_Mission' => $fm2
							);
					$result = $missions->update($data, $where);
					if($result) {
						//echo '<script>alert "'.$um['Mission_ID'].'";</script>';
					}
					else $this->_helper->redirector('error', 'error');
				}
			}
			$_SESSION['fathermission2'] = null;
			$this->_helper->redirector('success');
		}
		
	}
	
	public function publishmissioneAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '其他测试发布';
    	
		if ($this->getRequest()->isPost()) {
			$data = array(
					'Mission_Name' => $_POST['missionname'],
					'Release_Time' => date('Y-m-d H:i:s'),
					'Apply_Deadline' => $_POST['applicationdate'],
					'Finish_Deadline' => $_POST['finishdate'],
					'Tec_Field' => $_POST['tecfield'],
					'Mission_About' => $_POST['missioncontent'],
					'Lowest_Price' => $_POST['lowprice'],
					'Highest_Price' => $_POST['highprice'],
					'Mission_State' => $_POST['missiontype'],
					'Mission_Content' => 't',/////////////////////////////////上传文件的地址
					'Father_Mission' => $_POST['fathermission'],
					'Visible' => $_POST['missionvisible']
					);
			$mission = new Application_Model_DbTable_Mission();
			$result = $mission->insert($data);

			$dataum = array (
					'User_ID' => $_SESSION['userId'],
					'Mission_ID' => $result
					);
			$usermission = new Application_Model_DbTable_UserMission();
			$resultum = $usermission->insert($dataum);
			
			if($result && $resultum) {
				$this->_helper->redirector('success');
			}
			else $this->_helper->redirector('error', 'error');
		}
	}
	
	public function successAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
			$this->_redirect('/logon/logon?backurl='.'/'.$this->_getParam('controller').'/'.$this->_getParam('action'));
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
		$this->view->title = '任务发布成功';
		
//+1的实现！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
 		$userdb = new Application_Model_DbTable_User();
 		$user = $userdb->fetchRow('User_ID = '.$_SESSION['userId']);
		$where = $userdb->getAdapter()->quoteInto('User_ID = ?', $_SESSION['userId']);
		$data = array(
				'Submittedmission_counts' => $user['Submittedmission_counts'] + 1
				);
		$result = $userdb->update($data, $where);
		if($result) {
			//echo '<script>alert "'.$um['Mission_ID'].'";</script>';
		}
		else $this->_helper->redirector('error', 'error');
	}
}
