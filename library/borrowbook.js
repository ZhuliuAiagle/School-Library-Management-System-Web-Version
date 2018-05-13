$(document).ready(function(){
    $("#newborrow").click(
        function(){
            var list = Object();
            if($("input#cno").val().length != 0) list.cno = $.trim($("#cno").val().toString());
            else{alert("有项目为空！");return;}  
            if($("input#bno").val().length != 0) list.bno = $.trim($("#bno").val().toString());
            else{alert("有项目为空！");return;}  
            list.returndate = $("#returndate").val().toString();
            if(list.returndate == ""){
                alert("日期不能为空！");
                return;
            }
            list = JSON.stringify(list);
            user = getCookie("usr");
            pass = getCookie("pas");
            var request = {
                PostType: 4,
                condition:null,
                usr: user,
                pas: pass,
                info:list
            }
            $.get(
                "accessDB.php",
                request,
                function(data){
                    if(data == "LOGINERROR") {
                        document.cookie = "";
                        window.location.href='login.php';
                    }
                    else{
                        alert("借书成功！");
                    }
                }
            );
    }
    );
});

function getCookie(name) {
    var strCookie = document.cookie;
    var arrCookie = strCookie.split("; ");
    for (var i = 0; i < arrCookie.length; i++) {
        var arr = arrCookie[i].split("=");
        if (arr[0] == name)
            return arr[1];
    }
    return "";
}