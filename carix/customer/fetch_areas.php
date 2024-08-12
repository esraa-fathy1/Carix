<?php
include "../connection.php";

$id=$_POST['id'];
$sql="SELECT * FROM `stations_area` WHERE `station_id`=$id";
$result=mysqli_query($connect,$sql);

$out='';
while($row=mysqli_fetch_assoc($result)){
    $area_id=$row['area_station_id'];
    $select="SELECT * FROM `stations_locations` WHERE `area_station_id`=$area_id";
    $run=mysqli_query($connect,$select);

    $out.='<option value="'.$row['area_station_id'].'">'.$row['area_station_name'].'</option>';

}
echo $out;

?>