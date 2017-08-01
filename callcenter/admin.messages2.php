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

PrintHeader($userinfo['username'], 'messages');

$success[] = '抱歉, 暫無此功能, 但不影響目前客服系統的正常使用.';

$successtitle = '功能限制說明';

BR(6);

PrintSuss($success, $successtitle);


PrintFooter();

?>