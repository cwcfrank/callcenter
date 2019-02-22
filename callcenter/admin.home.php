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
<li>歡迎<u>'.$userinfo['username'].'</u> 進入管理面板! 為了確保系統安全, 請在關閉前點擊<a href="index.php?logout=1" onclick ="return confirm(\'確定退出管理面板嗎?\');">Logot</a>!</li>
</ul></div>
<BR>';


PrintFooter();

?>