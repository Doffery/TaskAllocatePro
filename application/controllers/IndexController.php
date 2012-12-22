<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->view->baseUrl = $this->_request->getBaseUrl();
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
    }
    
    public function logonAction() {
        if($_SESSION['user'] != null) {
    		$this->_helper->redirector('success');
    		return;
        }
        if ($this->getRequest()->isPost()) {
        		
	    	$username = trim($this->_request->getPost('username'));
	    	$password = trim($this->_request->getPost('password'));
	//     	echo 'username'.$username;
	//     	echo 'password'.$password;
	    	if($username!=null&&$password!=null){
	    		$login = new Application_Model_DbTable_User();
	    		$db = $login->getAdapter();
	    		$where = $db->quoteInto('User_Name=?',$username)
	    		.$db->quoteInto('And Password=?',$password);
	    		$result = $login->fetchRow($where);
	    		if($result){
	    			session_start();		//调用session_start()函数，声明session
	    			$_SESSION['user']=$_POST['username'];				//定义session变量
	    			$_SESSION['userId'] = $result->__get('User_ID');
	    			$this->_helper->redirector('success');
	    		}else{
	    			$where = $db->quoteInto('User_Email=?',$username)
	    			.$db->quoteInto('And Password=?',$password);
	    			$result = $login->fetchRow($where);
		    		if($result){
		    			session_start();		//调用session_start()函数，声明session
		    			$_SESSION['user']=$_POST['username'];				//定义session变量
	    				$_SESSION['userId'] = $result->__get('User_ID');
	    				$this->_helper->redirector('success');
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
    	if(strtolower($_SERVER['REQUEST_METHOD'])=='post'){
    	//$this->_forward('register'); return ;
    		$username = $this->_request->getParam('username');
    		$password = $this->_request->getParam('password');
    		$email = $this->_request->getParam('email');
    		$user = new Application_Model_DbTable_User();
    		if($username!=null&&$password!=null){
    			if($user->checkUnique($username, $email)){
    				//$this->_helper->redirector('register');
    				$this->view->ttt = '账户名或邮箱已经被注册~~';
    			}else{
				    $data = array(
			            'User_Name' => $username,
					    'User_Email' => $email,
					    'Password' => $password,
					    'Submittedmission_counts' => 0,
					    'User_Averagescore' => 0
			        );
    				$result = $user->insert($data);
    				if($result){
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

    public function successAction() {
    	//echo 
    	session_start();//需要在每个页面的开始运行此代码，否则该页面中不识别session
    	if(!$_SESSION['user'])
    		$this->_helper->redirector('logon');
    		
         else $this->view->user = $_SESSION['user'];
    }


}

