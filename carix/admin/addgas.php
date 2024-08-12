<?php

include "../connection.php";
if(isset($_SESSION['admin'])){
    $admin_id = $_SESSION['admin'];
    // echo $user_id;
}

if(!isset($_SESSION['admin'])){
    header("location:Admin-Login.php");
}
if($_SESSION['role'] == 'Customer_admin'){
    header("location:dashboard.php");
}


//navbar
$select_website="SELECT * FROM `website`";
$run_select_website=mysqli_query($connect,$select_website);
$fetch_web=mysqli_fetch_assoc($run_select_website);
$web_name=$fetch_web['name'];
$web_logo=$fetch_web['logo'];
$web_address=$fetch_web['address'];
$web_phone=$fetch_web['phone'];
$web_wh=$fetch_web['working_hours'];
$web_hotline=$fetch_web['hotline'];
$web_mail=$fetch_web['mail'];

$alert="";

//select city
$select_city="SELECT * FROM `stations_city`";
$run_city=mysqli_query($connect,$select_city);

// //select area
// $select_area="SELECT * FROM `stations_area`";
// $run_area=mysqli_query($connect,$select_area);

//add
$alert="";
if(isset($_POST['add'])){
    $area=$_POST['area'];
    $location=$_POST['location'];
    $link=$_POST['link'];
    $city=$_POST['city'];
    if(!empty($city)){
        if(!empty($area)){
            $insert="INSERT INTO `stations_locations` VALUES(NULL,'$location','$link',$area)";
            $run_insert=mysqli_query($connect,$insert);
        }
        else{
            $alert="Area is required";
        }
    }
    else{
        $alert="city is required";
    }
  
}

//update
$update=false;
$fetch_city="";
$fetch_area="";
$fetch_location="";
$fetch_link="";
if(isset($_GET['edit'])){
    $update=true;
    $id=$_GET['edit'];
    $select="SELECT * FROM `stations_locations`
    JOIN `stations_area` ON `stations_locations`.`area_station_id` = `stations_area`.`area_station_id`
    JOIN `stations_city` ON `stations_area`.`station_id` = `stations_city`.`station_id`";
    $run=mysqli_query($connect,$select);
    $fetch=mysqli_fetch_assoc($run);
    $fetch_city=$fetch['station_city'];
    $fetch_area=$fetch['area_station_name'];
    $fetch_area_id=$fetch['area_station_id'];
    $fetch_location=$fetch['location_address'];
    $fetch_link=$fetch['location_link'];
}
if(isset($_POST['update'])){
    $area=$_POST['area'];
    $location=$_POST['location'];
    $link=$_POST['link'];

    if(empty($area))
    {
        $area=$fetch_area_id;
        $update="UPDATE `stations_locations` SET `stations_locations`.`area_station_id`=$area ,
        `location_address`='$location' , `location_link`='$link' WHERE `location_station_id`=$id";
        $run_update=mysqli_query($connect,$update);
        header("location:viewgas.php");
    }
    else
    {
        $update="UPDATE `stations_locations` SET `stations_locations`.`area_station_id`=$area ,
        `location_address`='$location' , `location_link`='$link' WHERE `location_station_id`=$id";
        $run_update=mysqli_query($connect,$update);
        header("location:viewgas.php");
    }

 
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="content-language" content="en" />
    <meta name="keywords" content="website, blog, foo, bar" />
    <meta name="author" content="" />
    <meta name="publisher" content="" />
    <meta name="copyright" content="" />
    <meta name="description" content="" />
    <meta name="page-topic" content="" />
    <meta name="page-type" content="" />
    <meta name="audience" content="" />
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <link rel="icon" href="../images/Home/section-1-img (1).png" />
    <link rel="stylesheet" href="./css/normalize.css" />
    <link rel="stylesheet" href="./css/all.min.css" />
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/view.css">
    <link rel="stylesheet" href="./css/addgas.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <title>CariX</title>
</head>

<body>
    <!--!-------------------------------------------- Navbar ---------------------------------------------->
    <nav class="navbar navbar-expand-lg mainBg mt-3 sticky-top">
        <div class="container-fluid text-capitalize">
            <div class="navImage">
            <?php if(isset($_SESSION['admin'])){ ?>
            <a href="./dashboard.php"><img src="../images/<?php echo $web_logo ?>" class="img-fluid" alt="Logo"></a>
            <?php }else{ ?>
            <a href="./Admin_Login.php"><img src="../images/<?php echo $web_logo ?>" class="img-fluid" alt="Logo"></a>
            <?php } ?>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse centerNav" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Services</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="./Add-service.php">Add Services</a></li>
                            <li><a class="dropdown-item" href="./viewservice.php">View Services</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Accessories</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="./Add-product.php">Add Accessories</a></li>
                            <li><a class="dropdown-item" href="./viewproduct.php">View Accessories</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Orders</a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="./step2.php">Add Orders</a></li>
                            <li><a class="dropdown-item" href="./vieworder.php">View Orders</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Admins</a>
                        <ul class="dropdown-menu">
                            <!-- <li><a class="dropdown-item" href="./Admin-Login.html">Admin Login</a></li> -->
                            <li><a class="dropdown-item" href="./Add-admin.php">Add Admins</a></li>
                            <li><a class="dropdown-item" href="./viewadmin.php">View Admins</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Users</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="./Edit-User.php">edit Users</a></li>
                            <li><a class="dropdown-item" href="./viewuser.php">View Users</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Vehicles</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="./viewcars.php">View Vehicles</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="./Add-Brand.php">Add Car Brand</a></li>
                            <li><a class="dropdown-item" href="./viewbrand.php">View Car Brand</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="./Add-Model.php">Add Car Model</a></li>
                            <li><a class="dropdown-item" href="./viewmodel.php">View Car Model</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Appointments</a>
                        <ul class="dropdown-menu">
                            <!-- <li><a class="dropdown-item" href="#">Add Appointment</a></li> -->
                            <li><a class="dropdown-item" href="./viewappointment.php">View Appointments</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Reviews</a>
                        <ul class="dropdown-menu">
                            <!-- <li><a class="dropdown-item" href="#">Add Review</a></li> -->
                            <li><a class="dropdown-item" href="./viewreviews.php">View Reviews</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Wallets</a>
                        <ul class="dropdown-menu">
                            <!-- <li><a class="dropdown-item" href="#">Add Wallet</a></li> -->
                            <li><a class="dropdown-item" href="./viewwallet.php">View Wallets</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Areas</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="./Add-Area.php">Add Area</a></li>
                            <li><a class="dropdown-item" href="./viewarea.php">View Areas</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                    <li class="nav-item">
                        <a class="nav-link" href="./Website.php">Edit Website</a>
                    </li>
                </ul>
                <!-- <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Gas
                                Station</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Add Gas Station</a></li>
                                <li><a class="dropdown-item" href="#">View Gas Station</a></li>
                            </ul>
                        </li>
                    </ul> -->
                    <?php if(isset($_SESSION['admin'])){ ?>
                <div class="d-flex align-items-center gap-3">
                    <a class="nav-link rightUser px-3" href="./Admin-Login.php?logout"><i class="fa-solid fa-user"></i>&nbsp;Logout</a>
                </div>
                <?php } ?>
            </div>
        </div>
    </nav>

        <!--*-------------------------------------------- Add Gas Station ---------------------------------------------->
        
        <section class="mainBg py-3">
        <?php if($alert){ ?>
                        <div class="alert alert-danger text-center w-100 mt-3 m-auto">
                            <strong><?php echo $alert; ?></strong>
                        </div>
                        <?php } ?>
            <div class="container forms">
                <div class="wrapper">
                    <div class="inner">
                        <div class="image-holder">
                            <img src="../images/Forms/reg-img.jpg" alt="">
                        </div>
                        <form action="" method="POST" class="pt-0">
                            <?php if(!$update){ ?>
                            <h3>Add Gas Station</h3>
                            <?php }else{ ?> 
                            <h3>Update Gas Station</h3>
                            <?php } ?>
                            <div class="form-holder">
                                <label for="city">City:</label>
                                    <select  id="city" name="city" >
                                        <option value="">Select City</option>
                                        <?php foreach($run_city as $row){ ?>
                                        <option value="<?php echo $row['station_id'] ?>"><?php echo $row['station_city'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php if($update){ ?>
                                    <input type="text" value="<?php echo $fetch_city ?>" disabled>
                                    <?php } ?>
                            </div>
                            <div class="form-holder">
                                <label for="area">Area:</label>
                                    <select id="area" name="area" >
                                        <option value="">Select Area</option>
                                    </select>
                                    <?php if($update){ ?>
                                    <input type="text" value="<?php echo $fetch_area ?>" disabled>
                                    <?php } ?>
                            </div>
                            <div class="form-holder">
                                <input value="<?php echo $fetch_location ?>" name="location" type="text" placeholder="Location" class="form-control" style="font-size: 15px;" required>
                            </div>
                            <div class="form-holder">
                                <input value="<?php echo $fetch_link ?>" name="link" type="text" placeholder="Link" class="form-control" style="font-size: 15px;" required>
                            </div>
                            <!-- <div class="form-holder">
                                <input type="text" placeholder="Area" class="form-control" style="font-size: 15px;">
                            </div> -->
                            <div class="form-login">
                                <?php if(!$update){ ?>
                                <button name="add" type="submit" class="up">Add</button>
                                <?php  }else{ ?>
                                <button name="update" type="submit" class="up">Update</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script>
              $(document).ready(function(){
            $('#city').change(function(){
                var stdid=$('#city').val();
                // alert(stdid);

                $.ajax({
                    type: 'POST',
                    url: 'fetch_area_admin.php',
                    data: {id:stdid},
                    success:function(data){
                        $('#area').html(data);
                    }
                });

            });
        });

        </script>
</body>
</html>