<?php
include "../connection.php";

$id=$_POST['id'];
$sql="SELECT * FROM `stations_locations` WHERE `area_station_id`=$id";
$result=mysqli_query($connect,$sql);

$out2='';
while($data=mysqli_fetch_assoc($result)){
        $out2.='<li>'.$data['location_address'].':
        <a href="'.$data['location_link'].'"'.'target="_blank"
            rel="noopener noreferrer">'.$data['location_link'].'</a>
    </li>';
    

}

echo $out2;
?>