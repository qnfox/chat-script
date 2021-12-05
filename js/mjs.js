function controlfontsize()
{
    console.log("rsr");
    if (window.screen.width > 800)
    {
        $("*").css("font-size","large");   
    }
}
//controlfontsize();
//Path name for script [/scriptname]
function get_script_path()
{
    var scriptname = document.URL.split("/")[3];
     return scriptname;   
}
var actions_path = "/"+get_script_path()+"/tmp/actions.php";
//Get Massages
function getusermsgs(mfrom)
{  
     $.get(actions_path+"?msg="+mfrom,function(data)
    {
        $(".chat").html(data)
    });
}
//Refresh Massages
function refreshmassage(mfrom,old='')
{  
     document.getElementById("sendmsgresult").innerText = "جاري جلب الرسائل ...";
     $(".chatingarea").load(actions_path+"?msg="+mfrom+old+" .chatingarea",function()
    {
        document.getElementById("sendmsgresult").innerText = "";
    });
}
//Send Massage
function sendmsg(mto)
{
    var msg = $(".chat #msgto").val(); 
    var lenth = msg.length;
    console.log(length);
    $(".chat #sendmsgresult").text("جاري الارسال ..."); 
    $.post(actions_path,{"sendmsg":msg,"mto":mto},function(data)
    {
        $(".chat #sendmsgresult").html(data);
        $(".chat #msgto").val("");
        $(".chat #sendmsgresult").html("");
        refreshmassage(mto);
        
    });
}
//Delete Massage Or Massage
function dellmsg(mto,id='0')
{
    console.log(mto,id)
    document.getElementById("sendmsgresult").innerText = "حاري حذف الرسالة";
    $.post(actions_path,{"dellmsg":id,"mto":mto},function(data)
    {
        document.getElementById("sendmsgresult").innerText = data;
        refreshmassage(mto)
    });
}
//Edit User Profile
function edituser(key)
{
    var value = $(".profile #newvlaue").val();
    $(".profile #editresult").text("جاري التعديل ...");
    $.get(actions_path+"?edit=1&key="+key+"&value="+value,function(data)
    {
        $(".profile #editresult").html(data);
        if (data == "تم التعديل بنجاح")
        {
            if (key != "password")
            {
                $(".profile #"+key).text(value);  
            }   
        }
    });
}
