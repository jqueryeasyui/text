<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$this->display('main');//输出页面模板
    }
    public function admin(){
    	
    	$this->display('index');//输出页面模板
    }
    public function main(){
    	$User = M("iguan");
		$res = $User->limit(50)->select();
 		$json['total']='4';
		$json['rows']=$res;
		echo json_encode($json);
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