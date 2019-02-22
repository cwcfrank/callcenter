<?php
define('AUTH', true);
include('includes/welive.Core.php');
include(BASEPATH . 'includes/welive.Admin.php');
if($userinfo['usergroupid'] != 1) exit();
$action = ForceIncomingString('action', 'default');
$uid = $userinfo['userid'];
$ajaxpending = 'uid=' . $uid;        //需要动态变化, 用于将客服ID附加到AJAX URL
//print_r($uid);
PrintHeader($userinfo['username'], 'guest');
echo '<script type="text/javascript">var ajaxpending = "'. $ajaxpending .'";</script>'; //用于AJAX
//########### DELETE COMMENTS ###########
if($action == 'deleteguests'){
    $deleteguestid = $_POST['deleteguestid'];
    $page = ForceIncomingInt('p');
    $uid = ForceIncomingInt('u');
    for($i = 0; $i < count($deleteguestid); $i++){
        $DB->exe("DELETE FROM welive_guest_record WHERE id = '".ForceInt($deleteguestid[$i])."'");
    }
    GotoPage('admin.guest.php'.Iif($page, '?p='.$page.Iif($uid, '&u='.$uid), Iif($uid, '?u='.$uid)), 1);
}
//########### PRINT DEFAULT ###########
if($action == 'default'){
    $NumPerPage =20;
    $page = ForceIncomingInt('p', 1);
    $start = $NumPerPage * ($page-1);
    $uid = ForceIncomingInt('u');
	$sex = ForceIncomingString('sex', 'default');
    $searchsql = Iif($uid, "&& serverid ='$uid' ", "");
	
if($_POST['sex']){	//如果篩選性別
	$searchsql2 = Iif($sex, "&& sex ='$sex' ", "");
	}
if($_POST['age']){	//如果篩選年齡
	$searchsql3 = Iif($age, "&& age ='$age' ", "");
}
    $getusers = $DB->query("SELECT userid, userfrontname FROM " . TABLE_PREFIX . "user WHERE usergroupid <>1 ORDER BY userid");
    while($user = $DB->fetch($getusers)) {
        $users[$user['userid']] = $user['userfrontname'];
        $useroptions .= '<option value="' . $user['userid'] . '" ' . Iif($uid == $user['userid'], 'SELECTED', '') . '>' . $user['userfrontname'] . '</option>';
    }

	//如果篩選性別	
	$sexoptions .= '<option value="man" ' . Iif($_POST['sex'] == 'man', 'SELECTED', '') . '>Man</option><option value="woman" ' . Iif($_POST['sex'] == 'woman', 'SELECTED', '') . '>Woman</option>';
	
	//如果篩選年齡
	$ageoptions .= '<option value="under20" ' . Iif($_POST['age'] == 'under20', 'SELECTED', '') . '>under 20</option>
	<option value="21~30" ' . Iif($_POST['age'] == '21~30', 'SELECTED', '') . '>21~30</option>
	<option value="31~40" ' . Iif($_POST['age'] == '31~40', 'SELECTED', '') . '>31~40</option>
	<option value="41~50" ' . Iif($_POST['age'] == '41~50', 'SELECTED', '') . '>41~50</option>
	<option value="51~60" ' . Iif($_POST['age'] == '51~60', 'SELECTED', '') . '>51~60</option>
	<option value="over60" ' . Iif($_POST['age'] == 'over60', 'SELECTED', '') . '>over 60</option>';
	
    $getguest = $DB->query("SELECT * FROM " . TABLE_PREFIX . "guest_record WHERE id!='' ".$searchsql.$searchsql2.$searchsql3."  ORDER BY id DESC LIMIT $start,$NumPerPage");  //載入訪客紀錄資料庫
    $maxrows = $DB->getOne("SELECT COUNT(guestid) AS value FROM " . TABLE_PREFIX . "guest_record WHERE id!='' ".$searchsql.$searchsql2.$searchsql3);
	

	

    echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td>&nbsp;&nbsp;&nbsp;Toatal: <span class=note>'.$maxrows['value'].'</span> items record</td>
	<td>
	<form method="post" action="admin.guest.php" name="searchform">
	Service:&nbsp;<select name="u"><option value="0">All</option>'. $useroptions .'</select>&nbsp;Sex:&nbsp;<select name="sex"><option value="0">All</option>'. $sexoptions .'</select>&nbsp;Age:&nbsp;<select name="age"><option value="0">All</option>'. $ageoptions .'</select>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="search" value=" Search " />
	</form>
	</td>
	
	</tr>
	</table>
	<BR>
	<form method="post" action="admin.guest.php" name="messagesform">
	<input type="hidden" name="action" value="deleteguests">
	<input type="hidden" name="p" value="'.$page.'">
	<input type="hidden" name="u" value="'.$uid.'">
	<table id="welive_list" border="0" cellpadding="0" cellspacing="0" class="moreinfo">
	<thead>
	<tr>
	<th>From</th>
	<th>Sex</th>
	<th>Age</th>
	<th>IP Address</th>
	<th>Browser</th>
	<th>TO</th>
	<th>Creat time<BR>Leave time</th>
	<th>Lasting</th>
	<th><input type="checkbox" checkall="group" onclick="select_deselectAll (\'messagesform\', this, \'group\');"> Delete</th>
	</tr>
	</thead>
	<tbody>';
    if($maxrows['value'] < 1){
        echo '<tr><td colspan="9"><center><span class=red>No record!</span></center></td></tr></tbody></table></form>';
    }else{
        while($comment = $DB->fetch($getguest)){
			
//服務人員資料
$query_welive_user = $DB->query("SELECT * FROM " . TABLE_PREFIX . "user where userid = ".$comment['serverid']." ");
$row_welive_user = $DB->fetch($query_welive_user);
$ltime=floor(($comment['leaved']-$comment['created']));
//$day = floor($ltime/86400);//天
$hour = floor(($ltime%86400)/3600);//時
$minute = floor((($ltime%86400)%3600)/60);//分
$second = floor((($ltime%86400)%3600)%60);//秒
if($hour=='0' && $minute=='0'){
	$Lasting=$second.'s&nbsp';	
}elseif($hour=='0'){
	$Lasting=$minute.'m&nbsp'.$second.'s&nbsp';	
}else{
	$Lasting=$hour.'h&nbsp'.'m&nbsp'.$second.'s&nbsp';		
}

			if($comment['leaved']=='0'){$ttt='X';$Lasting='X';}else{$ttt=DisplayDate($comment['leaved'], 0, 1);} //離開時間
	
            echo '<tr>
            <td>'.Iif($comment['guestid'], '' . 'Guset '.$comment['guestid'] . '', 'Guset '.$comment['guestid']).'</td>
			
			<td>'.Iif($users[$comment['sex']], $users[$comment['sex']], ''.$comment['sex']).'</td>
			<td>'.Iif($users[$comment['age']], '' . $users[$comment['age']] . '', ''.$comment['age']).'</td>
			
			<td>' . Iif($comment['guestip'], '<a href="javascript:;" hidefocus="true" onclick="iplocation(\'' . $comment['id'] . '\', \'' . $comment['guestip'] . '\');return false;" title="View Country">' . $comment['guestip'] . '</a><br><span id="ip_' . $comment['id'] . '"></span>', '&nbsp;') . '</td>
			<td>'.Iif($users[$comment['browser']], '<a href="admin.users.php?action=edituser&userid='.$comment['browser'].'">' . $users[$comment['browser']] . '</a>', $comment['browser']).'</td>
			<td><a href="admin.users.php?action=edituser&userid='.$comment['serverid'].'">'.$row_welive_user['userfrontname'].'</a></td>
			<td>' . DisplayDate($comment['created'], 0, 1) . '<BR>' . $ttt . '</td>
			<td>'.$Lasting.'</td>
			<td><input type="checkbox" name="deleteguestid[]" value="' . $comment['id'] . '" checkme="group"></td>
			</tr>';
        }
        $totalpages = ceil($maxrows['value'] / $NumPerPage);
        if($totalpages > 1){
            echo '<tr><th colspan="6" class="last">'.GetPageList('admin.guest.php', $totalpages, $page, 10, 'u', $uid).'</th></tr>';
        }
        echo '</tbody>
		</table>
		<div style="margin-top:20px;text-align:center;">
		<input type="submit" onclick="return confirm(\'Are you sure you want to delete the selected guest??\');" value=" Delete the record " />
		</div>
		</form>';
    }
}
PrintFooter();
?>