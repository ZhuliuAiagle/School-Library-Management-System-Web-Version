function SingleImport()
{
    var list = Object();
    if($("input#bno").val().length != 0) list.bno = $.trim($("#bno").val().toString());
    else
    {
        alert("有项目为空！");
        return;
    }
    if($("input#category").val().length != 0) list.category = $.trim($("#category").val().toString());
    else
    {
        alert("有项目为空！");
        return;
    }
    if($("input#title").val().length != 0) list.title = $.trim($("#title").val().toString());
    else
    {
        alert("有项目为空！");
        return;
    }
    if($("input#press").val().length != 0) list.press = $.trim($("#press").val().toString());
    else
    {
        alert("有项目为空！");
        return;
    }
    if($("input#year").val().length != 0) list.year =$.trim($("#year").val());
    else
    {
        alert("有项目为空！");
        return;
    }
    if($("input#author").val().length != 0) list.author = $.trim($("#author").val());
    else
    {
        alert("有项目为空！");
        return;
    }
    if($("input#price").val().length != 0) list.price = $.trim($("#price").val());
    else
    {
        alert("有项目为空！");
        return;
    }
    if($("input#total").val().length != 0) list.total = $.trim($("#total").val());
    else
    {
        alert("有项目为空！");
        return;
    }
    user = getCookie("usr");
    pass = getCookie("pas");
    list = JSON.stringify(list);
    alert(list);
    var request = {
        PostType: 2,
        condition:null,
        info:list,
        usr: user,
        pas: pass
    };
    $.get("accessDB.php",request,function(data){
        if(data == "INERROR"){
            alert("插入发生错误！请检查书籍的相关信息和编号！");
        }
        else if(data == "UPERROR"){
            alert("更改库存错误！请检查书籍编号！");
        }
        else if(data == "UPSUCCESS"){
            alert("该书籍已经存在！增加库存！");
        }
        else if(data == "INSUCCESS"){
            alert("新书入库成功！");
        }
    });

}

function FloodImport() {
    var fd = new FormData();
    fd.append("upload", 1);
    fd.append("upfile", $("#upfile").get(0).files[0]);
    list = JSON.stringify(fd);
    alert(list);
    $.post(
        "fileprocess.php",
        fd,
        function(data){
            alert(data);
            if(data == "LOGINERROR") {
                document.cookie = "";
                window.location.href='login.php';
            }
    });

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