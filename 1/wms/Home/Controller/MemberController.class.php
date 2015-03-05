<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends Controller {
    public function index(){
    	$this->display('index');//输出页面模板
    }
    public function memberList(){
    	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$User = M("users");
		$userCount = $User->count();//获取记录的总数
		$offset = ($page-1)*$rows;
		$result["total"] = $userCount;
		$res = $User->limit($offset,$rows)->select();
		$result['rows'] = $res;
		echo json_encode($result);
    }
    public function memberEdit(){
    	$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    	$User = M("users");
    	$users = $User->where("user_id=$user_id")->find();
    	$this->assign('users',$users);
    	$this->display('edit');//输出页面模板
    }
    public function memberSave(){
    	$user_name 	= !empty($_REQUEST['user_name']) ? $_REQUEST['user_name'] : '';
    	$nick_name 	= !empty($_REQUEST['nick_name']) ? trim($_REQUEST['nick_name']) : '';
    	$email 		= !empty($_REQUEST['email']) ? trim($_REQUEST['email']) : '';
    	$mobile 	= !empty($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';
    	$birthday   = !empty($_REQUEST['birthday']) ? $_REQUEST['birthday'] : '';
    	$user_id 	= !empty($_REQUEST['user_id']) ? trim($_REQUEST['user_id']) : '';
    	$province 	= !empty($_REQUEST['province']) ? trim($_REQUEST['province']) : '';//省份
    	$city 		= !empty($_REQUEST['city']) ? trim($_REQUEST['city']) : '';//城市
    	$district 	= !empty($_REQUEST['district']) ? trim($_REQUEST['district']) : '';//地区
    	$region 	= !empty($_REQUEST['region']) ? trim($_REQUEST['region']) : '';//合并在一起的地址
    	$address 	= !empty($_REQUEST['address']) ? trim($_REQUEST['address']) : '';//详细地址
    	$update = array(
    		'user_name' => $user_name,
    		'nick_name' => $nick_name,
    		'email' 	=> $email,
        	'birthday'  => $birthday,
        	'province'  => $province,
        	'city'  	=> $city,
        	'district'  => $district,
        	'region'    => $region,
        	'address'   => $address,
    		'mobile' 	=> $mobile
    	);
    	$User = M("users"); // 实例化User对象
    	$update_id = $User->where("user_id=$user_id")->save($update); // 根据条件保存修改的数据
    	$result['success'] = $update_id;
    	$result['message'] = '数据已经更新.';
   		echo json_encode($result);
    }
    
}