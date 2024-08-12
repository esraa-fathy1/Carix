<?php
include "../connection.php";

$id=$_POST['id'];
$sql="SELECT * FROM `models` WHERE `brand_id`=$id";
$result=mysqli_query($connect,$sql);

$out='';
while($row=mysqli_fetch_assoc($result)){
    $out.='<option value="'.$row['id'].'">'.$row['name'].'</option>';

}
echo $out;
?>