<?php

// +---------------------------------------------+
// | Copyright 2010 - 2028 WeLive |
// | http://www.weentech.com |
// | This file may not be redistributed. |
// +---------------------------------------------+

define('AUTH', true);

include('includes/welive.Core.php');
include(BASEPATH . 'includes/welive.Admin.php');

if($userinfo['usergroupid'] != 1) exit();

$updates = Iif(ForceIncomingInt('check'), 1, 0);

PrintHeader($userinfo['username']);

echo '<div><ul>
<li>歡迎<u>'.$userinfo['username'].'</u> 進入管理面板! 為了確保系統安全, 請在關閉前點擊<a href="index.php?logout=1" onclick ="return confirm(\'確定退出管理面板嗎?\');">安全退出</a>!</li>
<li>隱私保護: <span class="note2">鄭重承諾, 您在使用本系統時, 開發商不會收集您的任何信息</span>.</ li>
</ul></div>
<BR>
<table border="0" cellpadding="0" cellspacing="0" class="normal" width="600">
<tr>
<td><b>程序名稱</b></td>
<td><b>您現在的版本</b></td>
<td><b>最新版本</b></td>
</tr>
<tr>
<td><b>'.APP_NAME.'</b></td>
<td>' . APP_VERSION . '(UTF-8)</td>
<td><span id="welive_latest_versioninfo"></span></td>
</tr>
</table>
<div id="welive_latest_moreinfo"></div>';

if(!$updates){
echo '<script type="text/javascript">$("welive_latest_versioninfo").innerHTML = "<a href=\"admin.home.php?check=1\">檢測最新版本</a>";< /script>';
}else{
echo '<script language="javascript" type="text/javascript" src="http://www.weentech.com/welive_version/versioninfo.js?temp='.rand().'"></script>
<script type="text/javascript">
if(typeof(v) == "undefined"){
$("welive_latest_versioninfo").innerHTML = "<font class=red>無法連接!</font>";
}else{
var welive_old_version = parseInt("' . APP_VERSION . '".replace(/\./g,""));
var welive_latest_version = parseInt(v.replace(/\./g,""));

if(welive_old_version < welive_latest_version ){
$("welive_latest_versioninfo").innerHTML = "<font class=red>"+v+"</font>";
$("welive_latest_moreinfo").innerHTML = "<br>請登錄<a href=\"http://www.weentech.com/bbs/\" target=\"_blank\">聞泰網絡weentech.com< /a> 下載升級!";
}else{
$("welive_latest_versioninfo").innerHTML = "<font class=green>暫無更新!</font>";
}

}
</script>';
}

echo '<BR><BR><BR>
<table id="welive_list" border="0" cellpadding="0" cellspacing="0" class="maintable">
<thead>
<tr>
<th><B>客服基本使用說明:</B></th>
</tr>
</thead>
<tbody>
<tr>
<td>1. 在頁面中插入以下代碼顯示客服小面板(浮動):<br><span class="note2">&lt;script src="http://網址/WeLive安裝目錄/welive.php" language="javascript"&gt;&lt;/script&gt;</span></td>
</tr>
<tr>
<td>2. 在頁面&lt;body&gt;&lt;/body&gt;之間任意位置插入以下代碼顯示客服圖片(固定):<br><span class="note2">&lt;script src="http:/ /網址/WeLive安裝目錄/welive_image.php" language="javascript"&gt;&lt;/script&gt;</span></td>
</tr>
<tr>
<td>3. 系統默認安裝後, 客服人員的登錄密碼與管理員相同, 請自行修改(只有客服登錄後方可提供在線服務).</td>
</tr>
<tr>
<td>4. 在客服操作面板, 按Esc鍵: 快速關閉當前訪客小窗口.</td>
</tr>
<tr>
<td>5. 在客服操作面板, 按Enter鍵: 快速提交當前訪客小窗口中輸入的內容.</td>
</tr>
<tr>
<td>6. 在客服操作面板, 按Ctrl + 下箭頭鍵: 快速最小化訪客小窗口.</td>
</tr>
<tr>
<td>7. 在客服操作面板, 按Ctrl + 上箭頭鍵: 快速展開訪客小窗口.</td>
</tr>
<tr>
<td>8. 在客服操作面板, 按Ctrl + 左或右箭頭鍵: 快速在展開的訪客小窗口中切換.</td>
</tr>
<tr>
<td>9. 在客服操作面板, 點擊"掛起"後, 當訪客點擊當前客服時, 系統將檢測是否有同組的, 在線且未掛起的客服, 如果有則自動轉接到其他客服(掛起功能相當於忙碌自動轉接功能).</td>
</tr>
</tbody>
</table>';


PrintFooter();

?>