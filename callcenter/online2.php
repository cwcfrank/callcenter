<?php

// +---------------------------------------------+
// |     Copyright  2010 - 2028 WeLive           |
// |     http://www.weentech.com                 |
// |     This file may not be redistributed.     |
// +---------------------------------------------+

include('includes/welive.Core.php');

header_nocache();

if($_CFG['cActived']){
	$online_cache_file = BASEPATH . "cache/online_cache.php";

	@include($online_cache_file);

	if(!isset($welive_onlines) OR !is_array($welive_onlines)){
		$welive_onlines = storeCache();
		if(!$welive_onlines) die('Save cache failed!');
	}

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
	body {font-family:Verdana,Arial; margin:0; padding:0; font-size:12px; color:#292929;}
	div {margin:0 auto; padding:0;}
	ul,ol,li,img {margin:0; padding:0; border:none; list-style-type:none;}
	a {color:#292929;text-decoration:none;cursor:pointer;}
	a:hover {color:#292929;text-decoration:none;}
	.usergroup {padding:0;height:22px;line-height:22px;background:#E1E1E1;color:#1E8FBB;text-indent:23px;overflow:hidden;}
	.description {float:left;padding:3px 7px 0 7px;width:121px;overflow:hidden;line-height:18px;color:#9D5946;}
	.user {margin:0 0 0 7px;padding:2px 0 6px 0;width:128px;white-space:nowrap;overflow:hidden;}
	.user li {line-height:200%;}
	.grey {color:#ACA9A8;}
	.green {color:#51B400;}
	.red {color:#FF6600;}
	</style>
	<script type="text/javascript">
	function setCookie(name,value) {
		document.cookie = name+"="+value+"; path=/";
	}
	</script>
	</head>
	<body id="cellphone">';

    foreach ($welive_onlines AS $usergroup) {
		if(IS_CHINESE){
			$groupname   = $usergroup['groupname'];
			$description   = $usergroup['description'];
		}else{
			$groupname   = $usergroup['groupename'];
			$description   = $usergroup['descriptionen'];
		}

		echo '<div class="usergroup">' . $groupname. '</div>
		'.Iif($description, '<div class="description">'.html($description).'</div>').'
		<ul class="user">';

		foreach ($usergroup['user'] AS $userid => $user) {
			$userfrontname = Iif(IS_CHINESE, $user['userfrontname'], $user['userfrontename']);
			if(!$userfrontname){
				$userfrontname = $user['username'];
			}
			
			switch ($user['type']) {
				case 1:
					$vvckey = PassGen(8);
?>	
<script> 
	function changesrc<?php echo $userid?>() 
	{  
	setCookie('safecookieG<?php echo $vvckey.COOKIE_KEY;?>','<?php echo md5($_CFG['cKillRobotCode'].$vvckey);?>');
	document.getElementById("cellphone").innerHTML='<iframe id="welive_iframe" style="width:100%;height:100%;" src="<?php echo '' . BASEURL . 'enter.php?uid='.$userid.'&code='.md5($userid.WEBSITE_KEY.$vvckey).'&vvckey='.$vvckey.'&url=https://'.$_SERVER['HTTP_HOST'].'/';?>" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
	} 
</script> 	
<?php				
//載入上線狀況
$query_welive_guest = "SELECT * FROM  welive_guest where  isonline = '1' && serverid = '".$userid."' ";
$welive_guest = mysql_query($query_welive_guest, $dataconfig) or die(mysql_error());
$row_welive_guest = mysql_fetch_assoc($welive_guest);
$totalRows_welive_guest = mysql_num_rows($welive_guest);					

					
					$alertmsg='This&nbsp;service&nbsp;is&nbsp;busy,&nbsp;please&nbsp;select&nbsp;other&nbsp;online&nbsp;customer&nbsp;service.'; //彈出忙碌訊息
					
					if($user['isonline'] && $totalRows_welive_guest=='1'){   //顯示有上線的客服  且  正在服務1位客人
						echo '<li style="background:url(' . TURL . 'images/user_online.gif) left no-repeat;padding-left:16px;">
						<a onClick=alert("'.$alertmsg.'")>
						' . $userfrontname. ' <span class=grey>[<span class=red>Busy</span>]</span></a></li>';

					}elseif($user['isonline'] && $totalRows_welive_guest=='0'){ //顯示有上線的客服 且 目前尚未服務任何客人
						echo '<li style="background:url(' . TURL . 'images/user_online.gif) left no-repeat;padding-left:16px;">
						<a onclick="changesrc'.$userid.'();">
						' . $userfrontname. ' <span class=grey>[<span class=red>' . $lang['chat'] . '</span>]</span></a></li>';						
						
					}else{ //顯示有下線的客服
						echo '<li style="background:url(' . TURL . 'images/user_offline.gif) left no-repeat;padding-left:16px;">
						<a onclick="setCookie(\'safecookieG'.$vvckey.COOKIE_KEY.'\',\''.md5($_CFG['cKillRobotCode'].$vvckey).'\');pp=window.open(\'' . BASEURL . 'comment.php?uid='.$userid.'&code='.md5($userid.WEBSITE_KEY.$vvckey).'&vvckey='.$vvckey.'\',\'newWin\',\'height=478,width=568,top=200,left=200,status=yes,toolbar=no,menubar=no,resizable=no,scrollbars=no,location=no,titlebar=no\');pp.focus();return false;" title="'.preg_replace('/\/\/1/i', $userfrontname, $lang['clickmsg']).'">
						<span class=grey>' . $userfrontname. ' [<span class=green>' . $lang['msg'] . '</span>]</span></a></li>';
					}
					break;

				case 2:
					echo '<li><a href="http://wpa.qq.com/msgrd?V=1&amp;Uin='.$user['username'].'&amp;Site='.APP_NAME.'&amp;Menu=yes" target="_blank" title="'.preg_replace('/\/\/1/i', $userfrontname, $lang['clickchat']).'"><img src="http://wpa.qq.com/pa?p=1:'.$user['username'].':4" height="16" alt="QQ" align="absmiddle"> '.$userfrontname.'</a></li>';
					break;

				case 3:
					echo '<li><img src="' . TURL . 'images/msn.gif" alt="MSN" align="absmiddle" /> <a href="msnim:chat?contact='.$user['username'].'" title="'.preg_replace('/\/\/1/i', $userfrontname, $lang['clickchat']).'">'.$userfrontname.'</a></li>';
					break;

				case 4:
					echo '<li><img src="http://mystatus.skype.com/smallclassic/'.urlencode($user['username']).'" alt="Skype" align="absmiddle"> <a href="skype:'.urlencode($user['username']).'?call" title="'.preg_replace('/\/\/1/i', $userfrontname, $lang['clickchat']).'">'.$userfrontname.'</a></li>';
					break;

				case 5:
					echo '<li><a href="http://amos1.taobao.com/msg.ww?v=2&uid='.urlencode($user['username']).'&s=2" target="_blank" title="'.preg_replace('/\/\/1/i', $userfrontname, $lang['clickchat']).'"><img src="http://amos1.taobao.com/online.ww?v=2&uid='.urlencode($user['username']).'&s=2" width="16" height="16" align="absmiddle" /> '.$userfrontname.'</a></li>';
					break;				
			}
		}

		echo '</ul>';
	}

	echo '</body></html>';


}

?>
