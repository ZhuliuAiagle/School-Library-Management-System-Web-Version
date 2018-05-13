

function ConditionSelect()
{
    var list = Object();
    if($("input#bno").val().length != 0) list.bno = $.trim($("#bno").val().toString());
    if($("input#category").val().length != 0) list.category = $.trim($("#category").val().toString());
    if($("input#title").val().length != 0) list.title = $.trim($("#title").val().toString());
    if($("input#press").val().length != 0) list.press = $.trim($("#press").val().toString());
    if($("input#year").val().length != 0) list.year = $.trim($("#year").val());
    if($("input#lower").val().length != 0) list.lower = $.trim($("#lower").val());
    if($("input#upper").val().length != 0) list.upper = $.trim($("#upper").val());

    list = JSON.stringify(list);
    user = getCookie("usr");
    pass = getCookie("pas");
    alert(user+pass);
    var request = {
        PostType: 9,
        condition:list,
        info:null,
        usr: user,
        pas: pass
    }

    $.get("accessDB.php",request,function(data){
        if(data == "LOGINERROR") {
            document.cookie = "";
            window.location.href='login.php';
        }
        else {
            $("#group_one").html(data);
            page = new Page(3,'dataTables-example','group_one');}
    });
}

function BorrowBook(id){
    id = "#Select" + id[id.length - 1];
    var bno = $(id).html();
    var returndate = $("#returndate").val().toString();
    if(returndate == ""){
        alert("日期不能为空！");
        return;
    }
    var cno = getCookie("usr");
    if(cno == ''){
        document.cookie = "";
        window.location.href='loginuser.php';
        return;
    }
    var list = new Object();
    list.bno = bno;
    list.cno = cno;
    list.returndate = returndate;
    list = JSON.stringify(list);
    alert(list);
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
                window.location.href='loginuser.php';
            }
            else{
                alert("借书成功！");
            }
        }
    );
}

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