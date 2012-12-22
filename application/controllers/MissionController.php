<?php

/**
 * MissionController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

$missionUnsolved = array();

class MissionController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated MissionController::indexAction() default action
	}
	
	public function chooseredirectorAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
    		$this->_helper->redirector('logon', 'index');
    	
    	
		if ($this->getRequest()->isPost()) {
			$test = $this->getRequest()->getPost('test');
			if ($test == '单元测试') {
				$this->_helper->redirector('publishmission1');
				
			} else if ($test == '集成测试') {
				$this->_helper->redirector('publishmission1');
				
			} else if ($test == '系统测试') {
				$this->_helper->redirector('publishmissions');
				
			}
		} else {
			//$this->_helper->redirector('ChooseMi');
		}
	}
	
	public function publishmission1Action() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
    		$this->_helper->redirector('logon', 'index');
    	
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
					'Mission_State' => 1,
					'Mission_Content' => 't',//上传文件的地址
					'Father_Mission' => $_POST['fathermission']
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
			else $this->_helper->redirector('error');
		}
	}
	
	//public static $missionUnsolved = array();
	
	//$mission;
	public function publishmissionsAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
    		$this->_helper->redirector('logon', 'index');

		if ($this->getRequest()->isPost()) {
			//$choosed = $this->getRequest()->getParams();
			if($_POST['separate']) {
				//首先要有一个父任务
				$data = array(
						'Mission_Name' => $_POST['missionname'],
						'Release_Time' => date('Y-m-d H:i:s'),
						'Tec_Field' => 'System',
						'Mission_About' => 'It\'s a father mission!',
						'Mission_State' => 3
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
					//$this->_helper->redirector('success');
					for($i = 1, $j = 0, $ind = 'testtype'. $i; $i <= 13; $i ++, $ind = 'testtype'. $i) {
						if($_POST[$ind]) {
							$missionUnsolved[$j] = $i + 3;
							$j ++;
						}
					}
					$_SESSION['missionUnsolved'] = $missionUnsolved;
					$_SESSION['fathermission'] = $result;
					$this->_helper->redirector('publishmissions1');
				}
				
				
			} else {
				
			}
			
		}
	}
	

	public function publishmissions1Action() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
    		$this->_helper->redirector('logon', 'index');
	}
	
	public function successAction() {
		
	}
}
