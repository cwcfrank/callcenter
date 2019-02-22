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
	<title>Callcenter│ID Yours A Personalized Jewelry Collection</title>
	<style type="text/css">
	body {font-family:Verdana,Arial; margin:0; padding:0; font-size:12px; color:#292929;}
	div {margin:0 auto; padding:0;}
	ul,ol,li,img {margin:0; padding:0; border:none; list-style-type:none;}
	a {color:#292929;text-decoration:none;cursor:pointer;}
	a:hover {color:#292929;text-decoration:none;}
	.usergroup {padding:0;height:22px;line-height:22px;background:#E1E1E1;color:#1E8FBB;text-indent:23px;overflow:hidden;}
	.description {float:left;padding:3px 7px 0 7px;width:121px;overflow:hidden;line-height:18px;color:#9D5946;}
	.user {margin:0 0 0 7px;padding:2px 0 6px 0;white-space:nowrap;overflow:hidden;}
	.user li {line-height:200px;float:left;}
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
	<body>';

	$vvckey = PassGen(8);

	
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

//載入Facebook上線的人
$query_facebook_online = "SELECT * FROM  welive_user where  status = '1' order by rand()";
$facebook_online = mysql_query($query_facebook_online, $dataconfig) or die(mysql_error());
$row_facebook_online = mysql_fetch_assoc($facebook_online);
$totalRows_facebook_online = mysql_num_rows($facebook_online);

$alertmsg='All&nbsp;service&nbsp;is&nbsp;busy,&nbsp;please&nbsp;wait&nbsp;and&nbsp;call&nbsp;later.'; //彈出忙碌訊息

echo '<hr>';
echo '<h2>&nbsp;IDYOURS</h2>';	
?>
<script> 
	function changesrc<?php echo $userid?>() 
	{  
	setCookie('safecookieG<?php echo $vvckey.COOKIE_KEY;?>','<?php echo md5($_CFG['cKillRobotCode'].$vvckey);?>');
	document.getElementById("cellphone").innerHTML='<iframe id="welive_iframe" style="width:100%;height:100%;" src="<?php echo '' . BASEURL . 'enter.php?uid='.$userid.'&code='.md5($userid.WEBSITE_KEY.$vvckey).'&vvckey='.$vvckey.'&url=https://'.$_SERVER['HTTP_HOST'].'/';?>" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
	} 
</script> 
<?php 
if($totalRows_idyours_online>0){  

if(check_mobile()){//如果是用手機		

						echo '<a id="idyours" onclick="setCookie(\'safecookieG'.$vvckey.COOKIE_KEY.'\',\''.md5($_CFG['cKillRobotCode'].$vvckey).'\');pp=window.open(\'' . BASEURL . 'enter_mobile.php?uid='.$row_idyours_online['userid'].'&code='.md5($row_idyours_online['userid'].WEBSITE_KEY.$vvckey).'&vvckey='.$vvckey.'&url=\'+parent.welive_thisPageUrl,\'newWin\',\'height=918,width=1830,top=0,left=200,status=yes,toolbar=no,menubar=no,resizable=no,scrollbars=no,location=no,titlebar=no\');pp.focus();return false;" title="'.preg_replace('/\/\/1/i', $row_idyours_online['userfrontname'], $lang['clickchat']).'">
						<img src="' . TURL . 'images/idyours.png" alt="" style="width:50px;margin-left:25px;"/></a>';	
	
}else{
	  //用PC
	
						echo '<a id="idyours" onclick="setCookie(\'safecookieG'.$vvckey.COOKIE_KEY.'\',\''.md5($_CFG['cKillRobotCode'].$vvckey).'\');pp=window.open(\'' . BASEURL . 'enter_pc.php?uid='.$row_idyours_online['userid'].'&code='.md5($row_idyours_online['userid'].WEBSITE_KEY.$vvckey).'&vvckey='.$vvckey.'&url=\'+parent.welive_thisPageUrl,\'newWin\',\'height=950,width=700,top=0,left=200,status=yes,toolbar=no,menubar=no,resizable=no,scrollbars=no,location=no,titlebar=no\');pp.focus();return false;" title="'.preg_replace('/\/\/1/i', $row_idyours_online['userfrontname'], $lang['clickchat']).'">
						<img src="' . TURL . 'images/idyours.png" alt="" style="width:50px;margin-left:25px;"/></a>';		
	
	}
}else{
							echo '<a id="idyours" onClick=alert("'.$alertmsg.'");myFunction();><div align="left"><img src="' . TURL . 'images/idyours.png" alt="" style="width:50px;margin-left:25px;"/><div></a>';	

	
	}
	
echo '<hr>';
echo '<h2>&nbsp;&nbsp;&nbsp;SKYPE</h2>';

if($totalRows_skype_online>0){

echo "                           <iframe style=\"display:none;\" id=\"_detectSkypeClient_1506661585920\"></iframe>\n";
echo "                            <span id=\"SkypeButton_Call_".$row_skype_online['skype']."_1_paraElement\">\n";
echo "                            <a href=\"javascript://\" onclick=\"SkypeButton.tryAnalyzeSkypeUri('call', '0');SkypeButton.trySkypeUri_Generic('skype:".$row_skype_online['skype']."?call', '_detectSkypeClient_1506661585920', '0'); return false;\">\n";
echo '							  <div align="left"><img src="' . TURL . 'images/skype.png" alt="" style="width:50px;margin-left:25px;"/><div>';
echo "                            </a>\n";
echo "                            </span>";
}else{
							echo '<a onClick=alert("'.$alertmsg.'")><div align="left"><img src="' . TURL . 'images/skype.png" alt="" style="width:50px;margin-left:25px;"/><div></a>';	
	}

echo '<hr>';
echo '<h2>&nbsp;Hangouts</h2>';

if($totalRows_hangouts_online>0){

echo '<a target="_blank" href="https://hangouts.google.com/call/'.$row_hangouts_online['hangouts'].'">
						<div align="left"><img src="' . TURL . 'images/hangout.png" alt="" style="width:50px;margin-left:25px;"/><div>
						</a>';		

}else{
							echo '<a onClick=alert("'.$alertmsg.'")><div align="left"><img src="' . TURL . 'images/hangout.png" alt="" style="width:50px;margin-left:25px;"/><div></a>';	
	
	}


echo '<hr>';
echo '<h2>&nbsp;Facebook</h2>';

if($totalRows_facebook_online>0){

//echo '<a target="_blank" href="'.$row_facebook_online['facebook'].'">
echo '<a target="_blank" href="https://www.facebook.com/IDYoursHeureux/inbox/">
						<div align="left"><img src="' . TURL . 'images/facebook.png" alt="" style="width:50px;margin-left:25px;"/><div>
						</a>';		

}else{
							echo '<a onClick=alert("'.$alertmsg.'")><div align="left"><img src="' . TURL . 'images/facebook.png" alt="" style="width:50px;margin-left:25px;"/><div></a>';	
	
	}
	
	echo '</body></html>';


}

?>
							<script type="text/javascript" src="../js/skype-uri.js"></script>
                            
                            
                             <script type="text/javascript">
                             Skype.ui({
                                        "name": "call",
                                        "element": "SkypeButton_Call_<?php echo $row_skype_online['skype'];?>_1",
                                        "participants": ["<?php echo $row_skype_online['skype'];?>"],
                                        "imageColor": "blue",
                                        "imageSize": 32	 
                             });
                             </script>

                             
      <!--call again-->                       
      <script type="text/javascript" src="../js/jquery.min.js"></script>
                            
	  <script>
        var myVar;
        function myFunction() {
            myVar = setInterval(status, 3000);
        }
        </script>   
        
        <script language="javascript">
        function status(){ 
               
               var data = '';
               
               $.ajax({
                type: "POST",
                url: 'online_status_check.php',
                cache: false,
                data:data,
                error: function(){
                    //alert('Ajax request 發生錯誤');
                    },
                success:function(html){
                    
                        newData=html.replace(/\s/g,''); //使用正規表達式，否則出錯
                        if(newData=='OK'){  
                            alert("Callcenter is available,you can call again.");
                            self.location.reload();
                        
                        }
                    }
            });
        
        }
        </script>                           