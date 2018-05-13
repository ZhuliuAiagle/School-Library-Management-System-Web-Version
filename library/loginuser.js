$(document).ready(function(){
    $("#log-in").click(function(){
        username = $.trim($("#user").val());
        password = hex_md5($.trim($("#pass").val()));
        var post  = {
            PostType: 10,
            usr: username,
            pas: password 
        } 
        $.get(
            "accessDB.php",
            post,
            function(data){
                if(data == "LOGINERROR"){
                    alert("用户名或密码错误！");
                    document.cookie = "";
                    window.location.href="loginuser.php";
                }
                else {
                    alert("登陆成功！");
                    document.cookie = "";
                    exp = new Date();
                    exp.setTime(exp.getTime() + 1000 * 1000); 
                    document.cookie = ("usr=" + username + ";expires=" + exp.toGMTString());
                    document.cookie = ("pas=" + password + ";expires=" + exp.toGMTString());
                    document.cookie = ("isOn="+"2"+ ";expires=" + exp.toGMTString());
                    window.location.href="user.html";
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