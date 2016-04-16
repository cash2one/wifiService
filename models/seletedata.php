<?php
namespace app\models;

use Yii;
class seletedata 
{

public function paging($sql,$count_sql){
	$db = Yii::$app->db;
	
	

	//如果post过来的内容为空，则使用默认值
	$_name = '';
	$_code = '';
	
	$_verification = '-1';
	$_isPage = 1;	//判断是否点击分页按钮(首页,1，2,..  1:表示点击查询按钮，2表示点击分页按钮)
	
	//分页
	$pageSize = 8 ;
	$page = isset($_POST['page']) ? $_POST['page'] : 1;
	
	$page_s = $page == 1 ? 0 : ($page-1) * $pageSize ;
	
	//$page = isset($_POST['page']) ? $_POST['page'] == 1 ? 0 : ($_POST['page']-1)*10 : 0;
	
	
	if($_POST)
	{
	
	
		/*
		 SELECT * FROM vcos_member WHERE cn_name LIKE %$member_name% OR last_name LIKE %$member_name% OR first_name LIKE %$member%
		 AND member_code LIKE %$member_code%
		 AND sex = $sex
		 AND member_verification = $member_verification
		 */
	
	
	
		$isPage = (isset($_POST['isPage'])) ? $_POST['isPage'] : '1';
	
	
	
	
		$_isPage = $isPage;
	
		//如果post的内容不为默认值，拼接字符串进行模糊查询
	
	
	}
	
	//如果是点击查询按钮，重置页码为第一页
	if($_isPage == '1')
	{
		$page = 1;
		$page_s = 0;
	}
    
	/* $count_sql .= " ORDER BY employee_id ASC "; */
	$count = $db->createCommand($count_sql)->queryAll();
    $count=sizeof($count);
    $maxcount=$count;
	$count = ceil( $count / $pageSize );
	
	$sql .= " LIMIT $page_s , $pageSize ";/* ORDER BY employee_id ASC */

	$wifi_item = $db->createCommand($sql)->queryAll();
    $data=array('wifi_item'=>$wifi_item,'count'=>$count,'maxcount'=>$maxcount,'verification'=>$_verification,'page'=>$page,'isPage'=>$_isPage,
			
	);
	return $data;
}
 /* report分页 */
public function reoprtpaging($sql,$count_sql){
	$db = Yii::$app->db;



	//如果post过来的内容为空，则使用默认值
	$_name = '';
	$_code = '';

	$_verification = '-1';
	$_isPage = 1;	//判断是否点击分页按钮(首页,1，2,..  1:表示点击查询按钮，2表示点击分页按钮)

	//分页
	$pageSize = 4 ;
	$page = isset($_POST['page']) ? $_POST['page'] : 1;

	$page_s = $page == 1 ? 0 : ($page-1) * $pageSize ;

	//$page = isset($_POST['page']) ? $_POST['page'] == 1 ? 0 : ($_POST['page']-1)*10 : 0;


	if($_POST)
	{


		/*
		 SELECT * FROM vcos_member WHERE cn_name LIKE %$member_name% OR last_name LIKE %$member_name% OR first_name LIKE %$member%
		 AND member_code LIKE %$member_code%
		 AND sex = $sex
		 AND member_verification = $member_verification
		 */



		$isPage = (isset($_POST['isPage'])) ? $_POST['isPage'] : '1';




		$_isPage = $isPage;

		//如果post的内容不为默认值，拼接字符串进行模糊查询


	}

	//如果是点击查询按钮，重置页码为第一页
	if($_isPage == '1')
	{
		$page = 1;
		$page_s = 0;
	}

	/* $count_sql .= " ORDER BY employee_id ASC "; */
	$count = $db->createCommand($count_sql)->queryAll();
	$count=sizeof($count);
	$maxcount=$count;
	$count = ceil( $count / $pageSize );

	$sql .= " LIMIT $page_s , $pageSize ";/* ORDER BY employee_id ASC */

	$wifi_item = $db->createCommand($sql)->queryAll();
	$data=array('wifi_item'=>$wifi_item,'count'=>$count,'maxcount'=>$maxcount,'verification'=>$_verification,'page'=>$page,'isPage'=>$_isPage,
				
	);
	return $data;
}
  
}