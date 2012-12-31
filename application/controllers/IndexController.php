<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->view->baseUrl = $this->_request->getBaseUrl();
    	$response = $this->getResponse();
    	$response->insert('footer', $this->view->render('footer.phtml'));
    	$response->insert('menu', $this->view->render('menu.phtml'));
    	$response->insert('userstate', $this->view->render('userstate.phtml'));
    	$response->insert('searchbar', $this->view->render('searchbar.phtml'));
    	//session_start();
    	$tests_num = array (
    			'unit_test' => 1,
    			'inte_test' => 2,//integeration
    			'syst_test' => 3,
    			'syst_function_test' => 4,
    			'syst_security_test' => 5,
    			'syst_volume_test' => 6,//volume
    			'syst_integrity_test' => 7,//integrity
    			'syst_structural_test' => 8,//structural
    			'syst_ui_test' => 9,
    			'syst_load_test' => 10,
    			'syst_pressure_test' => 11,//pressure
    			'syst_stra_test' => 12,//strain
    			'syst_recovery_test' => 13,
    			'syst_configuration_test' => 14,
    			'syst_compatibility_test' => 15,
    			'syst_installation_test' => 16,
    			'else_test' => 17
    	);
    	
    	$tests_str = array (
    			1 => '单元测试',
    			2 => '集成测试',
    			3 => '系统测试',
    			4 => '功能测试',
    			5 => '安全性测试',
    			6 => '容量测试',
    			7 => '完整性测试',
    			8 => '结构测试',
    			9 => '用户界面测试',
    			10 => '负载测试',
    			11 => '压力测试',
    			12 => '疲劳强度测试',
    			13 => '恢复性测试',
    			14 => '配置测试',
    			15 => '兼容性测试',
    			16 => '安装测试',
    			17 => '其他测试'
    	);
    	session_start();
    	$_SESSION['test_str_num'] = $tests_num;
    	$_SESSION['test_num_str'] = $tests_str;
    }

    public function indexAction()
    {
        // action body
    	//$this->_forward('logon');
//     	if($this->getRequest()->isPost())
//     	$this->_helper->redirector(array('controller' => 'index', 'action' => 'mission',
//     			 'params' => array('tester' => 'test')));
    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));
 		$this->view->title = 'Task Allocate Program';
 		
 		$missions = new Application_Model_DbTable_Mission();
 		$missionscompleted = $missions->getmissioncompleted($_SESSION['userId'], 10);
 		$this->view->missionscompleted = $missionscompleted;
 			
 		$missionstesting = $missions->getmissiontesting($_SESSION['userId'], 10);
 		$this->view->missionstesting = $missionstesting;
 		
 		$missionsnew = $missions->getmissionnew($_SESSION['userId'], 10);
 		$this->view->missionsnew = $missionsnew;
 		
    	$response->append('sidebar', $this->view->render('index/sidebar.phtml'));
    }



    public function findallprojectAction()
    {
    	 
    	$searcher = new Application_Model_DbTable_Mission();
    	$db = $searcher->getAdapter();
    	//$where = $db->quoteInto("");
    	$result = $searcher->fetchAll()->toArray();
    	rsort($result);
    	$this->view->result = $result;
    	 
    	if(count($result)!= 0 )
    	{
    
    		$_SESSION['have_result'] = 'yes';
    	}
    	else
    	{
    		$_SESSION['have_result'] = 'no';
    
    	}
    	 
    }
    
    
    public function findalluserAction()
    {
    
    	$searcher = new Application_Model_DbTable_Account();
    	$db = $searcher->getAdapter();
    	$where = $db->quoteInto("Account_ID in (select User_ID from user)");
    	$result = $searcher->fetchAll($where)->toArray();
    	rsort($result);
    	$this->view->result = $result;
    	if(count($result)!= 0 )
    	{
    		 
    		$_SESSION['have_result'] = 'yes';
    	}
    	else
    	{
    		$_SESSION['have_result'] = 'no';
    		 
    	}
    }
    
    public function findalltesterAction()
    {
    	$searcher = new Application_Model_DbTable_Account();
    	$db = $searcher->getAdapter();
    	$where = $db->quoteInto("Account_ID in (select Tester_ID from tester)");
    	$result = $searcher->fetchAll($where)->toArray();
    	rsort($result);
    	$this->view->result = $result;
    	if(count($result)!= 0 )
    	{
    		 
    		$_SESSION['have_result'] = 'yes';
    	}
    	else
    	{
    		$_SESSION['have_result'] = 'no';
    		 
    	}
    }
    
    public function findallrecordAction()
    {
        $searcher = new Application_Model_DbTable_Record();
   	    $db = $searcher->getAdapter();	
    	$result = $searcher->fetchAll()->toArray();
    	rsort($result);
    	$this->view->result = $result;
    	
    	if(count($result)!= 0 )
    	{
    		$_SESSION['have_result'] = 'yes';
    	}
    	else
    	{
    		 
    		$_SESSION['have_result'] = 'no';
    		
    	}
    
    
    }

    public function searchAction()
    {
    	session_start();
    	if ($this->getRequest()->isPost()) {
    
    		$keyword = trim($_POST['key']);
    		$selected = trim($_POST['selected']);
    		 
    		
    		if($selected == 'ForRecord')                 //如果是找用户
    		{
    			$_SESSION['selected'] = 'ForRecord';
    			$_SESSION['keyword'] = $keyword;
    			$this->_helper->redirector('searchrecord');
    		}
    
    		if($selected == 'ForUser')                 //如果是找用户
    		{
    			$_SESSION['selected'] = 'ForUser';
    			$_SESSION['keyword'] = $keyword;
    			$this->_helper->redirector('searchuser');
    		}
    		 
    		if($selected == 'ForTester')                 //如果是找用户
    		{
    			$_SESSION['selected'] = 'ForTester';
    			$_SESSION['keyword'] = $keyword;
    			$this->_helper->redirector('searchtester');
    			 
    		}
    		 
    		 
    		if($selected == 'ForMission')                 //如果是找用户
    		{
    			$_SESSION['selected'] = 'ForMission';
    			$_SESSION['keyword'] = $keyword;
    			$this->_helper->redirector('searchmission');
    		}
    
    		if($selected == 'ForRecord')
    		{
    		}
    	}
    
    }
    
    
    public function searchrecordAction()
    {
    	session_start();
    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));
    	
    	$keyword = $_SESSION['keyword'];
    	$selected = $_SESSION['selected'];
    	 
    	if($selected == 'ForRecord')
    	{
    	
    		$searcher = new Application_Model_DbTable_Record();
    		$db = $searcher->getAdapter();
    		$where = $db->quoteInto("Mission_Name Like '%$keyword%' or Release_User Like '%$keyword%'");       //根据mission_about来查询
    		$result = $searcher->fetchAll($where)->toArray();
    		rsort($result);
    		$this->view->result = $result;
    	}
    	if(count($result)!= 0 )
    	{
    		$_SESSION['have_result'] = 'yes';
    	}
    	else
    	{
    	
    		$_SESSION['have_result'] = 'no';
    		$messagesorry = '对不起，没有找到与  '.$keyword.'  相关的记录';
    		$this->view->message = $messagesorry;
    	}
    	
    	
    }
     
    public function searchmissionAction()
    {
    	session_start();
    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));
    
    	$keyword = $_SESSION['keyword'];
    	$selected = $_SESSION['selected'];
    	
    	if($selected == 'ForMission')
    	{
    
    		$searcher = new Application_Model_DbTable_Mission();
    		$db = $searcher->getAdapter();
    		$where = $db->quoteInto("Mission_About Like '%$keyword%' or Mission_Name Like '%$keyword%'");       //根据mission_about来查询
    		$result = $searcher->fetchAll($where)->toArray();
    		rsort($result);
    		$this->view->result = $result;
    	}
    	if(count($result)!= 0 )
    	{
    		$_SESSION['have_result'] = 'yes';
    	}
    	else
    	{
    
    		$_SESSION['have_result'] = 'no';
    		$messagesorry = '对不起，没有找到与  '.$keyword.'  相关的内容';
    		$this->view->message = $messagesorry;
    	}
    	 
    }
     
    
    public function searchuserAction()
    {
    	session_start();
    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));
    
    	$keyword = $_SESSION['keyword'];
    	$selected = $_SESSION['selected'];
    	 
    	if($selected == 'ForUser')
    	{
    		$searcher = new Application_Model_DbTable_Account();
    		$db = $searcher->getAdapter();
    		$where = $db->quoteInto("(Account_Name Like '%$keyword%' or Self_Discription Like '%$keyword%') and Account_ID in (select User_ID from user)");
    		$result = $searcher->fetchAll($where)->toArray();
    		rsort($result);
    		$this->view->result = $result;
    
    	}
    	 
    	if(count($result)!= 0 )
    	{
    		$_SESSION['have_result'] ='yes';
    	}
    	else
    	{
    		$_SESSION['have_result'] = 'no';
    		$messagesorry = '对不起，没有找到与  '.$keyword.'  相关的内容';
    		$this->view->message = $messagesorry;
    	}
    	 
    
    	 
    }
    
    public function searchtesterAction()
    {
    	session_start();
    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));
    	$keyword = $_SESSION['keyword'];
    	$selected = $_SESSION['selected'];
    	 
    	if($selected == 'ForTester')
    	{
    		$searcher = new Application_Model_DbTable_Account();
    		$db = $searcher->getAdapter();
    		$where = $db->quoteInto("Account_Name Like '%$keyword%' and Account_ID in (select Tester_ID from tester)");
    		$result = $searcher->fetchAll($where)->toArray();
    		rsort($result);
    		$this->view->result = $result;
    
    	}
    	if(count($result)!= 0 )
    	{
    		 
    		$_SESSION['have_result'] = 'yes';
    
    	}
    	else
    	{
    		$_SESSION['have_result'] = 'no';
    		$messagesorry = '对不起，没有找到与  '.$keyword.'  相关的内容';
    		$this->view->message = $messagesorry;
    	}
    }
    
    public function missionAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));
    	
    	//$this->view->t = $this->_getParam('missionid');
    	
    	$missiondb = new Application_Model_DbTable_Mission();
    	$mission = $missiondb->fetchRow('Mission_ID = '.$this->_getParam('missionid'));

    	$this->view->visible = $mission['Visible'];
    	if($mission['Visible'] == 0) {
    		//提示登录
    		//$_SESSION[]
    		$this->_helper->redirector('accessrefused', 'error');
    	}
    	
    	$testerdb = new Application_Model_DbTable_Tester();
    	$this->view->applytester = $testerdb->getApplyTesterAccount($mission['Mission_ID']);
    	
    	$this->view->missionid = $mission['Mission_ID'];
    	$this->view->title = $mission['Mission_Name'];
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
    }
    
    
    
    public function userdetailAction()
    {
    	$userid = $this->_getParam('fordetail_userid');
    	
    	$searcher = new Application_Model_DbTable_Account();
    	$db = $searcher->getAdapter();
    	$where = $db->quoteInto("Account_ID = ?",$userid);
    	$result = $searcher->fetchRow($where)->toArray();
    	$this->view->useraccount = $result;
    	
    	$searcher1 = new Application_Model_DbTable_User();
    	$db1 = $searcher1->getAdapter();
    	$where1 = $db1->quoteInto("User_ID = ?",$userid);
    	$result1 = $searcher1->fetchRow($where1)->toArray();
    	$this->view->useralone = $result1;
    	
    	
    }
    
    public function testerdetailAction()
    {
    	 $testerid = $this-> _getParam('fordetail_testerid');
    	
    	$searcher = new Application_Model_DbTable_Account();
    	$db = $searcher->getAdapter();
    	$where = $db->quoteInto("Account_ID = ?",$testerid);
    	$result = $searcher->fetchRow($where)->toArray();
    	$this->view->testeraccount = $result;
    	
    	
    	$searcher1 = new Application_Model_DbTable_Tester();
    	$db1 = $searcher1->getAdapter();
    	$where1 = $db1->quoteInto("Tester_ID = ?",$testerid);
    	$result1 = $searcher1->fetchRow($where1)->toArray();
    	$this->view->testeralone = $result1;
    	 
    }
    
    
    public function missiondetailAction()
    {
    	 $missionid = $this->_getParam('fordetail_missionid');
    	 
    	 $searcher = new Application_Model_DbTable_Mission();
    	 $db = $searcher->getAdapter();
    	 $where = $db->quoteInto("Mission_ID = ?",$missionid);       //根据mission_about来查询
    	 $result = $searcher->fetchRow($where)->toArray();
    	 $this->view->missionalone= $result;
    	 
    	 $searcher1 = new Application_Model_DbTable_UserMission();
    	 $db1 = $searcher1->getAdapter();
    	 $where1 = $db1->quoteInto("Mission_ID = ?",$missionid);       //根据mission_about来查询
    	 $result1 = $searcher1->fetchRow($where1)->toArray();
    	 $this->view->missionuser= $result1;
    	 
    	 
    	 $searcher2 = new Application_Model_DbTable_Account();
    	 $db2 = $searcher2->getAdapter();
    	 $where2 = $db2->quoteInto("Account_ID = ?",$result1['User_ID']);
    	 $result2 = $searcher2->fetchRow($where2)->toArray();
    	 $this->view->missionuser_account = $result2;
    	 
    }
}

