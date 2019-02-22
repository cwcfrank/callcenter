<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body onload="myFunction();">
      <script type="text/javascript" src="../js/jquery.min.js"></script>                      
<script>
var myVar;
function myFunction() {
    myVar = setInterval(status, 3000);
}
</script>   

<script language="javascript">
function status(){ 
var data = '123456';
       
	   alert("123");
       $.ajax({
        type: "POST",
        url: 'online_status_check.php',
        cache: false,
        data:data,
        error: function(){
            alert('Ajax request 發生錯誤');
            },
        success:function(html){
			alert(html);
			    newData=html.replace(/\s/g,''); //使用正規表達式，否則出錯
				if(newData=='OK'){  
				    alert("PLa");
					self.location.reload();
				
				}else{  
					alert("error");
				}
            }
    });

}
</script>  
</body>
</html>