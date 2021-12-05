<?php
/**
 *  PHP Functions
 */

//Login Cheak
function login_cheak($name,$password)//cheaked
{
    $alowed_letters = "/^[a-zA-Z0-9]*$/";
    if(!preg_match("$alowed_letters",$name))
    {
        return "الاسم غير صالح";
    }
    $password = md5($password);
    global $dbhandler;
    $query  = "select * from `users` where `name`='$name' and `password`='$password'";
    $result = @mysqli_query($dbhandler,$query);
    $num_rows = mysqli_num_rows($result);
    if($num_rows < 1)
    {
        return 0;
    }
    $userinfo = mysqli_fetch_assoc($result);       
    return $userinfo;
}
//Add User Function
function adduser($name,$fname,$password,$email)//cheaked
{
    global $dbhandler;
    $alowed_letters = "/^[a-zA-Z0-9دجحخهعغفقثصضطكمنتالبيسشظزوةىلارؤءئألأ}ْ ]*$/";
    $email_alow_letters = "/^[a-zA-Z0-9@_.]*$/";
    if(strlen(str_ireplace(" ","",$name)) < 3)
    {
        return "الاسم قصير جدا";
    }
    if(strlen(str_ireplace(" ","",$fname)) < 3)
    {
        return "الاسم قصير جدا";
    }
    if(strlen(str_ireplace(" ","",$password)) < 6)
    {
        return "الباسورد قصير جدا";
    }
    if(!preg_match("$alowed_letters",$name))
    {
        return "الاسم غير صالح";
    }
    if(!preg_match("$alowed_letters",$fname))
    {
        return "الاسم غير صالح";
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
        return "الايميل غير صالح";
    }
    $password = md5($password);
    $query = "select * from `users` where `name`='$name'";
    $result = mysqli_query($dbhandler,$query);
    $num_rows = mysqli_num_rows($result);
    if($num_rows > 0)
    {
        return "هذا الاسم محجوز";
    }
    $query = "insert into `users` (`name`,`fname`,`password`,`email`) values ('$name','$fname','$password','$email')";
    $result = @mysqli_query($dbhandler,$query);
    @mysqli_close($dbhandler);
    return $result;
}
//Get User Details For Profile
function getuser($name)//cheaked
{
    $alowed_letters = "/^[a-zA-Z0-9]*$/";
    if(!preg_match("$alowed_letters",$name))
    {
        return "الاسم غير صالح";
    }
    global $dbhandler;
    $query = "select * from `users` where `name`='$name'";
    $result = mysqli_query($dbhandler,$query);
    $num_rows = mysqli_num_rows($result);
    $user = null;
    if($num_rows > 0)
    {
        $user = mysqli_fetch_assoc($result);
    }
    return $user;
}
function edituser($key,$value,$name)//cheked
{
  
    //الفلترة
    if ($key == "name")//name
    {
        if(strlen(str_ireplace(" ","",$value)) < 3)
        {
            return "الاسم قصير جدا";
        }
        $alowed_letters = "/^[a-zA-Z0-9]*$/";
    }
    elseif($key == "fname")//fullname
    {
        if(strlen($value) < 3)
        {
            return "الاسم قصير جدا";
        }
        $alowed_letters = "/^[a-zA-Z0-9دجحخهعغفقثصضطكمنتالبيسشظزوةىلارؤءئألأ}ْ ]*$/";
    }
    elseif($key == "password")//password
    {
        if(strlen($value) < 6)
        {
            return "الباسورد قصير جدا";
        }
        $value = md5($value);
    }
    elseif($key == "email")//email
    {
        if(!filter_var($value,FILTER_VALIDATE_EMAIL))
        {
            return "الايميل غير صالح";
        }
    }
    else
    {
        return "مدخلات خاطئة";//any thing else
    }
    //المقارنة مع كل الحالات ماعدا الايميل
    if(($key != "email") && ($key != "password"))
    {
        if(!preg_match("$alowed_letters",$value))
        {
            return "مدخلات خاطئة";
        }
    }

    //استدعاء الاتصال بقاعدة البيانات
    global $dbhandler;
    //في حالة الاسم
    if ($key == "name")
    {
        $query  = "select * from `users` where `name`='$value'";
        $result = @mysqli_query($dbhandler,$query);
        //التحقق من الاسم الجديد غير موجود
        $num_rows = @mysqli_num_rows($result);
        if($num_rows > 0)
        {
            return "الاسم محجوز مسبقا";
        }
        $query = "update `users` set `$key`='$value' where `name`='$name'";
        $result = @mysqli_query($dbhandler,$query);
        if($result === TRUE)
        {
            $query = "update `msgs` set `mfrom`='$value' where `mfrom`='$name'";
            $result = mysqli_query($dbhandler,$query);
            $query = "update `msgs` set `mto`='$value' where `mto`='$name'";
            $result = mysqli_query($dbhandler,$query);
            return $result;
        }
    }
    //الحالات الاخرى
    $query = "update `users` set `$key`='$value' where `name`='$name'";
    $result = @mysqli_query($dbhandler,$query);

    return $result;
}
//Delet Msassage Or Massages
function dellmsg($mfrom,$queryplus)
{
    global $dbhandler;
    $alowed_letters = "/^[a-zA-Z0-9]*$/";
    if(!preg_match("$alowed_letters",$mfrom))
    {
        return "الاسم غير صالح";
    }
    $query = "delete from `msgs` where `mfrom`='$mfrom'$queryplus";
    $result = mysqli_query($dbhandler,$query);
    return $result;
}
//Get User Msgs Function
function getmsgs($mto,$queryplus="",$isread="0")//cheaked
{
    $alowed_letters = "/^[a-zA-Z0-9]*$/";
    if(!preg_match("$alowed_letters",$mto))
    {
        return "الاسم غير صالح";
    }
    global $dbhandler;
    $query = "select * from `msgs` where `mto`='$mto' $queryplus";
    $result = @mysqli_query($dbhandler,$query);
    $query = "UPDATE `msgs` set `isread`='$isread' WHERE `mto`='$mto' and `isread`='0'";
    $res = mysqli_query($dbhandler,$query);
    $msgs = array();
    while($row = mysqli_fetch_assoc($result))
    {
        $msgs[] = $row;
    }
    return $msgs;

}
//Send Massage Function
function sendmsg($mfrom,$mto,$msg)//cheaked
{
    //فلترة الدخلات 
    $alowed_letters = "/^[a-zA-Z0-9]*$/";
    if(strlen($msg) > 524){ return "طول الرسالة اكثر من الحد الازم"; }
    $msg = filter_var($msg,FILTER_SANITIZE_STRING);
    if(!preg_match("$alowed_letters",$mfrom))
    {
        return "الاسم غير صالح";
    }
    $alowed_letters = "/^[a-zA-Z0-9]*$/";
    if(!preg_match("$alowed_letters",$mto))
    {
        return "الاسم غير صالح";
    }
    global $dbhandler;
    //التحقق م ان المستخدم المرسل اليه موجود
    $query  = "select * from `users` where `name`='$mto'";
    $result = @mysqli_query($dbhandler,$query);
    $num_rows = @mysqli_num_rows($result);
    if($num_rows < 1)
    {
        return "هذا المستخدم غير موجود";
    }
    $db_mto = mysqli_fetch_assoc($result);
    if($mfrom == $db_mto["name"])
    {
        return "لايمكنك ارسال رسالة لنفسك";
    }
    $query = "select * from `msgs` where `mfrom`='$mfrom' and `mto`='$mto'";
    $result = @mysqli_query($dbhandler,$query);
    $isfirst = @mysqli_num_rows($result);
    if($isfirst == 0)
    {
        $isfirst = '1';
    }
    else
    {
        $isfirst = '0';
    }
    $query = "insert into `msgs` (`mfrom`,`mto`,`msg`,`isfirst`,`isread`) values ('$mfrom','$mto','$msg','$isfirst','0')";
    $result = @mysqli_query($dbhandler,$query);
    return $result;
}
