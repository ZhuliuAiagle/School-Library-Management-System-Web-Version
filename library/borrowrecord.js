$(document).ready(function(){
    $("#record").click(function(){
        alert("true");
        list = new Object();
        if($("#cno").val().length != 0) list.cno = $.trim($("#cno").val().toString());
        list = JSON.stringify(list);
        user = getCookie("usr");
        pass = getCookie("pas");
        var request = {
            PostType: 6,
            condition: list,
            info: null,
            usr: user,
            pas: pass
        }
        alert(list);
        $.get(
            "accessDB.php",
            request,
            function(data){
                alert(data);
                if(data == "LOGINERROR") {
                    document.cookie = "";
                    window.location.href='login.php';
                    return;
                }
                $("#group_one").html(data);
                page = new Page(3,'dataTables-example','group_one');
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