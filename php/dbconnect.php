<?php 
$_SERVER = "localhost";
$username = "root";
$password = "";
$database = "my_system";

$con = mysqli_connect($_SERVER,$username,$password,$database);

if ($con){
    
}else{
    die("error". mysqli_connect_error());
}

?>