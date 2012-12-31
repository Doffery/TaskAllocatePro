<?php

/**
 * LogonController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
class LogonController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */

    public function init()
    {
    	$response = $this->getResponse();
    	$response->insert('footer', $this->view->render('footer.phtml'));
    	$response->insert('menu', $this->view->render('menu.phtml'));
    	$response->insert('userstate', $this->view->render('userstate.phtml'));
    	$response->insert('searchbar', $this->view->render('searchbar.phtml'));
    }
    
	public function indexAction() {
		// TODO Auto-generated LogonController::indexAction() default action
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
	}
    
    public function logonAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
        if($_SESSION['user'] != null) {
    		$this->_helper->redirector('success');
    		return;
        }
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
 		$this->view->title = '商家登录';
 		
 		if($_GET['backurl'])
 			$this->view->backpage = $_GET['backurl'];
        if ($this->getRequest()->isPost()) {
        		
	    	$username = trim($this->_request->getPost('username'));
	    	$password = trim($this->_request->getPost('password'));
	    	$backurl = trim($this->_request->getPost('back'));
	//     	echo 'username'.$username;
	//     	echo 'password'.$password;
	    	if($username!=null&&$password!=null){
	    		$login = new Application_Model_DbTable_Account();
	    		$db = $login->getAdapter();
	    		$where = $db->quoteInto('Account_Name=?',$username)
	    		.$db->quoteInto('And Account_Password=?',$password);
	    		$result = $login->fetchRow($where);
	    		if($result){
	    			$check = new Application_Model_DbTable_User();
	    			$resultcheck = $check->fetchRow('User_ID = '.$result->__get('Account_ID'));
	    			if($resultcheck) {
		    			session_start();		//调用session_start()函数，声明session
		    			$_SESSION['user']=$result->__get('Account_Name');				//定义session变量
		    			$_SESSION['userId'] = $result->__get('Account_ID');
		    			$_SESSION['userEmail'] = $result->__get('Account_Email');
		    			//$_SESSION['userMissioncount'] = $result->__get('Submittedmission_counts');
		    			//$_SESSION['userscore'] = $result->__get('User_Averagescore');
		    			if($backurl)
		    				$this->_redirect($backurl);
		    			else $this->_helper->redirector('success');
	    			}
	    			else {
		    			$this->view->ttt = '您尚未开通商家账号~~';
	    			}
	    		}else{
	    			$where = $db->quoteInto('Account_Email=?',$username)
	    			.$db->quoteInto('And Account_Password=?',$password);
	    			$result = $login->fetchRow($where);
		    		if($result){
		    			$check = new Application_Model_DbTable_User();
		    			$resultcheck = $check->fetchRow('User_ID = '.$result->__get('Account_ID'));
		    			if($resultcheck) {
			    			session_start();		//调用session_start()函数，声明session
			    			$_SESSION['user']=$result->__get('Account_Name');				//定义session变量
			    			$_SESSION['userId'] = $result->__get('Account_ID');
			    			$_SESSION['userEmail'] = $result->__get('Account_Email');
			    			//$_SESSION['userMissioncount'] = $result->__get('Submittedmission_counts');
			    			//$_SESSION['userscore'] = $result->__get('User_Averagescore');
			    			if($backurl)
			    				$this->_redirect($backurl);
			    			else $this->_helper->redirector('success');
		    			}
		    			else {
			    			$this->view->ttt = '您尚未开通商家账号~~';
		    			}
		    		}else{
		    			$this->view->ttt = "您输入的信息有误";
		    			//$this->_helper->redirector('logon');
		    			//$this->_redirect('blog/login');
	    			}
	    		}
	    	}else{
	    		$this->view->ttt = "登陆失败";
		    	//$this->_helper->redirector('logon');
	    		//$this->_redirect('blog/login');
	    	}
    	}
    
    }

    public function testerlogonAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if($_SESSION['tester'] != null) {
    		$this->_helper->redirector('testersuccess');
    		return;
    	}
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
 		$this->view->title = '测试人员登录';

 		if($_GET['backurl'])
 			$this->view->backpage = $_GET['backurl'];
    	if ($this->getRequest()->isPost()) {
    
    		$testername = trim($this->_request->getPost('testername'));
    		$password = trim($this->_request->getPost('password'));
	    	$backurl = trim($this->_request->getPost('back'));
    		//     	echo 'username'.$username;
    		//     	echo 'password'.$password;
    		if($testername!=null&&$password!=null){
	    		$login = new Application_Model_DbTable_Account();
	    		$db = $login->getAdapter();
	    		$where = $db->quoteInto('Account_Name=?',$testername)
	    		.$db->quoteInto('And Account_Password=?',$password);
	    		$result = $login->fetchRow($where);
    			if($result){
	    			$check = new Application_Model_DbTable_Tester();
	    			$resultcheck = $check->fetchRow('Tester_ID = '.$result->__get('Account_ID'));
	    			if($resultcheck) {
	    				session_start();		//调用session_start()函数，声明session
	    				$_SESSION['tester']=$result->__get('Account_Name');				//定义session变量
	    				$_SESSION['testerId'] = $result->__get('Account_ID');
	    				$_SESSION['testerEmail'] = $result->__get('Account_Email');
	    				//$_SESSION['userMissioncount'] = $result->__get('Submittedmission_counts');
	    				//$_SESSION['userscore'] = $result->__get('User_Averagescore');
	    				if($backurl)
	    					$this->_redirect($backurl);
	    				else $this->_helper->redirector('testersuccess');
	    			}
	    			else {
	    				$this->view->ttt = '您尚未开通测试人员账号~~';
	    			}
	    			
    			}else{
    				$where = $db->quoteInto('Account_Email=?',$testername)
    				.$db->quoteInto('And Account_Password=?',$password);
    				$result = $login->fetchRow($where);
    				if($result){
		    			$check = new Application_Model_DbTable_User();
		    			$resultcheck = $check->fetchRow('Tester_ID = '.$result->__get('Account_ID'));
		    			if($resultcheck) {
		    				session_start();		//调用session_start()函数，声明session
		    				$_SESSION['tester']=$result->__get('Account_Name');				//定义session变量
		    				$_SESSION['testerId'] = $result->__get('Account_ID');
		    				$_SESSION['testerEmail'] = $result->__get('Account_Email');
		    				//$_SESSION['userMissioncount'] = $result->__get('Submittedmission_counts');
		    				//$_SESSION['userscore'] = $result->__get('User_Averagescore');
		    				if($backurl)
		    					$this->_redirect($backurl);
		    				else $this->_helper->redirector('testersuccess');
		    			}
		    			else {
		    				$this->view->ttt = '您尚未开通测试人员账号~~';
		    			}
	    			
    			}else{
    					$this->view->ttt = "您输入的信息有误";
    					//$this->_helper->redirector('logon');
    					//$this->_redirect('blog/login');
    				}
    			}
    		}else{
    			$this->view->ttt = "登陆失败";
    			//$this->_helper->redirector('logon');
    			//$this->_redirect('blog/login');
    		}
    	}
    
    }
    
    public function registerAction() {
    				//$this->view->ttt = '啊大法师地方';
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
    	if(strtolower($_SERVER['REQUEST_METHOD'])=='post'){
    	//$this->_forward('register'); return ;
    		$username = $this->_request->getParam('username');
    		$password = $this->_request->getParam('password');
    		$email = $this->_request->getParam('email');
    		$account = new Application_Model_DbTable_Account();
    		if($username!=null&&$password!=null){
    			if($account->checkUnique($username, $email)){
    				//$this->_helper->redirector('register');
    				$this->view->ttt = '账户名或邮箱已经被注册~~';
    			}else{
				    $data = array(
			            'Account_Name' => $username,
					    'Account_Email' => $email,
					    'Account_Password' => $password
			        );
    				$result = $account->insert($data);
    				$user = new Application_Model_DbTable_User();
    				$datau = array(
    						'User_ID' => $result
    						);
    				$resultu = $user->insert($datau);
    				if($result && $resultu){
// 		    			session_start();		//调用session_start()函数，声明session
// 		    			$_SESSION['user']=$_POST['username'];				//定义session变量
//     					$this->_helper->redirector('success');

    					$this->_helper->redirector('logon');
    				}else{
    					$this->view->ttt = '出错啦，重新注册下咯';
    					//$this->_helper->redirector('register');
    				}
    			}
    		}else{
    			$this->view->ttt = '请填写完成信息';
    			//$this->_helper->redirector('register');
    		}
     	}
    }

    public function testerregisterAction() {
    	//$this->view->ttt = '啊大法师地方';
 		$response = $this->getResponse();
 		$response->append('userstate', $this->view->render('userstate.phtml'));
    	if(strtolower($_SERVER['REQUEST_METHOD'])=='post'){
    		//$this->_forward('register'); return ;
    		$testername = $this->_request->getParam('testername');
    		$password = $this->_request->getParam('password');
    		$email = $this->_request->getParam('email');
    		$testerarea1 = $_POST['area1'];
    		$testerarea2 = $_POST['area2'];
    		$testerarea3 = $_POST['area3'];
    		$account = new Application_Model_DbTable_Account();
    		if($testername!=null&&$password!=null){
    			if($account->checkUnique($testername, $email)){
    				//$this->_helper->redirector('register');
    				$this->view->ttt = '账户名或邮箱已经被注册~~';
    			}
    			else{
				    $data = array(
			            'Account_Name' => $testername,
					    'Account_Email' => $email,
					    'Account_Password' => $password
			        );
				    
    				$result = $account->insert($data);
    				$tester = new Application_Model_DbTable_Tester();
    				
    				$datat = array(
    						'Tester_ID' => $result,
    						'Expert_Field1' => $testerarea1,
    						'Expert_Field2' => $testerarea2,
    						'Expert_Field3' => $testerarea3
    
    				);
    				$resultu = $tester->insert($datat);
    				if($result){
    					// 		    			session_start();		//调用session_start()函数，声明session
    					// 		    			$_SESSION['user']=$_POST['username'];				//定义session变量
    					//     					$this->_helper->redirector('success');
    
    					$this->_helper->redirector('testerlogon');
    				}else{
    					$this->view->ttt = '出错啦，重新注册下咯';
    					//$this->_helper->redirector('register');
    				}
    			}
    		}else{
    			$this->view->ttt = '请填写完成信息';
    			//$this->_helper->redirector('register');
    		}
    	}
    }
    
    public function successAction() {
    	//echo 
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
    		$this->_helper->redirector('logon');
        else $this->view->user = $_SESSION['user'];

        $response = $this->getResponse();
        $response->append('userstate', $this->view->render('userstate.phtml'));
    }

    public function testersuccessAction() {
    	//echo
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['tester'])
    		$this->_helper->redirector('testerlogon');
    	else $this->view->tester = $_SESSION['tester'];

    	$response = $this->getResponse();
    	$response->append('userstate', $this->view->render('userstate.phtml'));
    }
    
    public function logoffAction() {
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	$_SESSION['tester'] = null;
    	$_SESSION['user'] = null;
//     	$response = $this->getResponse();
//     	$response->append('userstate', $this->view->render('userstate.phtml'));
    	$this->_helper->redirector('index', 'index');
    }
}
