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
    	
    }
}