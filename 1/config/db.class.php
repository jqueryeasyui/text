<?php
header('Content-Type:text/html; charset=utf-8');//设置编码

//定义数据库操作类

//创造数据库操作对象

//通过对象使用数据库操作的属性、方法(每一个函数)

//类 是把函数再一次进行封装
class Mysql{
	
	//定义属性
	private $conn;
	
	/**
	 * 连接服务器=>定义成构造方法(我们实例化数据库操作类时可以自动调用此函数，自动连接服务器、选择数据库)
	 * 
	 * @author 管可章	 
	 * @param string $server 服务器的IP地址名域名	 
	 * @param string $root 用户名
	 * @param string $password 密码
	 * @param string $db 数据库名
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @return resource 连接标识符
	 */
	function __construct($server, $root, $password, $db, $debug = 0){
	
		$this->conn = @mysql_connect($server, $root, $password);
		if(!$this->conn){
		
			if( $debug ){
				exit("与服务器 $server 链接失败了，原因为：".mysql_error());
			}else{
				exit('由于系统繁忙，操作失败了，请联系管理员或稍候再试');
			}			
		}
			
		//连接数据库
		$db_selected = @mysql_select_db($db,$this->conn);
		if(!$db_selected){
			
			if( $debug ){
				exit("选择 $db 数据库失败了，原因为：".mysql_error());
			}else{
				exit('由于系统繁忙，操作失败了，请联系管理员或稍候再试');
			}
			
		}
		
		//设置传输编码  注意：数据库的utf8是没有中线的，而网页上是有的
		$this->query('SET NAMES UTF8',$this->conn,$debug);
		
	}
	
	//定义方法
	
	/**
	 * 查询一条记录
	 *
	 * @author 管可章	 
	 * @param string $sql  查询语句
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @param int/bool $echoSql 是否直接输出SQL语句并中止程序
	 * @return  array $data 返回一条数据（一维数组）
	 */	
	function getOne($sql, $debug = 0, $echoSql = 0) {

		$result = $this->query($sql,$this->conn,$debug,$echoSql);
		if( $result && mysql_num_rows($result)>0 ){
		
			$data = mysql_fetch_assoc($result);	
			return $data;
		}else{
			return array();
		}
	}
	/**
	 * 查询一条记录以数组的形式输出
	 *
	 * @author 管可章	 
	 * @param string $sql  查询语句
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @param int/bool $echoSql 是否直接输出SQL语句并中止程序
	 * @return  array $data 返回一条数据（一维数组）
	 */	
	function getRow($sql,$debug = 0,$echoSql = 0,$limited = false){
        if ($limited == true){
            $sql = trim($sql . ' LIMIT 1');
        }

        $res = $this->query($sql,$this->conn,$debug,$echoSql);;
        if ($res !== false){
            return mysql_fetch_assoc($res);
        }else{
            return false;
        }
    }

	/**
	 * 查询多条记录
	 *
	 * @author 管可章	 
	 * @param string $sql 查询语句
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @param int/bool $echoSql 是否直接输出SQL语句并中止程序
	 * @return array $data 返回多条数据（二维数组）
	 */	
	function getAll($sql, $debug = 0, $echoSql = 0) {

		$result = $this->query($sql,$this->conn,$debug,$echoSql);
		if( $result && mysql_num_rows($result) > 0 ){
			
			//$data = array();
			while( $ass_res = mysql_fetch_assoc($result) ){
				$data[] = $ass_res;
			}
			
			//return $data;xcu8
			return (array)$data;//强制转换为数组
		}else{
			return array();
		}
	}
	
	//添加数据
	/**
	 * 向数据表添加记录，返回刚插入的记录的ID
	 *
	 * @author 管可章	 
	 * @param string $table  数据表名
	 * @param array $arr 要插入的数据（以关联数组的形式，数组的下标是数据表的每一个字段）
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @param int/bool $echoSql 是否直接输出SQL语句并中止程序
	 * @return int 刚插入的记录的ID
	 */	
	function insert($table, $arr, $debug = 0, $echoSql = 0) {

		$k = '`'.implode( '`,`',array_keys($arr) ).'`';
		
		$v = "'".implode( "','",$arr )."'";

		$sql = "INSERT INTO $table ({$k}) VALUES ({$v})";

		$this->query($sql,$this->conn,$debug,$echoSql);
		return mysql_insert_id($this->conn);

	}
				
	//更新数据
	/**
	 * 更新数据表的记录，返回受影响的记录行数
	 *
	 * @author 管可章	 
	 * @param string $table  数据表名
	 * @param array $arr 要更新的数据（以关联数组的形式，数组的下标是数据表的每一个字段）
	 * @param string $where  更新条件
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @param int/bool $echoSql 是否直接输出SQL语句并中止程序
	 * @return int 受影响的记录行数
	 */	
	function update($table, $arr, $where=0, $debug = 0, $echoSql = 0) {

		$str = '';
		foreach($arr as $key=>$value){
			$str .= "`{$key}`='$value',";  //$str = $str . $key; 
		}

		$str = rtrim($str,',');

		$sql = "UPDATE $table SET $str WHERE $where";
		$this->query($sql,$this->conn,$debug,$echoSql);   
		return mysql_affected_rows($this->conn);

	}	
	
	/**
	 * 删除数据表的记录，返回受影响的记录行数
	 *
	 * @author 管可章
	 * @param string $table  数据表名
	 * @param string $where  删除条件
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @param int/bool $echoSql 是否直接输出SQL语句并中止程序
	 * @return int 受影响的记录行数
	 */	
	function delete($table, $where, $debug = 0, $echoSql = 0){
		
		$sql = "DELETE FROM $table WHERE $where";
		$this->query($sql,$this->conn,$debug,$echoSql);
		return mysql_affected_rows($this->conn);

	}
	
	/**
	 * 查询总记录数
	 *
	 * @author 管可章
	 * @param string $sql 查询语句（注意 语句当中的 cout(*) as c ）
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @param int/bool $echoSql 是否直接输出SQL语句并中止程序
	 * @return int 返回总记录数
	 */
	 function getCount($sql, $debug = 0, $echoSql = 0)
	{
		//查询总记录数
		$rs = $this->query($sql,$this->conn,$debug,$echoSql);
		$data = mysql_fetch_assoc($rs);
		return $data['c'];
	}
	
	/**
	 * 执行SQL语句
	 *
	 * @author 管可章
	 * @param string $sql 要执行的SQL语句
	 * @param int/bool $debug 是否开启高度模试(开启错误提示，默认是 0,不开启)
	 * @param int/bool $echoSql 是否直接输出SQL语句并中止程序
	 * @return resource 资源集（或者叫结果集
	 */
	private function query($sql, $conn, $debug = 0, $echoSql = 0){
	
		if($echoSql){exit($sql);}
		
		//执行SQL语句
		$rs = mysql_query($sql, $conn);
		
		if( !$rs )	//执行失败
		{
			if($debug)	//开发阶段，出错时要报详情
			{
				echo '<pre>';
				print_r(
					array(
						'sql' => "sql语句为：$sql",
						'error' => '错误信息为:'. mysql_error(),
						'actpage' => "发生错误的页面为：$_SERVER[PHP_SELF]"
					)
				);
				echo '</pre>';
				exit();
			}else	//运营阶段，出错时要报 忽悠信息
			{
				exit('由于系统繁忙，操作失败了，请联系管理员或稍候再试');
			}
		}	
		return $rs;
	}
	
	//析构函数
	function __destruct(){
		//echo '关闭数据库资源';
		mysql_close($this->conn);
	}
}

//使用。。。
//创造数据库操作对象
$dbObj = new Mysql(SAE_MYSQL_HOST_M,SAE_MYSQL_USER,SAE_MYSQL_PASS,SAE_MYSQL_DB);

//操作管理员表
 
/* $admin_info = $mysql->getOne('SELECT * FROM `jl_admin`',1,1);
print_r($admin_info);exit;

$admin_info = $mysql->getAll('SELECT * FROM `jl_admin`',1,1);
print_r($admin_info);exit;

$count = $mysql->getCount('SELECT count(*) as c FROM `jl_admin`',1,1);
echo $count;

$insert=array();
$insert['username']='小管';
$res = $mysql->insert('jl_admin',$insert,1);
echo $res;
exit;

$update=array();
$update['username']='小管';
$update_id = $mysql->update('jl_admin',$update,'id=1',1);
echo $update_id;
exit;

$del_res = $mysql->delete('jl_admin','id=2',1);
echo $del_res;
exit;
 */






