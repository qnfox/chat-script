<?php
/*
* Database Created By [ Mohammad abd almoneam ]
*/
function db_connect()
{
    $argument = ['localhost','root','','chat'];
    $dbhandler = @mysqli_connect($argument['0'],$argument['1'],$argument['2'],$argument['3']);
    if($dbhandler)
    {
        @mysqli_set_charset($dbhandler,"utf8");
        return $dbhandler;
    }
    else
    {
        return 0;
    }
}
?>