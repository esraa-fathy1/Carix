<?php

include '../connection.php';

if(!isset($_SESSION['admin'])){
    header("location:Admin-Login.php");
}

if($_SESSION['role'] == 'Customer_admin'){
    header("location:dashboard.php");
}

//logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location:Admin-Login.php");
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


$mode = 'add';

//get data to update 
if(isset($_GET['update'])){
    $mode = 'update';
    $service_id = $_GET['update'];
    $select_service = "SELECT * FROM `services` 
    WHERE `s_id` = $service_id";
    $run_select_service = mysqli_query($connect , $select_service);
    $array = mysqli_fetch_array($run_select_service);
}

//select enum 
$select_enum = "SHOW COLUMNS FROM `services` LIKE 'type'";
$run_select_enum = mysqli_query($connect , $select_enum);
$row = mysqli_fetch_assoc($run_select_enum);
$row = $row['Type'];
$row = str_replace("enum('", "", $row);
$row = str_replace("')", "", $row);
$row = explode("','", $row);


//update service
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    if(!is_numeric($price)){
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong>price must be a valid number!</strong><br>
    </div> ";
    }
    else{
        $update = "UPDATE `services`
        SET `s_name` = '$name' , 
        `s_price` = $price , 
        `description` = '$description' ,
        `type` = '$type'
        WHERE `s_id` = $service_id";
        $run_update = mysqli_query($connect , $update);
        header("location:viewservice.php");
    }


}


//add service
if(isset($_POST['add'])){
    $name=$_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $type= $_POST['type'];
    if(!is_numeric($price)){
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong>quantity must be a valid number!</strong><br>
    </div> ";
    }
    else{
        $insert = "INSERT INTO `services` VALUES(NULL ,  '$name' , $price , '$description' , '$type')";
        $run_insert = mysqli_query($connect , $insert);
        header("location:viewservice.php");
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
    <link rel="stylesheet" href="./css/Add-service.css" />
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

    <!--*-------------------------------------------- Add Service ---------------------------------------------->
    <section class="mainBg py-3">
        <div class="container forms">
            <div class="wrapper">
                <div class="inner">
                    <div class="image-holder">
                        <img src="../images/Forms/orders.svg" alt="">
                    </div>
                    <form method="POST" class="pt-0">
                        <?php if($mode == 'add'){?>
                        <h3>Add Service</h3> 
                        <?php } else { ?>
                        <h3>Update Service</h3> 
                        <?php } ?>
                        <div class="form-holder">
                            <input type="text" placeholder="Service Name" class="form-control" style="font-size: 15px;"  value="<?php if($mode == 'update'){ echo $array['s_name'] ;} ?>" name="name" required>
                        </div>
                        <div class="form-holder">
                            <input type="text" placeholder="Service Price" class="form-control"
                                style="font-size: 15px;" value="<?php if($mode == 'update'){ echo $array['s_price'] ;} ?>" name="price" required>
                        </div>
                        <div class="form-holder">
                            <input type="text" placeholder="Description" class="form-control" style="font-size: 15px;" value="<?php if($mode == 'update'){ echo $array['description'] ;} ?>" name="description"required>
                        </div>
                        <div class="form-holder">
                            <label for="Type">Service Type: </label>
                            <select id="type" name="type" class="ms-2" required>
                                <?php foreach($row as $enum){ ?>
                                <option value="<?php echo $enum ; ?>" 
                                <?php if($mode == 'update' && $enum == $array['type']) { ?> selected <?php } ?>
                                ><?php echo $enum ; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-login">
                        <?php if($mode == 'add'){?>
                            <button type="submit" class="up" name="add">Add</button>
                            <?php }else{ ?>
                            <button type="submit" class="up" name="update">Update</button>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>