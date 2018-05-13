$(document).ready(function(){
    $("#Newdelete").click(function(){
        alert("true");
        var list = Object();
        if($("input#cno").val().length != 0) list.cno = $.trim($("#cno").val().toString());
        else{alert("有项目为空！");return;}
        user = getCookie("usr");
        pass = getCookie("pas");
        list = JSON.stringify(list); 
        var request = {
            PostType:8,
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
                else if(data == "NOTRETURN"){
                    alert("他还有书没还！");
                }
                else{
                    alert("删除成功！");
                }
            }
        )
    });
});