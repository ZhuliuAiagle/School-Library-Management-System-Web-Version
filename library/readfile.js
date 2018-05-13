$(document).ready(function()
{
$("#import").click(function(){//点击导入按钮，使files触发点击事件，然后完成读取文件的操作。
    $("#files").click();
})});

String.prototype.trim = function() {
    return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
}

function _import()
{
    var selectedFile = document.getElementById("files").files[0];//获取读取的File对象
    var name = selectedFile.name;//读取选中文件的文件名
    var size = selectedFile.size;//读取选中文件的大小
    console.log("文件名:"+name+"大小："+size);
    var reg = /\.(CSV|csv)/i;
    if(!reg.test(name)){
        alert("文件格式同规定不符！请上传.csv文件！");
        return;
    }
    $("#feedback").html("<h2><strong>"+name+"</strong></h2>");


    var reader = new FileReader();//这里是核心！！！读取操作就是由它完成的。
    try
    {
        reader.readAsText(selectedFile);//读取文件的内容
    }
    catch(err)
    {
        alert("读取文件时发生错误");
    }

    reader.onload = function(){
        var array = this.result.trim().split('\n');
        var error = 0;
        var arr = new Array();
        for(var i = 0; i < array.length; i++)
        {
            var flag = 0;
            var list = array[i].split(',');
            if(list) {
                list = JSON.stringify(list);
                arr.push(list);
            }
        }
        var length = arr.length;
        arr = JSON.stringify(arr);
        alert(arr);
        post = {
            len: length,
            data: arr
        }
        $.get(
            "readfile.php",
            post,
            function(data){
                if(data == "INERROR" || data == "UPERROR"){
                    alert("文件中出现错误！");
                    $("#feedback").html("<h2><strong>文件内部错误</strong></h2>");

                }
            }
        )
    };
   
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