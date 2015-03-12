<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
    	$this->display('index');//输出页面模板
    }
    
    public function checkLogin(){
    	$user_name  = !empty($_POST['user_name'])?trim($_POST['user_name']):'';
    	$password   = !empty($_POST['password'])?trim($_POST['password']):'';
    	$checkVerify 	= !empty($_POST['verify'])?trim($_POST['verify']):'';
        $Verify = new \Think\Verify();
		 if(!$Verify->check($_POST['verify'],'1')){
		    $result['success'] = 0;
    		$result['message'] = '输入的验证码不对';
    		$result['url']	   ='admin.php?c=login&a=index';
    		exit(json_encode($result)) ;
		 }
		 $User = M("users");
		 $users = $User->where("user_name='{$user_name}' AND password = '{$password}'")->find();
		 session('user_id',$users['user_id']);
		 session('user_name',$users['user_name']);
    	if(!empty($users)){
    		$result['success'] = 1;
    		$result['message'] = '欢迎进入信息管理系统';
    		$result['url']	   ='admin.php?c=index&a=index';
    	}else{
    		$result['success'] = 0;
    		$result['message'] = '输入帐号不对,请重新登录';
    		$result['url']	   ='admin.php?c=login&a=index';
    	}
    	
    	exit(json_encode($result));
    }
    public function verify_c(){
    	$Verify = new \Think\Verify();
    	$Verify->fontSize = 17;
    	$Verify->length   = 4;
    	$Verify->useCurve = false;
    	$Verify->codeSet = '23456789';
    	$Verify->imageW = 130;
    	$Verify->imageH = 34;
    	//$Verify->expire = 600;
    	$Verify->entry(1);
    }
    public function loginOut(){
    	session('user_name',null); // 删除name
    	if(!session("?user_name")){
    		$this->success('退出成功', 'admin.php?c=login&a=index');
    	} else {
    		$this->error('退出失败');
    	}
    }
}