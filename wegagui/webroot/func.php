<?php
function dbval($p_name,$ns)
{
   include "../config/".$ns.".conf.php";
   $tb="config";
   $link=mysqli_connect("$dbhost", "$login", "$password", "$my_db");
   $sqlstr="select value from $tb where parameter = '$p_name' limit 1";
   $result=mysqli_query($link, $sqlstr);
   $value = mysqli_fetch_object($result)->value;
   mysqli_close($link);
   return $value;
}

function dbcomment($p_name,$ns)
{
   include "../config/".$ns.".conf.php";
   $tb="config";
   $link=mysqli_connect("$dbhost", "$login", "$password", "$my_db");
   $sqlstr="select comment from $tb where parameter = '$p_name' limit 1";
   $result=mysqli_query($link, $sqlstr);
   $comment = mysqli_fetch_object($result)->comment;
   mysqli_close($link);
   return $comment;
}

function setdbval($ns,$p_name,$p_value,$p_comment)
{
   include "../config/".$ns.".conf.php";
   $link=mysqli_connect("$dbhost", "$login", "$password", "$my_db");

   $tb="config";
   mysqli_query($link, "create table IF NOT EXISTS $tb (parameter varchar(50) PRIMARY KEY) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
   mysqli_query($link, "alter table $tb add column value varchar(50) ");
   mysqli_query($link, "alter table $tb add column comment varchar(255) ");
   mysqli_query($link, "insert into $tb (parameter, value, comment) values ( '$p_name', '$p_value', '$p_comment' )" );
   mysqli_query($link, "update $tb set value = '$p_value', comment = '$p_comment'  where parameter ='$p_name' ");
   mysqli_close($link);
}

function sensval($p_name,$ns)
{
   include "../config/".$ns.".conf.php";
   $tb="sens";
   $link=mysqli_connect("$dbhost", "$login", "$password", "$my_db");
   $sqlstr="select An from sens limit 1";
   $result=mysqli_query($link, $sqlstr);
   $value = mysqli_fetch_object($result)->value;
   mysqli_close($link);
   return $value;
}

?>