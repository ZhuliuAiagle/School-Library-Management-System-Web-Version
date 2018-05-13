$(document).ready(function(){
    $("#newreturn").click(function(){
        var list = Object();
        if($("input#cno").val().length != 0) list.cno = $("#cno").val().toString();
        else{alert("有项目为空！");return;}  
        if($("input#bno").val().length != 0) list.bno = $("#bno").val().toString();
        else{alert("有项目为空！");return;} 
        user = getCookie("usr");
        pass = getCookie("pas");
        list = JSON.stringify(list);
        var request = {
            PostType: 5,
            condition: null,
            info: list,
            usr: user,
            pas: pass
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
                    alert("还书成功！");
                }
            }
        );
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