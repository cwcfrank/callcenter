function sex(gid) {
	var sex=document.getElementById("selectsex")
	ajax("support.php?act=sex&gid=" + gid + "&sex=" + sex.options[sex.selectedIndex].text, donothing)
   
}

function age(gid) {
	var age=document.getElementById("selectage")
	ajax("support.php?act=age&gid=" + gid + "&age=" + age.options[age.selectedIndex].text, donothing)
   
}

function $(id) {
    return typeof id == "string" ? document.getElementById(id) : id
}

function isUndefined(variable) {
    return typeof variable == 'undefined' ? true : false
}

function in_array(needle, haystack) {
    if (typeof needle == 'string' || typeof needle == 'number') {
        for (var i in haystack) {
            if (haystack[i] == needle) {
                return true
            }
        }
    }
    return false
}

function GetObjByE(e) {
    if (isUndefined(e)) e = window.event;
    var Obj = document.all ? e.srcElement : e.target;
    return Obj
}

function flashTitle() {
    clearInterval(tttt);
    flashtitle_step = 1;
    tttt = setInterval(function() {
        if (flashtitle_step == 1) {
            document.title = '【News】' + pagetitle;
            flashtitle_step = 2
        } else {
            document.title = '【　　　】' + pagetitle;
            flashtitle_step = 1
        }
    }, 200)
}

function stopFlashTitle() {
    if (flashtitle_step != 0) {
        flashtitle_step = 0;
        clearInterval(tttt);
        document.title = pagetitle
    }
}

function getLocalTime() {
    var date = new Date();

    function addZeros(value, len) {
        var i;
        value = "" + value;
        if (value.length < len) {
            for (i = 0; i < (len - value.length); i++) value = "0" + value
        }
        return value
    }
    return addZeros(date.getHours(), 2) + ':' + addZeros(date.getMinutes(), 2) + ':' + addZeros(date.getSeconds(), 2)
}

function _attachEvent(obj, evt, func, eventobj) {
    eventobj = !eventobj ? obj : eventobj;
    if (obj.addEventListener) {
        obj.addEventListener(evt, func, false)
    } else if (eventobj.attachEvent) {
        obj.attachEvent('on' + evt, func)
    }
}

function timer_start() {
    if (seconds >= 59) {
        seconds = 0;
        minutes += 1
    } else {
        seconds += 1
    }
    if (minutes >= 60) {
        minutes = 0;
        hours += 1
    }
    displaytime();
    setTimeout('timer_start()', 1000)
}

function displaytime() {
    var sec_display, mins_display, hours_display;
    if (seconds < 10) {
        sec_display = "0" + seconds
    } else {
        sec_display = seconds
    }
    if (minutes < 10) {
        mins_display = "0" + minutes
    } else {
        mins_display = minutes
    }
    if (hours < 10) {
        if (hours > 0) {
            hours_display = "0" + hours + ":"
        } else {
            hours_display = ""
        }
    } else {
        hours_display = hours + ":"
    }
    $("timer").innerHTML = hours_display + mins_display + ":" + sec_display
}

function chClassname(obj, newClassName) {
    var oldClassName = obj.className;
    obj.className = 'tools_' + newClassName + '_hover';
    obj.onmouseout = function() {
        this.className = oldClassName
    }
}

function chSoundTitle(obj) {
    if (allow_sound == 1) {
        obj.title = soundoff
    } else {
        obj.title = soundon
    }
}

function setFocus(id) {
    var obj = $("message_" + id);
    if (obj) obj.focus()
}

function showColors(id, delay) {
    clearTimeout(tt);
    setFocus(id);
    var eTools_color = $("tools_color_" + id);
    if (!eTools_color) return;
    var eSmilies = $("smilies_" + id);
    var eColors = $("colors_" + id);
    eTools_color.className = 'tools_color_hover';
    tt = setTimeout(function() {
        eSmilies.style.display = 'none';
        eColors.style.display = 'block';
        eColors.onmouseover = function() {
            clearTimeout(tt)
        };
        eColors.onmouseout = function() {
            clearTimeout(tt);
            tt = setTimeout(function() {
                eColors.style.display = 'none';
                if (ajaxC == "0") {
                    eTools_color.className = 'tools_color_off'
                } else {
                    eTools_color.className = 'tools_color_on'
                }
            }, 200)
        }
    }, (delay || delay == 0) ? delay : 300);
    eTools_color.onmouseout = function() {
        clearTimeout(tt);
        tt = setTimeout(function() {
            eColors.style.display = 'none';
            if (ajaxC == "0") {
                eTools_color.className = 'tools_color_off'
            } else {
                eTools_color.className = 'tools_color_on'
            }
        }, 280)
    }
}

function insertColors(id, code) {
    var me = $("message_" + id);
    if (!me) return;
    ajaxC = code;
    me.focus();
    for (var k in guest) {
        var obj = $('tools_color_' + guest[k]);
        var eMessage = $("message_" + guest[k]);
        if (!obj || !eMessage) continue;
        obj.className = 'tools_color_on';
        eMessage.style.color = "#" + code
    }
}

function showSmilies(id, delay) {
    clearTimeout(ttt);
    setFocus(id);
    var eTools_smile = $("tools_smile_" + id);
    if (!eTools_smile) return;
    var eSmilies = $("smilies_" + id);
    var eColors = $("colors_" + id);
    eTools_smile.className = 'tools_smile_hover';
    ttt = setTimeout(function() {
        eColors.style.display = 'none';
        eSmilies.style.display = 'block';
        eSmilies.onmouseover = function() {
            clearTimeout(ttt)
        };
        eSmilies.onmouseout = function() {
            clearTimeout(ttt);
            ttt = setTimeout(function() {
                eSmilies.style.display = 'none';
                eTools_smile.className = 'tools_smile_off'
            }, 200)
        }
    }, (delay || delay == 0) ? delay : 300);
    eTools_smile.onmouseout = function() {
        clearTimeout(ttt);
        ttt = setTimeout(function() {
            eSmilies.style.display = 'none';
            eTools_smile.className = 'tools_smile_off'
        }, 280)
    }
}

function insertSmilies(id, code) {
    var obj = $("message_" + id);
    if (!obj) return;
    var selection = document.selection;
    obj.focus();
    code += ' ';
    if (!isUndefined(obj.selectionStart)) {
        var opn = obj.selectionStart + 0;
        obj.value = obj.value.substr(0, obj.selectionStart) + code + obj.value.substr(obj.selectionEnd)
    } else if (selection && selection.createRange) {
        var sel = selection.createRange();
        sel.text = code;
        sel.moveStart('character', -code.length)
    } else {
        obj.value += code
    }
}

function showMsgs(id, delay) {
    clearTimeout(ttttt);
    setFocus(id);
    var eTools_msg = $("tools_msg_" + id);
    if (!eTools_msg) return;
    msgId = id;
    var eMsgs = $("msgs_div");
    var e = window.event || arguments.callee.caller.arguments[0];
    var xx = e.clientX;
    var yy = e.clientY;
    eTools_msg.className = 'tools_msg_hover';
    ttttt = setTimeout(function() {
        eMsgs.style.top = yy - 340 + 'px';
        eMsgs.style.left = xx - 290 + 'px';
        eMsgs.style.zIndex = zIndex + 100;
        eMsgs.style.display = 'block';
        eMsgs.onmouseover = function() {
            clearTimeout(ttttt)
        };
        eMsgs.onmouseout = function() {
            clearTimeout(ttttt);
            ttttt = setTimeout(function() {
                eMsgs.style.display = 'none';
                eTools_msg.className = 'tools_msg_off'
            }, 200)
        }
    }, (delay || delay == 0) ? delay : 300);
    eTools_msg.onmouseout = function() {
        clearTimeout(ttttt);
        ttttt = setTimeout(function() {
            eMsgs.style.display = 'none';
            eTools_msg.className = 'tools_msg_off'
        }, 280)
    }
}

function chClass(obj) {
    var oldClassName = obj.className;
    obj.className = 'msgs_line_hover';
    obj.onmouseout = function() {
        this.className = oldClassName
    }
}

function insertMsgs(msgobj) {
    var obj = $("message_" + msgId);
    if (!obj) return;
    var msg = msgobj.innerHTML;
    if (msg == '') return;
    var selection = document.selection;
    obj.focus();
    if (!isUndefined(obj.selectionStart)) {
        var opn = obj.selectionStart + 0;
        obj.value = obj.value.substr(0, obj.selectionStart) + msg + obj.value.substr(obj.selectionEnd)
    } else if (selection && selection.createRange) {
        var sel = selection.createRange();
        sel.text = msg;
        sel.moveStart('character', -msg.length)
    } else {
        obj.value += msg
    }
    clearTimeout(ttttt);
    var eMsgs = $("msgs_div");
    eMsgs.style.display = 'none';
    var eTools_msg = $("tools_msg_" + msgId);
    if (eTools_msg) eTools_msg.className = 'tools_msg_off'
}

function InitMyWin(id) {
    var obj;
    var eMessage = $("message_" + id);
    if (allow_sound == 0) {
        obj = $('tools_sound_' + id);
        if (obj) obj.className = 'tools_sound_off'
    }
    if (ajaxC != '0') {
        obj = $('tools_color_' + id);
        if (obj) obj.className = 'tools_color_on';
        if (eMessage) eMessage.style.color = "#" + ajaxC
    }
    if (ajaxB == '1') {
        obj = $('tools_bold_' + id);
        if (obj) obj.className = 'tools_bold_on';
        if (eMessage) eMessage.style.fontWeight = 'bold'
    }
    if (ajaxI == '1') {
        obj = $('tools_italic_' + id);
        if (obj) obj.className = 'tools_italic_on';
        if (eMessage) eMessage.style.fontStyle = 'italic'
    }
    if (ajaxU == '1') {
        obj = $('tools_underline_' + id);
        if (obj) obj.className = 'tools_underline_on';
        if (eMessage) eMessage.style.textDecoration = 'underline'
    }
}

function toggleTools(id, tool) {
    var obj = $('tools_' + tool + '_' + id);
    var eMessage = $("message_" + id);
    if (!obj || !eMessage) return;
    if (tool == 'sound') {
        if (allow_sound == 1) {
            allow_sound = 0;
            setSoundStyle('off')
        } else {
            allow_sound = 1;
            setSoundStyle('on')
        }
    } else if (tool == 'bold') {
        if (ajaxB == '1') {
            ajaxB = '0';
            setBoldStyle('off', 'normal')
        } else {
            ajaxB = '1';
            setBoldStyle('on', 'bold')
        }
    } else if (tool == 'italic') {
        if (ajaxI == '1') {
            ajaxI = '0';
            setItalicStyle('off', 'normal')
        } else {
            ajaxI = '1';
            setItalicStyle('on', 'italic')
        }
    } else if (tool == 'underline') {
        if (ajaxU == '1') {
            ajaxU = '0';
            setUnderlineStyle('off', 'none')
        } else {
            ajaxU = '1';
            setUnderlineStyle('on', 'underline')
        }
    }
    obj.onmouseout = null;
    eMessage.focus()
}

function ResetInput(id) {
    var eMessage = $("message_" + id);
    if (!eMessage) return;
    eMessage.value = '';
    eMessage.focus()
}

function setSoundStyle(style) {
    for (var k in guest) {
        var obj = $('tools_sound_' + guest[k]);
        if (!obj) continue;
        obj.className = 'tools_sound_' + style
    }
}

function setBoldStyle(tstyle, mstyle) {
    for (var k in guest) {
        var obj = $('tools_bold_' + guest[k]);
        var eMessage = $("message_" + guest[k]);
        if (!obj || !eMessage) continue;
        obj.className = 'tools_bold_' + tstyle;
        eMessage.style.fontWeight = mstyle
    }
}

function setItalicStyle(tstyle, mstyle) {
    for (var k in guest) {
        var obj = $('tools_italic_' + guest[k]);
        var eMessage = $("message_" + guest[k]);
        if (!obj || !eMessage) continue;
        obj.className = 'tools_italic_' + tstyle;
        eMessage.style.fontStyle = mstyle
    }
}

function setUnderlineStyle(tstyle, mstyle) {
    for (var k in guest) {
        var obj = $('tools_underline_' + guest[k]);
        var eMessage = $("message_" + guest[k]);
        if (!obj || !eMessage) continue;
        obj.className = 'tools_underline_' + tstyle;
        eMessage.style.textDecoration = mstyle
    }
}

function setStatus(status) {
    if (sys_status == status) return;
    sys_status = status;
    if (status == 0) {
        eStatus_ok.style.display = "none";
        eStatus_err.style.display = "block";
        eStatus_err2.style.display = "none"
    } else if (status == 1) {
        eStatus_ok.style.display = "block";
        eStatus_err.style.display = "none";
        eStatus_err2.style.display = "none"
    } else {
        eStatus_ok.style.display = "none";
        eStatus_err.style.display = "none";
        eStatus_err2.style.display = "block"
    }
}

function ajax(url, callback, updating, loading, format, method) {
    if (!method) method = "POST";
    if (!loading) loading = "loading";
    if (!callback) callback = welive_output;
    url += (url.indexOf("?") + 1) ? "&" : "?";
    url += "ajax_last=" + ajax_last + "&" + ajaxpending;
    jx.bind({
        "url": url,
        "onSuccess": callback,
        "onError": function(status) {
            setStatus(0)
        },
        "format": format,
        "method": method,
        "update": updating,
        "loading": loading
    });
    return false
}

function setOffline() {
    ajax("support.php?act=offline", donothing)
}
//更改為上線
function setOnline() {
    ajax("support.php?act=online");
    waiting()
}
//更改為忙碌中
function setstatuson() {
    ajax("support.php?act=statuson", donothing)
}

function setstatusoff() {
    ajax("support.php?act=statusoff");
    waiting()
}

function setbusy() {
    ajax("support.php?act=setbusy", donothing);
    var obj = $('setbusy');
    if (obj) obj.innerHTML = '<a href="javascript:;" onclick="unsetbusy();return false;"><b><span style="color:#FF3300;">Release ！！！</span></b></a>'
}

function unsetbusy() {
    ajax("support.php?act=unsetbusy", donothing);
    var obj = $('setbusy');
    if (obj) obj.innerHTML = '<a href="javascript:;" onclick="setbusy();return false;"><b>掛斷</b></a> '
}

function banned(gid) {
    ajax("support.php?act=banned&gid=" + gid, donothing);
    var obj = $('ban' + gid);
    if (obj) obj.innerHTML = '<a href="javascript:;" class="red" hidefocus="true" onclick="unbanned(' + gid + ');return false;">' + unban + '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:;" hidefocus="true" onclick="kickout(' + gid + ');return false;">Hang out</a>';
    if (in_array(gid, guest)) {
        guest['g' + gid]['banned'] = 1;
        output_win(gid + '|||0|||' + guestname + gid + baninfo + '|||0|||000|||0^^^')
    }
}

function unbanned(gid) {
    ajax("support.php?act=unbanned&gid=" + gid, donothing);
    var obj = $('ban' + gid);
    if (obj) obj.innerHTML = '<a href="javascript:;" hidefocus="true" onclick="banned(' + gid + ');return false;">' + ban + '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:;" hidefocus="true" onclick="kickout(' + gid + ');return false;">Hang out</a>';
    if (in_array(gid, guest)) {
        output_win(gid + '|||0|||' + guestname + gid + unbaninfo + '|||1|||000|||0^^^')
    }
}

function kickout(gid) {
    ajax("support.php?act=kickout&gid=" + gid, donothing);
    var row = $('g' + gid);
    if (row) eWelive.removeChild(row);
    gwin = $("win" + gid);
    guest['g' + gid] = null;
    for (var k in guest) {
        if (guest[k] == gid) {
            guest.splice(k, 1);
            break
        }
    }
    if (gwin) {
        var currentNo = parseInt(gwin.getAttribute("Minno"));
        document.body.removeChild(gwin);
        if (currentNo > 0) {
            sort_min(currentNo)
        }
    }
}

function iplocation(gid, ip) {
    var obj = $('ip_' + gid);
    if (obj) {
        if (obj.innerHTML != '') {
            if (obj.style.display == 'block') {
                obj.style.display = 'none'
            } else {
                obj.style.display = 'block'
            }
        } else {
            ajax("support.php?act=iplocation&ip=" + ip, function(data) {
                showlocation(gid, data)
            })
        }
    }
}

function showlocation(gid, data) {
    var obj = $('ip_' + gid);
    if (obj) {
        obj.style.display = 'block';
        obj.innerHTML = data
    }
}

function donothing() {
    return
}

function sending(id) {
    if (sys_status == 0 || sys_status == 2) return;
    var id = id ? id : get_lastopen();
    if (typeof guest['g' + id] == 'object') {
        if (guest['g' + id]['online'] == 0) return
    }
    var eMessage = $("message_" + id);
    if (!eMessage) return;
    if (lock == 0) {
        var ajaxLine = eMessage.value.replace(/(^\s+)|\s+$/g, "").replace(/\^\^\^|\|\|\|/g, "");
        if (ajaxLine.length > 0) {
            ajaxLine = ajaxLine.replace(/\?/g, '%3F').replace(/&/g, '%26').replace(/\r\n|\n|\r/g, "<br>");
            var url = "swaiting.php?act=sending&ajaxline=" + ajaxLine + "&ajaxbiu=" + ajaxB + ajaxI + ajaxU + "&ajaxcolor=" + ajaxC + "&gid=" + id;
            ajax(url)
        }
        eMessage.value = ""
    }
    eMessage.focus()
}

function waiting() {
    lock = 0;
    clearTimeout(response_tout);
    response_tout = setTimeout('welive()', refresh_time * 1000)
}

function welive() {
    if (lock == 0) {
        lock = 1;
        ajax("swaiting.php");
        waiting()
    }
}

function initObj() {
    eWelive = $('welive');
    eSounder = $('sounder');
    eNoguest = $('noguest');
    eStatus_ok = $('status_ok');
    eStatus_err = $('status_err');
    eStatus_err2 = $('status_err2')
}

function welive_output(data) {
    setStatus(1);
    var allguests, onlines = 0,
        allmsgs = '',
        aguest, gid, isonline, row, cell, gwin;
    var newdata = data;
    newdata = newdata.split('||||||');
    ajax_last = newdata[0];
    allguests = newdata[1];
    if (allguests == 2) {
        setStatus(2);
        return
    } else if (!allguests || allguests.length < 18) {
        return
    }
    lock = 1;
    clearTimeout(response_tout);
    if (newdata[2]) {
        allmsgs = newdata[2]
    }
    allguests = allguests.split('^^^');
    for (var i = 0; i < allguests.length; i++) {
        aguest = allguests[i].split('|||');
        
        isonline = aguest[4];
        gid = aguest[0];
        if (isonline == 1) {
            onlines += 1;
            row = $('g' + gid);
            if (!row) {
/*iframe無法用了 使用此方式*/	
				var myWindow = window.open('https://www.id-yours.com:8443/demos/admin.html#'+ callcentername + gid,'Guset'+ gid,config='height=800,width=1000');	
/*0214改版使用此方式*/									
                row = document.createElement("tr");
                row.setAttribute("id", "g" + gid);
                cell = document.createElement("td");
                cell.innerHTML = '<a href="javascript:;" hidefocus="true" onclick="openwin(' + gid + ', \'' + guestname + gid + '\');return false;" title="Open-Chat">' + guestname + gid + '</a> (<span id="new' + gid + '">0</span>)';
                cell.className = 'first';
                row.appendChild(cell);	
				
/*取得訪客來源網址*/var wor = aguest[6].split("=");

				if(wor[1]){//alert(wor[1]);
				 
				}else{
				var words = ["Guest", "Guest", "Guest", "Guest"];
					}
													
/*當天日期*/				
                cell = document.createElement("span");
				var Today=new Date();
				cell.innerHTML = '<BR><BR><BR><BR>Visit Date︰' + Today.getFullYear()+ '/' + (Today.getMonth()+1) + '/' + Today.getDate() + '  ' + Today.getHours() + ':' + Today.getMinutes() + ' ';
                row.appendChild(cell);		
/*訪客訪客試做訂單編號*/				
                cell = document.createElement("span");
				cell.innerHTML = '<BR><BR>Order Code︰'+ wor[1];
                row.appendChild(cell);	
/*訪客訪客會員編號*/				
                cell = document.createElement("span");
				cell.innerHTML = '<BR><BR>Member Code︰'+ wor[1];
                row.appendChild(cell);			
/*是否為黑名單*/				
                cell = document.createElement("span");
				cell.innerHTML = '<BR><BR>Blacklist︰<span class=red>' + wor[1] + '</span>';
                row.appendChild(cell);													
/*訪客資本資料*/				
                cell = document.createElement("span");
				cell.innerHTML = '<BR><BR>SEX︰<select class="form-control" id="selectsex" onChange="sex(' + gid + ');"><option value="">---</option><option value="man">man</option><option value="woman">woman</option></select><BR>AGE︰<select class="form-control" id="selectage" onChange="age(' + gid + ');"><option value="">---</option><option value="under20">under20</option><option value="21~30">21~30</option><option value="31~40">31~40</option><option value="41~50">41~50</option><option value="51~60">51~60</option><option value="61~70">61~70</option><option value="over70">over70</option>';
                row.appendChild(cell);				
/*訪客在線時間*/				
/*              cell = document.createElement("span");
                cell.setAttribute("id", "login" + gid);
                cell.innerHTML = '<BR><BR>Time Online︰'+getLocalTime();
                row.appendChild(cell);*/
/*訪客IP*/					
                cell = document.createElement("span");
                cell.innerHTML = '<BR><BR>IP Address︰<a href="javascript:;" hidefocus="true" onclick="iplocation(' + gid + ', \'' + aguest[1] + '\');return false;" title="IP-Country">' + aguest[1] + '</a><br><span id="ip_' + gid + '"></span>';
                row.appendChild(cell);
/*訪客瀏覽器*/					
                cell = document.createElement("span");
                cell.innerHTML = '<BR><BR>Browser︰'+aguest[2] + ' (' + (aguest[3] == 1 ? '<span class=green>中文</span>' : '<span class=red>English</span>') + ')';
                row.appendChild(cell);
/*訪客來源網頁*/					
                cell = document.createElement("span");
                cell.innerHTML = '<BR><BR>From Page︰'+getURL(aguest[6], 50);
                row.appendChild(cell);
/*執行動作*/					
                cell = document.createElement("span");
                cell.setAttribute("id", "ban" + gid);
                if (aguest[5] == 1) {
                    cell.innerHTML = '<BR><BR>Acotion︰<a href="javascript:;" class="red" hidefocus="true" onclick="unbanned(' + gid + ');return false;">' + unban + '</a>'
                } else {
                    cell.innerHTML = '<BR><BR>Acotion︰<a href="javascript:;" hidefocus="true" onclick="banned(' + gid + ');return false;">' + ban + '</a>'
                }
                cell.innerHTML = cell.innerHTML + '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:;" hidefocus="true" onclick="kickout(' + gid + ');return false;">Hang out</a>';
                row.appendChild(cell);
                eWelive.insertBefore(row, eWelive.childNodes[0]);
                openwin(gid, guestname + gid);
                allmsgs = gid + '|||0|||' + newguest + '|||1|||000|||0^^^' + (allmsgs.length > 18 ? allmsgs.replace(/\r\n|\n|\r/g, '') : '');
                InitMyWin(gid)
				
				/*畫面自動帶到此訪客列*/
				window.location="#g"+gid+"";				
				
            } else if (in_array(gid, guest) && guest['g' + gid]['online'] == 0) {
				
				/*提醒客服 之前的訪客又上線了*/
				alert("The 【Guest "+ gid +"】 is re-connect!");
				/*iframe無法用了 使用此方式*/	
				var myWindow = window.open('https://www.id-yours.com:8443/demos/admin.html#'+ callcentername + gid,'Guset'+ gid,config='height=800,width=1000');	
				/*0214改版使用此方式*/	
				
                guest['g' + gid]['online'] = 1;
                var eLogin = $("login" + gid);
                if (eLogin) eLogin.innerHTML = getLocalTime();
                allmsgs = gid + '|||0|||' + guestname + gid + reonline + '|||1|||000|||0^^^' + (allmsgs.length > 18 ? allmsgs.replace(/\r\n|\n|\r/g, '') : '')
				
				/*畫面自動帶到此訪客列*/
				window.location="#g"+gid+"";
            }
        } else {
            if (!in_array(gid, guest) || guest['g' + gid]['online'] == 0) continue;
            guest['g' + gid]['online'] = 0;
            var eLogin = $("login" + gid);
            if (eLogin) eLogin.innerHTML = '<span class=red>Offline</span>';
            gwin = $("win" + gid);
            if (gwin) {
                allmsgs = (allmsgs.length > 18 ? allmsgs.replace(/\r\n|\n|\r/g, '') : '') + gid + '|||0|||' + guestname + gid + er_goffline + '|||0|||000|||0^^^'
            }
			alert("【Guest "+ gid +"】 Left!");
        }
    }



	
    if (onlines == 0) {
        eNoguest.style.display = 'block';
    } else {
        eNoguest.style.display = 'none'
    }
    if (allmsgs.length > 18) {
        output_win(allmsgs)
    }
    waiting()
}

function output_win(data) {
    var eHistory, eNews, newdata, aline, gid, time, sender, msg, stype, content, ctype, biu, color, style;
    var lines = data.split('^^^');
    var lastid = get_lastopen();
    var do_flashTitle = true;
    for (var i = 0; i < lines.length; i++) {
        aline = lines[i].split('|||');
        if (aline[2]) {
            gid = aline[0];
            eNews = $("new" + gid);
            if (gid != lastid && eNews) {
                eNews.innerHTML = parseInt(eNews.innerHTML) + 1;
                eNews.style.cssText = 'color:red;font-weight:bold;'
            }
            eHistory = $("history_" + gid);
            if (!eHistory) continue;
            time = "<span class=time>" + getLocalTime() + "</span>";
            stype = aline[1];
            content = format_output(aline[2]);
            ctype = aline[3];
            biu = aline[4];
            color = aline[5];
            style = '';
            if (color != 0) style = "color:#" + color + ";";
            if (biu.match(/1\d\d/i)) style += "font-weight:bold;";
            if (biu.match(/\d1\d/i)) style += "font-style:italic;";
            if (biu.match(/\d\d1/i)) style += "text-decoration:underline;";
            if (stype == 0) {
                sender = "<span class=sender_sys>" + sender_sys + ":</span>"
            } else if (stype == 1) {
                sender = "<span class=sender_me>" + username + ":</span>";
                do_flashTitle = false
            } else if (stype == 2) {
                sender = "<span class=sender_user>" + guestname + gid + ":</span>"
            }
            if (ctype == 0) {
                msg = "<span class=sys_err>" + content + "</span>"
            } else if (ctype == 1) {
                msg = "<span class=sys_ok>" + content + "</span>"
            } else if (ctype == 2) {
                msg = "<span style=\"" + style + "\">" + content + "</span>"
            }
            newdata = time + sender + msg + "<br>";
            eHistory.innerHTML = eHistory.innerHTML + newdata;
            eHistory.scrollTop = eHistory.scrollHeight
        }
    }
    if (allow_sound == 1) {
        eSounder.innerHTML = sound
    }
    if (do_flashTitle) flashTitle();
    window.focus();
    setFocus(lastid)
}

function format_output(data) {
    data = data.replace(/((href=\"|\')?(((https?|ftp):\/\/)|www\.)([\w\-]+\.)+[\w\.\/=\?%\-&~\':+!#]*)/ig, function($1) {
        return getURL($1)
    });
    data = data.replace(/([\-\.\w]+@[\.\-\w]+(\.\w+)+)/ig, '<a href="mailto:$1" target="_blank">$1</a>');
    data = data.replace(/\[:(\d*):\]/g, '<img src="' + t_url + 'smilies/$1.gif">');

    return data
}

function getURL(url, limit) {
    if (url.substr(0, 5).toLowerCase() == 'href=') return url;
    if (!limit) limit = 60;
    var urllink = '<a href="' + (url.substr(0, 4).toLowerCase() == 'www.' ? 'http://' + url : url) + '" target="_blank" title="' + url + '">';
    if (url.length > limit) {
        url = url.substr(0, 30) + ' ... ' + url.substr(url.length - 18)
    }
    urllink += url + '</a>';
    return urllink
}
  
