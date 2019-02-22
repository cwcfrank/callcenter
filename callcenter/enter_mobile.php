<?php

// +---------------------------------------------+
// |     Copyright  2010 - 2028 WeLive           |
// |     http://www.weentech.com                 |
// |     This file may not be redistributed.     |
// +---------------------------------------------+

include('includes/welive.Core.php');

header_nocache();

$uid = ForceIncomingInt('uid');
$code = ForceIncomingString('code');
$vvckey = ForceIncomingString('vvckey');
$fromurl = ForceIncomingString('url', 'unknown');
$gid = ForceInt(ForceIncomingCookie('weliveGID'.COOKIE_KEY));

if(!$uid OR !$code OR !$vvckey){
	$error = $lang['er_noaccess'];
}elseif(ForceIncomingCookie('safecookieG'.$vvckey.COOKIE_KEY) != md5($_CFG['cKillRobotCode'] . $vvckey)){
	$error = $lang['er_noaccess'];
	setcookie('safecookieG'.$vvckey.COOKIE_KEY, '', 0, '/');
}elseif(IsBannedIP(GetIP())){
	$error = $lang['er_bannedip'];
}else{
	$sql = "SELECT u.* FROM " . TABLE_PREFIX . "user u
				LEFT JOIN " . TABLE_PREFIX . "usergroup ug ON ug.usergroupid = u.usergroupid
				WHERE u.userid  = '$uid'
				AND   u.activated = 1
				AND   u.usergroupid <> 1
				AND   ug.activated = 1";

	$user = $DB->getOne($sql);

	if(!$user['userid'] OR $code != md5($user['userid'].WEBSITE_KEY.$vvckey)){
		$error = $lang['er_noaccess'];
	}elseif(!$user['isonline']){
		$error = $lang['er_uoffline'];
	}else{
		$transfer_uid = checkbusy($uid); //这里判断是否需要转接到其他客服
		if($transfer_uid){
			$sql = "SELECT u.* FROM " . TABLE_PREFIX . "user u
						LEFT JOIN " . TABLE_PREFIX . "usergroup ug ON ug.usergroupid = u.usergroupid
						WHERE u.userid  = '$transfer_uid'
						AND   u.activated = 1
						AND   u.usergroupid <> 1
						AND   ug.activated = 1";

			$user = $DB->getOne($sql);

			if(!$user['userid'] OR !$user['isonline']){
				$error = $lang['er_uoffline'];
			}else{
				$uid = $user['userid']; //更改UID
			}
		}
	}
}

if(isset($error)){
	header_utf8();
	$er_info = '<BR><BR><BR><BR><BR><BR><BR><center><font color=red>//1</font></center>';
	die(str_replace('//1', $error, $er_info));
}

//根据语言选择客服的信息
if(IS_CHINESE){
	$username = $user['userfrontname'];
	$userinfo = html($user['infocn']);
	$useradv = html($user['advcn']);
	$history_imgurl = TURL. 'images/history.gif';
	$message_imgurl = TURL. 'images/message.gif';
}else{
	$username = $user['userfrontename'];
	$userinfo = html($user['infoen']);
	$useradv = html($user['adven']);
	$history_imgurl = TURL. 'images/history_en.gif';
	$message_imgurl = TURL. 'images/message_en.gif';
}

//验证成功后写入或核实客人信息
$realtime = time();

//访客自动离线时间
$offline_time = ForceInt($_CFG['cAutoOffline']);
$offline_time = Iif($offline_time, $offline_time, 10);

if($gid){
	$guest = $DB->getOne("SELECT guestid FROM " . TABLE_PREFIX . "guest WHERE guestid  = '$gid'");
	
	//新增訪客紀錄
	$userAgent = get_userAgent($_SERVER['HTTP_USER_AGENT']);
	$DB->exe("INSERT INTO " . TABLE_PREFIX . "guest_record (guestid, guestip, browser, created, serverid, fromurl) VALUES ('$gid', '".GetIP()."', '$userAgent', '$realtime', '$uid', '$fromurl')");	
}

if(!$gid OR !$guest['guestid']){
	$userAgent = get_userAgent($_SERVER['HTTP_USER_AGENT']);

	$DB->exe("INSERT INTO " . TABLE_PREFIX . "guest (guestid, guestip, browser, lang, created, isonline, isbanned, serverid, fromurl) VALUES ('$gid', '".GetIP()."', '$userAgent', '".IS_CHINESE."', '$realtime', 0, 0, '$uid', '$fromurl')");

	$gid = $DB->insert_id();
	setcookie('weliveGID'.COOKIE_KEY, $gid, ($realtime+60*60*24), "/");
}else{
	$DB->exe("UPDATE " . TABLE_PREFIX . "guest SET fromurl = '$fromurl' WHERE guestid = '$gid'");
}

setcookie('weliveG'.COOKIE_KEY, md5($gid.WEBSITE_KEY.$uid.$_CFG['cKillRobotCode']), 0, "/");         //用于AJAX验证

$guest_record = $DB->getOne("SELECT id FROM " . TABLE_PREFIX . "guest_record order by id DESC");  //搜尋聊天室ID

$ajaxpending = 'uid=' . $uid . '&gid=' . $gid. '&id=' . $guest_record['id'];        //用于将客服ID和客人ID附加到AJAX URL
$welcome_info = preg_replace('/\/\/1/i', '<span class=spec>'.$gid.'</span>', $lang['welcome']);

$smilies = ''; //添加表情图标
for($i = 0; $i < 24; $i++){
	$smilies .= '<img src="'.TURL.'smilies/' . $i . '.gif" onclick="insertSmilies(\'[:' . $i . ':]\');">';
}

//添加颜色
$colors = array('000000','6C6C6C','969696','FF0000','FF6600','FFCC00','916200','CD8447','2B8400','2FEA00','999900','0000CC','0066FF','35A2C1','701B76','C531D0'); 
$color_squares = '';
foreach($colors as $key => $value){
	$color_squares .= '<div class="color_squares" style="background:#' . $value . '" onclick="insertColors(\'' . $value . '\');"></div>';
}


$js_var = "pagetitle=\"".SITE_TITLE."\",newmsg=\"$lang[newmsg]\",soundon=\"$lang[soundon]\",soundoff=\"$lang[soundoff]\",er_kickout=\"$lang[er_kickout]\",er_useroffline=\"$lang[er_useroffline]\",er_banned=\"$lang[er_banned]\",er_autooffline=\"$lang[er_autooffline]\",doonline=\"$lang[doonline]\",reonline=\"$lang[reonline]\",unbanned=\"$lang[unbanned]\",sender_sys=\"$lang[system]\",guestname=\"$lang[isay]\",username=\"$username\", t_url=\"".TURL."\"";

//下以输出页面
?>
<?php
//載入IDYOUR上線的人
$query_idyours_online = "SELECT * FROM  welive_user where  status = '1' order by rand()";
$idyours_online = mysql_query($query_idyours_online, $dataconfig) or die(mysql_error());
$row_idyours_online = mysql_fetch_assoc($idyours_online);
$totalRows_idyours_online = mysql_num_rows($idyours_online);

//載入SKYPE上線的人
$query_skype_online = "SELECT * FROM  welive_user where  status = '1' order by rand()";
$skype_online = mysql_query($query_skype_online, $dataconfig) or die(mysql_error());
$row_skype_online = mysql_fetch_assoc($skype_online);
$totalRows_skype_online = mysql_num_rows($skype_online);

//載入HANGOUTS上線的人
$query_hangouts_online = "SELECT * FROM  welive_user where  status = '1' order by rand()";
$hangouts_online = mysql_query($query_hangouts_online, $dataconfig) or die(mysql_error());
$row_hangouts_online = mysql_fetch_assoc($hangouts_online);
$totalRows_hangouts_online = mysql_num_rows($hangouts_online);

//如果客服忙線中 客人將無法連線蹦險是忙碌中
if($totalRows_idyours_online==0){
	?>
	<script type="text/javascript">
    alert("All service is busy, please wait.");
	window.close();
    </script>    
    <?php
	}
if($totalRows_skype_online==0){
	?>
	<script type="text/javascript">
    alert("All service is busy, please wait.");
	window.close();
    </script>    
    <?php
	}
if($totalRows_hangouts_online==0){
	?>
	<script type="text/javascript">
    alert("All service is busy, please wait.");
	window.close();
    </script>    
    <?php
	}		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo SITE_TITLE?></title>
<script type="text/javascript" src="includes/javascript/Ajax.js"></script>
<script type="text/javascript" src="includes/javascript/WeLive.js"></script>
<link rel="stylesheet" type="text/css" href="templates/styles.css">
<link rel="shortcut icon" href="favicon.ico" />
<script type="text/javascript" src="includes/javascript/BrowserDetect.js"></script>
<script type="text/javascript">
    var isFirefox = navigator.userAgent.match("Firefox");
    var isOpera = navigator.userAgent.match("Opera");
    var isSafarigoogle = navigator.userAgent.match("Safari");//Google瀏覽器是用這核心
   
    if(!isFirefox && !isOpera && !isSafarigoogle){
        alert('Your browser does not support audio and video,please use Google Chrome latest version APP.');window.close();
	}
</script>    
<script>
BrowserDetect.init();
if(BrowserDetect.browser=='Firfox' && BrowserDetect.version<52){
	alert("Your browser does not support audio and video,please use Google Chrome latest version APP.");window.close();
	}
if(BrowserDetect.browser=='Chrome' && BrowserDetect.version<60){
	alert("Your browser does not support audio and video,please use Google Chrome latest version APP.");window.close();
	}	
if(BrowserDetect.browser=='Opera' && BrowserDetect.version<41){
	alert("Your browser does not support audio and video,please use Google Chrome latest version APP.");window.close();
	}
</script> 
</head>
<body>
<img src="templates/images/loding.gif" width="100%" />
<div id="guest">
	<div id="guest_top">
		<div class="logo"><?php echo SITE_TITLE?></div>
		<div id="guestinfo"><?php echo $welcome_info?></div>
		<div class="timer_div"><span id="timer">00:00</span></div>
	</div><BR><BR>
	
		<div><button onclick="gusstphoneleave();" style=" width:100%; height:27px; background-color:#F00; color:#FFF;">Close Chat X</button></div>
	
	<?php /*?><div class="ico_history"><img src="<?php echo $history_imgurl?>"></div><?php */?>
    <iframe src="https://<?php echo $_SERVER['HTTP_HOST'];?>:8443/user.html#<?php echo $username.$gid;?>" width="100%" height="100%"></iframe>
    
    
<?php /*?>	<div id="history" style="width:350px; height:50px; top:310px;left:10px;"></div>
	<div class="guest_right" style="width:200px; left:190px; top:0px;">

         
		<div class="adv"><?php echo $useradv?></div>
	</div>
    
	<div id="colors" class="colors_div" style="display:none"><?php echo $color_squares?></div>
	<div id="smilies" class="smilies_div" style="display:none"><?php echo $smilies?></div>
	<div id="guest_tools" style="top:258px;display:none;">
		<div id="tools_sound" class="tools_sound_on" onmouseover="chClassname(this, 'sound');chSoundTitle(this);" onclick="toggleTools('sound');"></div>
		<div id="tools_smile" class="tools_smile_off" onclick="showSmilies(0);" onmouseover="showSmilies();" title="<?php echo $lang['smilies']?>"></div>
		<div id="tools_color" class="tools_color_off" onclick="showColors(0);" onmouseover="showColors();" title="<?php echo $lang['fontcolor']?>"></div>
		<div id="tools_bold" class="tools_bold_off" onmouseover="chClassname(this, 'bold');" onclick="toggleTools('bold');" title="<?php echo $lang['bold']?>"></div>
		<div id="tools_italic" class="tools_italic_off" onmouseover="chClassname(this, 'italic');" onclick="toggleTools('italic');" title="<?php echo $lang['italic']?>"></div>
		<div id="tools_underline" class="tools_underline_off" onmouseover="chClassname(this, 'underline');" onclick="toggleTools('underline');" title="<?php echo $lang['underline']?>"></div>
		<div style="margin-left:auto;" id="tools_reset" class="tools_reset_off" onmouseover="chClassname(this, 'reset');" onclick="ResetInput();" title="<?php echo $lang['reset']?>"></div>
		<div id="sounder" style="width:0;height:0;visibility:hidden;overflow:hidden;"></div>
	</div>

	
	<div class="message_div" style="width:190px; top:375px;left:10px;"><textarea id="message" class="message"></textarea></div>
	<div class="tools_send_div" style="left:228px; top:375px;">
		<div id="tools_send" class="tools_send" onmouseover="chClassname(this, 'send');" onclick="sending();return false;"><?php echo $lang['send']?></div>
	</div><?php */?>

	<div id="guest_bottom">
		<div class="sysinfo_div"><span id="status_ok" class="status_ok"><img src="<?php echo TURL?>images/status_ok.gif" align="top">&nbsp;&nbsp;<?php echo $lang['status_ok']?></span><span id="status_err" class="status_err"><img src="<?php echo TURL?>images/status_err.gif" align="top">&nbsp;&nbsp;<?php echo $lang['er_system']?></span><span id="status_err2" class="status_err"><img src="<?php echo TURL?>images/status_err.gif" align="top">&nbsp;&nbsp;<?php echo $lang['er_database']?></span></div>
		<div id="loading"><img src="<?php echo TURL?>images/waitt.gif" align="top"></div>
		<div class="copyright" id="copyright" style="display:none;"><?php echo COPYRIGHT?></div>
	</div>
</div>

<style type="text/css">html,body{overflow:hidden}</style>
<script type="text/javascript">
function gusstphoneleave() {
	setOffline();alert("You have left the room!");
	document.location.href="online.php";
}
var seconds=0, minutes=0, hours =0;
var sys_status=1, is_online=1, is_banned=0, kickout=0, ajaxpending = "<?php echo $ajaxpending?>";
var ajaxB="0", ajaxI="0", ajaxU="0", ajaxC="0", <?php echo $js_var?>;
var lock = 0, tt = 0, ttt = 0, tttt = 0, ttttt = 0, flashtitle_step = 0, allow_sound=1, response_tout = 0, ajax_last = 0;
var refresh_time = "<?php echo $_CFG['cUpdate'];?>";
var offline_time = "<?php echo $offline_time;?>";
var sound='<object data="<?php echo TURL?>sound.swf" type="application/x-shockwave-flash" width="1" height="1" style="visibility:hidden"><param name="movie" value="<?php echo TURL?>sound.swf" /><param name="menu" value="false" /><param name="quality" value="high" /></object>';

_attachEvent(window, "load", timer_start, document);
//_attachEvent(window, "beforeunload", setOffline);
//_attachEvent(window, "unload", setOffline);

initObj();
setOnline();
</script>

<script type="text/javascript">

//設定倒數秒數
var t = 5;

//顯示倒數秒收
function showTime()
{
    t -= 1;
    if(t==0)
    {
        location.href='https://<?php echo $_SERVER['HTTP_HOST'];?>:8443/demos/user_mobile.html?local=<?php echo $_GET['url'];?>#<?php echo $username.$gid;?>';
    }
    
    //每秒執行一次,showTime()
    setTimeout("showTime()",500);
}

//執行showTime()
showTime();
</script>

</body>
</html>