<?php 

$servername="localhost";
$username="root";
$password="";
$database="carix";

$connect= mysqli_connect($servername , $username , $password , $database);

session_start();
// if($_SESSION['user'])
// {
//     session_unset();
//     session_destroy();
// }
// if($connect){
//     echo "Done";
// }

?>
