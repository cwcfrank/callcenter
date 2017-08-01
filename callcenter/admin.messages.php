<?php
define('AUTH', true);
include('includes/welive.Core.php');
include(BASEPATH . 'includes/welive.Admin.php');
if($userinfo['usergroupid'] != 1) exit();
$action = ForceIncomingString('action', 'default');
$uid = $userinfo['userid'];
$ajaxpending = 'uid=' . $uid;        //需要动态变化, 用于将客服ID附加到AJAX URL
//print_r($uid);
PrintHeader($userinfo['username'], 'messages');
echo '<script type="text/javascript">var ajaxpending = "'. $ajaxpending .'";</script>'; //用于AJAX
/*$success[] = '抱歉, 免费版无此功能, 但不影响WeLive的正常使用.';
$success[] = '此功能方便管理员查阅、管理客服人员的交流记录.';
$success[] = 'WeLive商业版仅售 <span class=blueb>100</span> 元, 一次性付费, 永久使用及免费升级.';
$success[] = '购买商业版: QQ <span class=note>20577229</span> (加入时请注明: <span class=note>WeLive商业版</span>) <BR>&nbsp;&nbsp;&nbsp;&nbsp;或 致电 <a href="google/" target="_blank">闻泰网络</a>. 感谢您的支持!';
$successtitle = '功能限制说明';
BR(6);
PrintSuss($success, $successtitle);*/
//########### DELETE COMMENTS ###########
if($action == 'deletemessages'){
    $deletemessageids = $_POST['deletemessageids'];
    $page = ForceIncomingInt('p');
    $uid = ForceIncomingInt('u');
    for($i = 0; $i < count($deletemessageids); $i++){
        $DB->exe("DELETE FROM " . TABLE_PREFIX . "msg WHERE msgid = '".ForceInt($deletemessageids[$i])."'");
    }
    GotoPage('admin.messages.php'.Iif($page, '?p='.$page.Iif($uid, '&u='.$uid), Iif($uid, '?u='.$uid)), 1);
}
//########### PRINT DEFAULT ###########
if($action == 'default'){
    $NumPerPage =20;
    $page = ForceIncomingInt('p', 1);
    $start = $NumPerPage * ($page-1);
    $uid = ForceIncomingInt('u');
    $searchsql = Iif($uid, "WHERE toid ='$uid' ", "");
    $getusers = $DB->query("SELECT userid, userfrontname FROM " . TABLE_PREFIX . "user WHERE usergroupid <>1 ORDER BY userid");
    while($user = $DB->fetch($getusers)) {
        $users[$user['userid']] = $user['userfrontname'];
        $useroptions .= '<option value="' . $user['userid'] . '" ' . Iif($uid == $user['userid'], 'SELECTED', '') . '>' . $user['userfrontname'] . '</option>';
    }
    $getmessages = $DB->query("SELECT * FROM " . TABLE_PREFIX . "msg ".$searchsql." ORDER BY msgid DESC LIMIT $start,$NumPerPage");
    $maxrows = $DB->getOne("SELECT COUNT(msgid) AS value FROM " . TABLE_PREFIX . "msg ".$searchsql);
    echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td>&nbsp;&nbsp;&nbsp;共有: <span class=note>'.$maxrows['value'].'</span> 条聊天记录</td>
	<td>
	<form method="post" action="admin.messages.php" name="searchform">
	选择:&nbsp;<select name="u"><option value="0">全部客服</option>'. $useroptions .'</select>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="search" value=" 搜索留言 " />
	</form>
	</td>
	
	</tr>
	</table>
	<BR>
	<form method="post" action="admin.messages.php" name="messagesform">
	<input type="hidden" name="action" value="deletemessages">
	<input type="hidden" name="p" value="'.$page.'">
	<input type="hidden" name="u" value="'.$uid.'">
	<table id="welive_list" border="0" cellpadding="0" cellspacing="0" class="moreinfo">
	<thead>
	<tr>
	<th>FROM</th>
	<th>IP地址</th>
	<th>聊天内容</th>
	<th>TO</th>
	<th>时间</th>
	<th><input type="checkbox" checkall="group" onclick="select_deselectAll (\'messagesform\', this, \'group\');"> 删除</th>
	</tr>
	</thead>
	<tbody>';
    if($maxrows['value'] < 1){
        echo '<tr><td colspan="6"><center><span class=red>暂无任何留言!</span></center></td></tr></tbody></table></form>';
    }else{
        while($comment = $DB->fetch($getmessages)){
            echo '<tr>
            <td>'.Iif($users[$comment['fromid']], '<a href="admin.users.php?action=edituser&userid='.$comment['fromid'].'">' . $users[$comment['fromid']] . '</a>', $comment['fromid']).'</td>
			
			<td>' . Iif($comment['guestip'], '<a href="javascript:;" hidefocus="true" onclick="iplocation(\'' . $comment['msgid'] . '\', \'' . $comment['guestip'] . '\');return false;" title="查看IP归属地">' . $comment['guestip'] . '</a><br><span id="ip_' . $comment['msgid'] . '"></span>', '&nbsp;') . '</td>
			<td>'.nl2br($comment['msg']). '</a></td>
			<td>'.Iif($users[$comment['toid']], '<a href="admin.users.php?action=edituser&userid='.$comment['toid'].'">' . $users[$comment['toid']] . '</a>', $comment['toid']).'</td>
			<td>' . DisplayDate($comment['created'], 0, 1) . '</td>
			<td><input type="checkbox" name="deletemessageids[]" value="' . $comment['msgid'] . '" checkme="group"></td>
			</tr>';
        }
        $totalpages = ceil($maxrows['value'] / $NumPerPage);
        if($totalpages > 1){
            echo '<tr><th colspan="6" class="last">'.GetPageList('admin.messages.php', $totalpages, $page, 10, 'u', $uid).'</th></tr>';
        }
        echo '</tbody>
		</table>
		<div style="margin-top:20px;text-align:center;">
		<input type="submit" onclick="return confirm(\'确定删除所选聊天吗?\');" value=" 删除聊天 " />
		</div>
		</form>';
    }
}
PrintFooter();
?>