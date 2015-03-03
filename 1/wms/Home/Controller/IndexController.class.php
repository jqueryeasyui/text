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
}