<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	$this->view->baseUrl = $this->_request->getBaseUrl();
    }

    public function indexAction()
    {
        // action body
    	//$this->_forward('logon');
    }
    
    public function logonAction() {
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
    			$this->_helper->redirector('success');
    		}else{
    			$where = $db->quoteInto('User_Email=?',$username)
    			.$db->quoteInto('And Password=?',$password);
    			$result = $login->fetchRow($where);
	    		if($result){
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
    					$this->_helper->redirector('success');
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
    }

//     public function checkregisterAction() {
//     	//$this->view->title = "register";
//     	//if(strtolower($_SERVER['REQUEST_METHOD'])=='post'){
//     	//$this->_forward('register'); return ;
//     		$username = $this->_request->getParam('username');
//     		$password = $this->_request->getParam('password');
//     		$email = $this->_request->getParam('email');
//     		$user = new Application_Model_DbTable_User();
//     		if($username!=null&&$password!=null){
//     			if($user->checkUnique($username, $email)){
//     				$this->view->ttt = '账户名或邮箱已经被注册~~';
//     				$this->_helper->redirector('register');
//     			}else{ 
// 				    $data = array(
// 			            'User_Name' => $username,
// 					    'User_Email' => $email,
// 					    'Password' => $password,
// 					    'Submittedmission_counts' => 0,
// 					    'User_Averagescore' => 0
// 			        );
//     				$result = $user->insert($data);
//     				if($result){
//     					echo '恭喜您，注册成功';
//     					$this->_helper->redirector('success');
//     				}else{
//     					echo '出错啦，重新注册下咯';
//     					$this->_helper->redirector('register');
//     				}
//     			}
//     		}else{
//     			echo '请填写完成信息';
//     			$this->_helper->redirector('register');
//     		}
// //     	}else{
// //     		echo  $this->view->render('register');
// //     	}
//     }
    
    
//     public function checkloginAction() {

//     	$username = trim($this->_request->getPost('username'));
//     	$password = trim($this->_request->getPost('password'));
//     	echo 'username'.$username;
//     	echo 'password'.$password;
//     	if($username!=null&&$password!=null){
//     		$login = new Application_Model_DbTable_User();
//     		$db = $login->getAdapter();
//     		$where = $db->quoteInto('User_Name=?',$username)
//     		.$db->quoteInto('And Password=?',$password);
//     		$result = $login->fetchRow($where);
//     		if($result){
//     			$this->_helper->redirector('success');
//     		}else{
//     			$where = $db->quoteInto('User_Email=?',$username)
//     			.$db->quoteInto('And Password=?',$password);
//     			$result = $login->fetchRow($where);
// 	    		if($result){
//     				$this->_helper->redirector('success');
// 	    		}else{
// 	    			echo "您输入的信息有误";
// 	    			$this->_helper->redirector('logon');
// 	    			//$this->_redirect('blog/login');
//     			}
//     		}
//     	}else{
//     		echo "登陆失败";
// 	    	$this->_helper->redirector('logon');
//     		//$this->_redirect('blog/login');
//     	}
//     }


}

