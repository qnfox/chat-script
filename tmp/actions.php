<?php
session_start();
if(!isset($_SESSION["name"]))
{
    header("location:home");
}
include "../inc/db.php";
include "../inc/functions.php";
$dbhandler = db_connect();
//Get Massages
if(isset($_GET["msg"]))
{
    $alowed_letters = "/^[a-zA-Z0-9]*$/";
    $html_code  = array();
    $mfrom      = $_GET["msg"];
    $mto        = $_SESSION["name"];
    $msg_count  = 4;
    if(!preg_match("$alowed_letters",$mfrom))
    {
        exit("مدخلات خاطئة");
    }
    //عرض الرسائل بحسب طلب المستخدم الاحدث والاقدم
    $queryplus = "and `mfrom`='$mfrom' or `mfrom`='$mto' and `mto`='$mfrom'";
    $msgs = getmsgs($mto,$queryplus,"1");//set isread = 1 in getmsgs function
    $start = count($msgs)-$msg_count;
    if($start < 0)
    {
        $start = 0;
    }
    //جلب الرسائل الاقدم
    if(isset($_GET["old"]))
    {
        $_GET["old"] = (int)$_GET["old"];
        if($_GET["old"] < $msg_count)
        {
            $start = 0;
        }
        else
        {
            $start = $_GET["old"]-$msg_count;
        }
        
    }
    //جلب الرسائل الاحدث
    if(isset($_GET["new"]))
    {
        $_GET["new"] = (int)$_GET["new"];
        $start = $_GET["new"]+$msg_count;
        if($start >= count($msgs)-$msg_count)
        {
            $start = count($msgs)-$msg_count;
        }
    }
    $html_code[] = "<div class='chatingarea'>";

    if($start > 0)
    {
        $html_code[] = "<p id='getusermsgs_new_old' style='text-align:center;'><button onclick='refreshmassage(\"$mfrom\",\"&old=$start\")'>الرسائل الاقدم</button></p>";
    }
    if($start < count($msgs)-$msg_count)
    {
        $html_code[] = "<p id='getusermsgs_new_old' style='text-align:center;'><button onclick='refreshmassage(\"$mfrom\",\"&new=$start\")'>الرسائل الاحدث</button></p>";
    }
    
    //جلب الرسائل للمستخدم
    $queryplus = "and `mfrom`='$mfrom' or `mfrom`='$mto' and `mto`='$mfrom' limit $start,$msg_count";
    $msgs = getmsgs($mto,$queryplus,"1");//set isread = 1 in getmsgs function
    $html_code[] = "<button onclick='refreshmassage(\"$mfrom\")'>تحديث</button>";
    $html_code[] = "<button onclick='dellmsg(\"$mfrom\")'>حذف المحادثة</button>";
    for ($i=0; $i < count($msgs); $i++) 
    {
        $msg = $msgs[$i]["msg"];
        $mfrom = $msgs[$i]["mfrom"];
        $mto   =$msgs[$i]["mto"];
        $id = $msgs[$i]["id"];
        if($mfrom == $_SESSION["name"])
        {
            $readstatus = "";
            $dellmsg    = "<a id='dellmsga' onclick='dellmsg(\"$mto\",\"$id\")' href='#'>حذف</a>";
            if($msgs[$i]["isread"] == "1"){$readstatus = "<br><strong>تمت المشاهدة</strong>";}
            $html_code[] = "<p id='usermsgs' style='margin-left:auto;'>$msg $readstatus $dellmsg</p>";
        }
        else
        {
            
            $html_code[] = "<p id='usermsgs' style='margin-right:auto;'>$msg</p>"; 
        }
    }
    $html_code[] = "</div>";
    $mfrom = $_GET["msg"];
    $html_code[] = "<div style='text-align:center;'>
    <textarea maxlength='289' id='msgto'></textarea><br><button id='mto' onclick='sendmsg(\"$mfrom\")'>ارسال</button>
    </div>
    <div style='font-size: 30px;color: chartreuse;text-align: center;margin:auto' id='sendmsgresult'></div>";
    //print_r($html_code);
    foreach($html_code as $value)
    {
        echo $value;
    }

}
//Send Massage
if(isset($_POST["sendmsg"]))
{
    $mto   = $_POST["mto"];
    $mfrom = $_SESSION["name"];
    $msg   = $_POST["sendmsg"];
    $sendmsg = sendmsg($mfrom,$mto,$msg);
    if($sendmsg === true)
    {
        echo "<p style='font-size: 17px;color: chartreuse;text-align: center;margin:auto'>تم الارسال بنجاح</p>";
    }
    elseif($sendmsg === false)
    {
        echo "<p style='font-size: 17px;color: red;text-align: center;margin:auto>فشل الارسال</p>";
    }
    else
    {
        echo $sendmsg;
    }
}
//Delete Massage Or Massages
if(isset($_POST["dellmsg"]))
{
    $mfrom = $_SESSION["name"];
    $mto   = $_POST["mto"];
    $alowed_letters = "/^[a-zA-Z0-9]*$/";
    if(!preg_match("$alowed_letters",$mto))
    {
        exit("الاسم غير صالح");
    }
    $id    = (int)$_POST["dellmsg"];
    if($id > 0){$queryplus = "and `id`='$id'";}
    else{$queryplus = "and `mto`='$mto' or `mfrom`='$mto' and `mto`='$mfrom'";}
    $result = dellmsg($mfrom,$queryplus);
    if($result === TRUE)
    {
        echo "تم حذف";
    }
    elseif($result === FALSE)
    {
        echo "فشل حذف";
    }
    else
    {
        echo $result;
    }
}
//Edit User Profile
if(isset($_GET["edit"]))
{
    $name  = $_SESSION["name"];
    $key   = $_GET["key"];
    $value = $_GET["value"];
    $editprofile = edituser($key,$value,$name);
    if ($editprofile === TRUE)
    {
        echo "تم التعديل بنجاح";
        if ($key == "name")
        {
            $_SESSION["name"] = $value;
        }
        
    }
    elseif($editprofile === FALSE)
    {
        echo "فشل التعديل";
    }
    else
    {
        echo $editprofile;
    }
}
@mysqli_close($dbhandler);