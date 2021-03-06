<?php

// +---------------------------------------------+
// |     Copyright  2010 - 2028 WeLive           |
// |     http://www.weentech.com                 |
// |     This file may not be redistributed.     |
// +---------------------------------------------+

define('AUTH', true);

include('includes/welive.Core.php');
include(BASEPATH . 'includes/welive.Admin.php');

if($userinfo['usergroupid'] != 1) exit();

$action = ForceIncomingString('action', 'default');
if(IsPost('updateusers')) $action= 'updateusers';
if(IsPost('deleteusers')) $action= 'deleteusers';

PrintHeader($userinfo['username'], 'users');

$cache_errortitle = '更新客服緩存錯誤';
$cache_errors = '用戶信息已保存到數據庫, 但更新在線客服緩存文件失敗, 前台客服小面板狀態無法更新! 請檢查cache/目錄是否存在或可寫?';

//########### UPDATE OR ADD USER ###########
if($action == 'insertuser' OR $action == 'updateuser'){
	$userid          = ForceIncomingInt('userid');
	$usergroupid     = ForceIncomingInt('usergroupid');
	$activated       = ForceIncomingInt('activated');
	$displayorder          = ForceIncomingInt('displayorder');
	$username        = ForceIncomingString('username');
	$password        = ForceIncomingString('password');
	$passwordconfirm = ForceIncomingString('passwordconfirm');
	$userfrontname        = ForceIncomingString('userfrontname');
	$userfrontename        = ForceIncomingString('userfrontename');
	$infocn        = ForceIncomingString('infocn');
	$infoen        = ForceIncomingString('infoen');
	$advcn        = ForceIncomingString('advcn');
	$adven        = ForceIncomingString('adven');

	if(strlen($username) == 0){
		$errors[] = '請輸入用戶名!';
	}elseif(!IsName($username)){
		$errors[] = '用戶名存在非法字符!';
	}elseif($DB->getOne("SELECT userid FROM " . TABLE_PREFIX . "user WHERE type = 1 AND username = '$username' AND userid != '$userid'")){
		$errors[] = '用戶名已存在, 請重新輸入!';
	}

	if($action == 'updateuser'){
		if(strlen($password) OR strlen($passwordconfirm)){
			if(!IsPass($password)){
				$errors[] = '密碼存在非法字符!';
			}elseif(strcmp($password, $passwordconfirm)){
				$errors[] = '兩次輸入的密碼不相同!';
			}
		}
	}else{
		if(strlen($password) == 0){
			$errors[] = '請輸入密碼!';
		}elseif(!IsPass($password)){
			$errors[] = '密碼存在非法字符!';
		}elseif($password != $passwordconfirm){
			$errors[] = '兩次輸入的密碼不相同!';
		}
	}

	if(strlen($userfrontname) == 0){
		$errors[] = '請輸入中文姓名!';
	}

	if(strlen($userfrontename) == 0){
		$errors[] = '請輸入英文姓名!';
	}


	if(isset($errors)){
		$errortitle = Iif($userid, '編輯用戶錯誤', '添加用戶錯誤');
		$action = Iif($userid, 'edituser', 'adduser');
	}else{
		if($action == 'updateuser'){
			$DB->exe("UPDATE " . TABLE_PREFIX . "user SET username    = '$username',
			".Iif($userid != 1 AND $userid != $userinfo['userid'], "usergroupid = '$usergroupid', activated = '$activated',")."
			".Iif($password, "password = '" . md5($password) . "',")."
			displayorder       = '$displayorder',
			userfrontname       = '$userfrontname',
			userfrontename       = '$userfrontename',
			infocn       = '$infocn',
			infoen       = '$infoen',
			advcn       = '$advcn',
			adven       = '$adven'										 
			WHERE userid      = '$userid'");
		}else{
			$DB->exe("INSERT INTO " . TABLE_PREFIX . "user (usergroupid, username, type, password, activated, userfrontname, userfrontename, infocn, infoen, advcn, adven) VALUES ('$usergroupid', '$username', 1, '".md5($password)."', 1, '$userfrontname', '$userfrontename', '$infocn', '$infoen', '$advcn', '$adven')");

			$newuserid = $DB->insert_id();
			$DB->exe("UPDATE " . TABLE_PREFIX . "user SET displayorder = '$newuserid' WHERE userid = '$newuserid'");
		}

		if(!storeCache()){ //更新小面板在線客服緩存文件
			$errortitle = $cache_errortitle;
			$errors = $cache_errors;
			$action = Iif($userid, 'edituser', 'adduser');
		}else{
			GotoPage('admin.users.php', 1);
		}
	}
}

//########### UPDATE OR ADD QQ MSN SKYPE ###########
if($action == 'insertqms' OR $action == 'updateqms'){
	$userid          = ForceIncomingInt('userid');
	$usergroupid     = ForceIncomingInt('usergroupid');
	$activated       = ForceIncomingInt('activated');
	$displayorder          = ForceIncomingInt('displayorder');
	$username        = ForceIncomingString('username');
	$userfrontname        = ForceIncomingString('userfrontname');
	$userfrontename        = ForceIncomingString('userfrontename');
	$type          = ForceIncomingInt('type');

	if(strlen($username) == 0){
		$errors[] = '請輸入特殊客服的名稱!';
	}

	if(isset($errors)){
		$errortitle = Iif($userid, '編輯特殊客服錯誤', '添加特殊客服錯誤');
		$action = Iif($userid, 'editqms', 'addqms');
	}else{
		if($action == 'updateqms'){
			$DB->exe("UPDATE " . TABLE_PREFIX . "user SET usergroupid = '$usergroupid',
			displayorder       = '$displayorder',
			username    = '$username',
			type    = '$type',
			activated = '$activated',
			userfrontname = '$userfrontname',
			userfrontename = '$userfrontename'
			WHERE userid      = '$userid'");
		}else{
			$DB->exe("INSERT INTO " . TABLE_PREFIX . "user (usergroupid, username, type, activated, userfrontname, userfrontename) VALUES ('$usergroupid', '$username', '$type', 1, '$userfrontname', '$userfrontename')");

			$newuserid = $DB->insert_id();
			$DB->exe("UPDATE " . TABLE_PREFIX . "user SET displayorder = '$newuserid' WHERE userid = '$newuserid'");
		}

		if(!storeCache()){ //更新小面板在線客服緩存文件
			$errortitle = $cache_errortitle;
			$errors = $cache_errors;
			$action = Iif($userid, 'editqms', 'addqms');
		}else{
			GotoPage('admin.users.php', 1);
		}
	}
}


//########### UPDATE users ###########

if($action == 'updateusers'){
	$userids   = $_POST['userids'];
	$displayorders   = $_POST['displayorders'];
	$activateds   = $_POST['activateds'];

    for($i = 0; $i < count($userids); $i++){
		$DB->exe("UPDATE " . TABLE_PREFIX . "user SET displayorder = '".ForceInt($displayorders[$i])."',
		activated = '".Iif($userids[$i] == 1 OR $userids[$i] == $userinfo['userid'], 1, ForceInt($activateds[$i]))."'
		WHERE userid = '".ForceInt($userids[$i])."'");
    }

	if(!storeCache()){ //更新小面板在線客服緩存文件
		$errortitle = $cache_errortitle;
		$errors = $cache_errors;
		$action = 'default';
	}else{
		GotoPage('admin.users.php', 1);
	}
}

//########### DELETE users ###########

if($action == 'deleteusers'){
	$deleteuserids   = $_POST['deleteuserids'];

    for($i = 0; $i < count($deleteuserids); $i++){
		$DB->exe("DELETE FROM " . TABLE_PREFIX . "user WHERE userid <>1 AND userid = '".ForceInt($deleteuserids[$i])."'");
    }

	if(!storeCache()){ //更新小面板在線客服緩存文件
		$errortitle = $cache_errortitle;
		$errors = $cache_errors;
		$action = 'default';
	}else{
		GotoPage('admin.users.php', 1);
	}
}

// ############################ DISPLAY QQ MSN SKYPE FORM #############################

if($action == 'editqms' OR $action == 'addqms'){

	$userid = ForceIncomingInt('userid');

	if(isset($errors)){
		PrintErrors($errors, $errortitle);

		$user = array('userid'   => $userid,
			  'usergroupid'  => Iif($userid == $userinfo['usergroupid'], $userinfo['usergroupid'], $usergroupid),
			  'activated'  => Iif($userid == $userinfo['userid'], $userinfo['activated'], $activated),
			  'displayorder'     => $displayorder,
			  'username'     => $username,
			  'userfrontname'     => $userfrontname,
			  'userfrontename'     => $userfrontename,
			  'type'     => $type);

	} else if($userid) {
		$user = $DB->getOne("SELECT * FROM " . TABLE_PREFIX . "user WHERE userid = '$userid'");
	}else{
		$user = array('userid' => 0, 'activated' => 1);
	}

	$info = '<font class=red> * Required</font>';

	$getgroups = $DB->query("SELECT usergroupid, groupname FROM " . TABLE_PREFIX . "usergroup WHERE usergroupid<> 1 ORDER BY usergroupid");

	$usergroupselect = '<select name="usergroupid">';
	while($usergroup = $DB->fetch($getgroups)) {
		$usergroupselect .= '<option value="' . $usergroup['usergroupid'] . '" ' . Iif($user['usergroupid'] == $usergroup['usergroupid'], ' SELECTED') . '>' . $usergroup['groupname'] . '</option>';
	}
	$usergroupselect .= '</select>';

	echo '<form method="post" action="admin.users.php">
	<input type="hidden" name="action" value="' . Iif($userid, 'updateqms', 'insertqms') . '">
	<input type="hidden" name="userid" value="' . $user['userid'] . '">
	<table id="welive_list" border="0" cellpadding="0" cellspacing="0" class="maintable">
	<thead>
	<tr>
	<th colspan="2">'.Iif($userid, '編輯QQ, MSN或Skype等', '添加QQ, MSN或Skype等').'</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td width="360">所屬客服群組:</td>
	<td>'.$usergroupselect.'</td>
	</tr>
	<tr>
	<td width="360">選擇類別:</td>
	<td><select name="type"><option value="2" ' . Iif($user['type'] == 2, ' SELECTED') . '>QQ號碼</option><option value="3" ' . Iif($user['type'] == 3, ' SELECTED') . '>MSN帳號</option><option value="4" ' . Iif($user['type'] == 4, ' SELECTED') . '>Skype帳號</option><option value="5" ' . Iif($user['type'] == 5, ' SELECTED') . '>旺旺帳號</option></select></td>
	</tr>
	<tr>
	<td>帳號名稱:</td>
	<td><input type="text" name="username" value="'.$user['username'].'" size="30" maxlength="64">'.$info.'</td>
	</tr>
	<tr>
	<td>客服小面板中顯示的中文名稱:</td>
	<td><input type="text" name="userfrontname" value="'.$user['userfrontname'].'" size="30"><font class=gey> * 留空將顯示賬號名稱</font></td>
	</tr>
	<tr>
	<td>客服小面板中顯示的英文名稱:</td>
	<td><input type="text" name="userfrontename" value="'.$user['userfrontename'].'" size="30"><font class=gey> * 留空將顯示賬號名稱</font></td>
	</tr>	';

	if($userid){
		echo '<tr>
		<td>是否激活?</td>
		<td><input type="checkbox" name="activated" value="1" ' . Iif($user['activated'] == 1, 'checked="checked"') .'></td>
		</tr>
		<tr>
		<td>顯示順序:</td>
		<td><input type="text" name="displayorder" value="'.$user['displayorder'].'" size="10"></td>
		</tr>	';
	}

	echo '
	</tbody>
	</table>';

	PrintSubmit(Iif($userid, 'Save', 'Delete'));
	
}

// ############################ DISPLAY USER FORM #############################

if($action == 'edituser' OR $action == 'adduser'){

	$userid = ForceIncomingInt('userid');

	if(isset($errors)){
		PrintErrors($errors, $errortitle);

		$user = array('userid'   => $userid,
			  'usergroupid'  => Iif($userid == $userinfo['usergroupid'], $userinfo['usergroupid'], $usergroupid),
			  'activated'  => Iif($userid == $userinfo['userid'], $userinfo['activated'], $activated),
			  'displayorder'     => $displayorder,
			  'username'     => $username,
			  'userfrontname'     => $userfrontname,
			  'userfrontename'     => $userfrontename,
			  'infocn'     => $_POST['infocn'],
			  'infoen'     => $_POST['infoen'],
			  'advcn'     => $_POST['advcn'],
			  'adven'     => $_POST['adven']);

	} else if($userid) {
		$user = $DB->getOne("SELECT * FROM " . TABLE_PREFIX . "user WHERE userid = '$userid'");
	}else{
		$user = array('userid' => 0, 'activated' => 1);
	}

	$info = '<font class=red> * Required</font>';
	$info_pass = Iif($userid, '<font class=green> * 不修改請留空</font>', $info);

	$getgroups = $DB->query("SELECT usergroupid, groupname FROM " . TABLE_PREFIX . "usergroup ORDER BY usergroupid");

	$usergroupselect = '<select name="usergroupid" ' . Iif($user['userid'] == 1 OR $userid == $userinfo['userid'], 'disabled') .'>';
	while($usergroup = $DB->fetch($getgroups)) {
		$usergroupselect .= '<option value="' . $usergroup['usergroupid'] . '" ' . Iif($user['usergroupid'] == $usergroup['usergroupid'], ' SELECTED') . '>' . $usergroup['groupname'] . '</option>';
	}
	$usergroupselect .= '</select>';

	echo '<form method="post" action="admin.users.php">
	<input type="hidden" name="action" value="' . Iif($userid, 'updateuser', 'insertuser') . '">
	<input type="hidden" name="userid" value="' . $user['userid'] . '">
	<table id="welive_list" border="0" cellpadding="0" cellspacing="0" class="moreinfo">
	<thead>
	<tr>
	<th colspan="2">'.Iif($userid, 'Edit', 'Add Account').'</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td width="360">Group:</td>
	<td>'.$usergroupselect.'</td>
	</tr>
	<tr>
	<td>LoginName:</td>
	<td><input type="text" name="username" value="'.$user['username'].'" size="30">'.$info.'</td>
	</tr>';

	if($userid){
		echo '<tr>
		<td>Status?</td>
		<td><input type="checkbox" ' . Iif($user['userid'] == 1 OR $userid == $userinfo['userid'], 'disabled') .' name="activated" value="1" ' . Iif($user['activated'] == 1, 'checked="checked"') .'></td>
		</tr>
		<tr>
		<td>No.:</td>
		<td><input type="text" name="displayorder" value="'.$user['displayorder'].'" size="10"></td>
		</tr>	';
	}

	echo '<tr>
	<td>Password:</td>
	<td><input type="password" name="password" size="30">'.$info_pass.'</td>
	</tr>
	<tr>
	<td>Password Again:</td>
	<td><input type="password" name="passwordconfirm" size="30">'.$info_pass.'</td>
	</tr>
	<tr>
	<td>NickName:</td>
	<td><input type="text" name="userfrontname" value="'.$user['userfrontname'].'" size="30">'.$info.'</td>
	</tr>
	<tr>
	<td>Name:</td>
	<td><input type="text" name="userfrontename" value="'.$user['userfrontename'].'" size="30">'.$info.'</td>
	</tr>
	<tr>
	<td>中文簡介:<BR><span class=note2>說明: 允許使用HTML代碼, 如換行可輸入&lt;br&gt;<BR>如要插入客服人員的像片, 圖片寬度要求不越過106px.</span></td>
	<td><textarea name="infocn" rows="6"  style="width:300px;">'.$user['infocn'].'</textarea></td>
	</tr>
	<tr>
	<td>英文簡介:<BR><span class=note2>說明: 同上.</span></td>
	<td><textarea name="infoen" rows="6"  style="width:300px;">'.$user['infoen'].'</textarea></td>
	</tr>
	<tr>
	<td>中文廣告:<BR><span class=note2>說明: 同上.</span></td>
	<td><textarea name="advcn" rows="6"  style="width:300px;">'.$user['advcn'].'</textarea></td>
	</tr>
	<tr>
	<td>英文廣告:<BR><span class=note2>說明: 同上.</span></td>
	<td><textarea name="adven" rows="6"  style="width:300px;">'.$user['adven'].'</textarea></td>
	</tr>
	</tbody>
	</table>';

	PrintSubmit(Iif($userid, 'Save', '+Add'));
	
}

//########### PRINT DEFAULT ###########

if($action == 'default'){
	if(isset($errors)){
		PrintErrors($errors, $errortitle);
	}

	$getgroups = $DB->query("SELECT usergroupid, groupname FROM " . TABLE_PREFIX . "usergroup ORDER BY usergroupid");
	while($usergroup = $DB->fetch($getgroups)) {
		$usergroups[$usergroup['usergroupid']] = $usergroup['groupname'];
	}

	$getusers = $DB->query("SELECT userid, usergroupid, displayorder, username, type, activated, isonline, status, userfrontname, userfrontename, lastlogin FROM " . TABLE_PREFIX . "user ORDER BY usergroupid, displayorder");

	echo '&nbsp;&nbsp;&nbsp;<a href="admin.users.php?action=adduser">+Add Account</a>&nbsp;&nbsp;&nbsp;
	<BR><BR><form method="post" action="admin.users.php" name="usersform">
	<table id="welive_list" border="0" cellpadding="0" cellspacing="0" class="maintable">
	<thead>
	<tr>
	<th>No.</th>
	<th>LoginName</th>
	<th>Status</th>
	<th>Group</th>
	<th>NickName</th>
	<th>Name</th>
	<th>Online/Offline</th>
	<th>Status</th>
	<th>LastTime</th>
	<th>Check</th>
	</tr>
	</thead>
	<tbody>';

	$types = array('1' => '', '2' => 'QQ', '3' => 'MSN', '4' => 'Skype', '5' => '旺旺');

    while($user = $DB->fetch($getusers)){

		$typename = $types[$user['type']];

		echo '<tr>
		<td>
		<input type="hidden" name="userids[]" value="' . $user['userid'] . '">
		<input type="text" name="displayorders[]" value="' . $user['displayorder'] . '"  size="4"></td>
		</td>
		<td><a href="admin.users.php?action='.Iif($user['type']>1, 'editqms', 'edituser').'&userid='.$user['userid'].'" '.Iif(!$user['activated'], 'class="red"').'>' . $user['username']. '</a>'.Iif($typename, '&nbsp;&nbsp;('.$typename.')').'</td>
		<td>
		<select name="activateds[]">
		<option value="1">Normal</option>
		<option style="color:red;" value="0" ' . Iif(!$user['activated'], 'SELECTED', '') . '>Ban</option>
		</select></td>
		<td>' . $usergroups[$user['usergroupid']]. '</td>
		<td>' . Iif($user['userfrontname'], $user['userfrontname'], '-'). '</td>
		<td>' . Iif($user['userfrontename'], $user['userfrontename'], '-'). '</td>
		<td>'.Iif($typename, '-', Iif($user['isonline'], '<span class="green">Online</span>', 'Offline')).'</td>
		<td>'.Iif($typename, '-', Iif($user['status'], '<span class="green">ON-AIR</span>', 'Busy')).'</td>
		<td>'.Iif($typename, '-', Iif($user['lastlogin'], DisplayDate($user['lastlogin'], '', 1), 'Never')).'</td>
		<td><input type="checkbox" name="deleteuserids[]" value="'.$user['userid'].'" '.Iif($user['userid'] == 1 OR $user['userid'] == $userinfo['userid'], 'disabled').'></td>
		</tr>';
	}

	echo '</tbody>
	</table>
	<div style="margin-top:20px;text-align:center;">
	<input type="submit" name="updateusers" value=" Save " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="deleteusers" onclick="return confirm(\'Are you sure you want to delete the selected user??\');" value=" Delete " />
	</div>
	</form>';

}

PrintFooter();

?>

