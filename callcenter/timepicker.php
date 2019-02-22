
<script src="assets/js/jquery-1.8.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
var timepicker = jQuery.noConflict(true);   /*設timepicker為$參數 以免衝突*/
</script>
<link rel="stylesheet" href="lib/timepicker/jquery-ui-1.10.4.custom/css/ui-lightness/jquery-ui-1.10.4.custom.css"><link rel="stylesheet" href="lib/timepicker/jquery-ui-1.10.4.custom/css/ui-lightness/jquery-ui-timepicker-addon.css">    
<script src="lib/timepicker/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js" type="text/javascript"></script>
<script src="lib/timepicker/jquery-ui-1.10.4.custom/js/jquery-ui-sliderAccess.js" type="text/javascript"></script>
<script src="lib/timepicker/jquery-ui-1.10.4.custom/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>


    
    
  
<script>
				
				var opt={dateFormat: 'yy-mm-dd',
				   showSecond: false, //顯示(秒)可拉動捲軸
				   showHour: false,  //顯示(小時)可拉動捲軸
				   showMinute: false,  //顯示(分鐘)可拉動捲軸
				   showTime: false,  //顯示(時間)
               
				   timeOnlyTitle:"Select minutes and seconds",
				   timeText:"Time",
				   hourText:"Hour",
				   minuteText:"Minute",
				   secondText:"Second",
				   millisecText:"Millisecond",
				   timezoneText:"Time zone",
				   currentText:"Now",
				   closeText:"Done",
				   amNames:["上午","AM","A"],
				   pmNames:["下午","PM","P"],
				   dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
				   dayNamesMin:["Sun","Mon","Tue","Wen","Thu","Fri","Sat"],
				   monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],
				   monthNamesShort:["January","February","March","April","May","June","July","August","September","October","November","December"],
				   prevText:"Last",
				   nextText:"Next",
				   weekHeader:"week"
															
               };
															
  timepicker(function() {
    $( "#datetimepicker1" ).datetimepicker(opt);		
  });
		
		timepicker(function() {
    $( "#datetimepicker2" ).datetimepicker(opt);
				$.datepicker.regional['zh-TW']={
   dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
   dayNamesMin:["日","一","二","三","四","五","六"],
   monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   prevText:"上月",
   nextText:"次月",
   weekHeader:"週"
			
};
//將預設語系設定為中文
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
//套用到表單
//$(function() {
	//$( "#日期欄位" ).datepicker({dateFormat: 'yy/mm/dd'});
//});
				
  });
  
  		timepicker(function() {
    $( "#datetimepicker3" ).datetimepicker(opt);
				$.datepicker.regional['zh-TW']={
   dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
   dayNamesMin:["日","一","二","三","四","五","六"],
   monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   prevText:"上月",
   nextText:"次月",
   weekHeader:"週"
			
};
//將預設語系設定為中文
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
//套用到表單
//$(function() {
	//$( "#日期欄位" ).datepicker({dateFormat: 'yy/mm/dd'});
//});
				
  });
  
  		timepicker(function() {
    $( "#datetimepicker4" ).datetimepicker(opt);
				$.datepicker.regional['zh-TW']={
   dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
   dayNamesMin:["日","一","二","三","四","五","六"],
   monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   prevText:"上月",
   nextText:"次月",
   weekHeader:"週"
			
};
//將預設語系設定為中文
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
//套用到表單
//$(function() {
	//$( "#日期欄位" ).datepicker({dateFormat: 'yy/mm/dd'});
//});
				
  });
  		timepicker(function() {
    $( "#datetimepicker5" ).datetimepicker(opt);
				$.datepicker.regional['zh-TW']={
   dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
   dayNamesMin:["日","一","二","三","四","五","六"],
   monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   prevText:"上月",
   nextText:"次月",
   weekHeader:"週"
			
};
//將預設語系設定為中文
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
//套用到表單
//$(function() {
	//$( "#日期欄位" ).datepicker({dateFormat: 'yy/mm/dd'});
//});
				
  }); 
  		timepicker(function() {
    $( "#datetimepicker6" ).datetimepicker(opt);
				$.datepicker.regional['zh-TW']={
   dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
   dayNamesMin:["日","一","二","三","四","五","六"],
   monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   prevText:"上月",
   nextText:"次月",
   weekHeader:"週"
			
};
//將預設語系設定為中文
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
//套用到表單
//$(function() {
	//$( "#日期欄位" ).datepicker({dateFormat: 'yy/mm/dd'});
//});
				
  }); 
  		timepicker(function() {
    $( "#datetimepicker7" ).datetimepicker(opt);
				$.datepicker.regional['zh-TW']={
   dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
   dayNamesMin:["日","一","二","三","四","五","六"],
   monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   prevText:"上月",
   nextText:"次月",
   weekHeader:"週"
			
};
//將預設語系設定為中文
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
//套用到表單
//$(function() {
	//$( "#日期欄位" ).datepicker({dateFormat: 'yy/mm/dd'});
//});
				
  }); 
  		timepicker(function() {
    $( "#datetimepicker8" ).datetimepicker(opt);
				$.datepicker.regional['zh-TW']={
   dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
   dayNamesMin:["日","一","二","三","四","五","六"],
   monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   prevText:"上月",
   nextText:"次月",
   weekHeader:"週"
			
};
//將預設語系設定為中文
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
//套用到表單
//$(function() {
	//$( "#日期欄位" ).datepicker({dateFormat: 'yy/mm/dd'});
//});
				
  });     
  </script>   
   