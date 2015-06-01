<?php
$user ='root';
$pass='';
$db='340final';


$mysqli=new mysqli('localhost',$user,$pass,$db) or die("Unable to connect");

echo"Hi";

?>