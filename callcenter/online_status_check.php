<?php require_once('../Connections/dataconfig.php');?>
<?php
if(!isset($_SESSION)){
    session_start();
    }  //判斷session是否已啟動
?>
<?php 
		
		//載入 上線 有空 的客服
		$query_facebook_online = "SELECT * FROM  welive_user where  status = '1' order by rand()";
		$facebook_online = mysql_query($query_facebook_online, $dataconfig) or die(mysql_error());
		$row_facebook_online = mysql_fetch_assoc($facebook_online);
		$totalRows_facebook_online = mysql_num_rows($facebook_online);
		if($totalRows_facebook_online>0){
  		echo'OK';
		}else{}
?>