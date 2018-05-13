$(document).ready(function(){
    $("#newcard").click(function(){
        var list = Object();
        if($("input#cno").val().length != 0) list.cno = $.trim($("#cno").val().toString());
        else{alert("有项目为空！");return;}
        if($("input#name").val().length != 0) list.name = $.trim($("#name").val().toString());
        else{alert("有项目为空！");return;}
        if($("input#dept").val().length != 0) list.department = $.trim($("#dept").val().toString());
        else{alert("有项目为空！");return;}
        if($("input#pas").val().length != 0) list.pas = hex_md5($.trim($("#pas").val().toString()));
        else{alert("有项目为空！");return;}
        if($("select#type").val().length != 0) 
        {
            if($.trim($("#type").val() == "普通职工"))
                list.type = 'O';
            else if($.trim($("#type").val() == "本科生"))
                list.type = 'U';
            else if($.trim($("#type").val() == "研博生"))
                list.type = 'G';
            else
                list.type = 'T'; 
        }
        else{alert("有项目为空！");return;}
        user = getCookie("usr");
        pass = getCookie("pas");
        list = JSON.stringify(list); 
        var request = {
            PostType:3,
            condition:null,
            info: list,
            usr: user,
            pas: pass
        }
        $.get(
            "accessDB.php",
            request,
            function(data){
                alert(data);
                if(data == "LOGINERROR") {
                    document.cookie = "";
                    window.location.href='login.php';
                }
            }
        )
    });
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