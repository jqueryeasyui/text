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
    public function region(){
    	$region_id = !empty($_GET['region_id'])?trim($_GET['region_id']):1;
    	$type_id = !empty($_GET['type_id'])?trim($_GET['type_id']):1;
    	header('Content-type: text/html; charset=utf8');
    	$region = M("region");
    	$result = $region->where("region_type = '$type_id' AND parent_id = '$region_id'")->select();
    	echo json_encode($result);
    	exit;
    }
    
}