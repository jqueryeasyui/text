<?php
header ( 'Content-type: text/html; charset=utf-8' );
include './config/db.class.php';
$act = isset($_GET['act']) ? $_GET['act'] : '';

//会员编辑
if($act =='edit_member'){

	echo json_encode($result);
}

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

$offset = ($page-1)*$rows;
$rs = mysql_query("select count(*) from mw_users ");
$row = mysql_fetch_row($rs);
$result["total"] = $row[0];
$sql ="select * from mw_users food where 1 limit $offset,$rows ";
$res = $dbObj->getAll($sql);
$result['rows'] = $res;
echo json_encode($result);