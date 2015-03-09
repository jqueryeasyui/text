<?php
namespace Home\Controller;
use Think\Controller;
class NewController extends Controller {
    public function index(){
    	$this->display('index');//输出页面模板
    }
    public function newList(){
    	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    	$food = M("food");
    	$userCount = $food->count();//获取记录的总数
    	$offset = ($page-1)*$rows;
    	$result["total"] = $foodCount;
    	$res = $food->limit($offset,$rows)->select();
    	$result['rows'] = $res;
    	echo json_encode($result);
    }
    public function newEdit(){
    	$food_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    	$food = M("food");
    	$res = $food->where("id=$food_id")->find();
    	$this->assign('food',$res);
    	$this->display('newEdit');
    }
    public function newSave(){
    	$title 		= !empty($_REQUEST['title']) ? $_REQUEST['title'] : '';
    	$editcontent= !empty($_REQUEST['editcontent']) ? trim($_REQUEST['editcontent']) : '';
    	$email 		= !empty($_REQUEST['email']) ? trim($_REQUEST['email']) : '';
    	$mobile 	= !empty($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';
    	$birthday   = !empty($_REQUEST['birthday']) ? $_REQUEST['birthday'] : '';
    	$id 		= !empty($_REQUEST['editID']) ? trim($_REQUEST['editID']) : '';
    	$province 	= !empty($_REQUEST['province']) ? trim($_REQUEST['province']) : '';//省份
    	$city 		= !empty($_REQUEST['city']) ? trim($_REQUEST['city']) : '';//城市
    	$district 	= !empty($_REQUEST['district']) ? trim($_REQUEST['district']) : '';//地区
    	$region 	= !empty($_REQUEST['region']) ? trim($_REQUEST['region']) : '';//合并在一起的地址
    	$address 	= !empty($_REQUEST['address']) ? trim($_REQUEST['address']) : '';//详细地址
    	$photo 		= !empty($_REQUEST['photo']) ? trim($_REQUEST['photo']) : '';//图片
    
    	$upload = new \Think\Upload();// 实例化上传类
    	$upload->maxSize   =     3145728 ;// 设置附件上传大小
    	$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    	$upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
    	$upload->subName  = array('date','Ymd');
    	$upload->savePath  =  	 ''; // 设置附件上传（子）目录
    	// 上传文件
    	$info = $upload->upload();
    	$picUrl = $upload->rootPath.$info['photo']['savepath'].$info['photo']['savename'];
    	$update = array(
    			'title' => $title,
    			'editcontent' => $editcontent,
    			'pic'    => $picUrl
    	);
    	$food = M("food"); // 实例化User对象
    	$update_id = $food->where("id=$id")->save($update); // 根据条件保存修改的数据
    	$result['success'] = $update_id;
    	$result['message'] = '数据已经更新.';
    	echo json_encode($result);
    }
}
