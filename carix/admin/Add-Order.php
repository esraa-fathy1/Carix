<?php
include '../connection.php';
if(!isset($_SESSION['admin'])){
    header("location:Admin-Login.php");
}

if($_SESSION['role']=='System_admin'){
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


if (isset($_GET['update'])) {
    $details_id = $_GET['update'];
    $select_details = "SELECT * FROM `order_details`
        LEFT JOIN `orders`
        ON `order_details`.`order_id` = `orders`.`o_id`
        LEFT JOIN `payments`
        ON `payments`.`order_id` = `orders`.`o_id`
        LEFT JOIN `services`
        ON `order_details`.`service_id` = `services`.`s_id`
        LEFT JOIN `products`
        ON `order_details`.`product_id` = `products`.`p_id`
        LEFT JOIN `user_address`     
        ON `orders`.`address_id` = `user_address`.`add_id`
        WHERE `order_details`.`details_id` = $details_id";
    $run_select_details = mysqli_query($connect, $select_details);
    $array = mysqli_fetch_array($run_select_details);
    $user_id = $array['user_id'];
    $order_id = $array['o_id'];
    $payment_id = $array['id'];

    //select address
    $select_add = "SELECT * FROM `user_address`
    JOIN `areas` ON `user_address`.`area_id`=`areas`.`id`
    WHERE `user_address`.`user_id`= $user_id";
    $run_select_address = mysqli_query($connect, $select_add);
}

//select services and products
$select_service = "SELECT * FROM `services`";
$run_select_service = mysqli_query($connect, $select_service);
$select_product = "SELECT * FROM `products`";
$run_select_product = mysqli_query($connect, $select_product);

if (isset($_POST['update'])) {
    if (is_numeric($_POST['product']) &&  is_numeric($_POST['service'])) {
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong>You can't choose both service and product!</strong><br>Please Edit just one.
    </div> ";
    }
    else if (!is_numeric($_POST['product']) &&  !is_numeric($_POST['service'])) {
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong>You didnt choose service or product!</strong><br>Please choose one service or one product.
    </div> ";
    }
    else{
    $product_id = $_POST['product'];
    $service_id = $_POST['service'];
    // $total_price = $_POST['price'];
    $address_id = $_POST['address'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service_type = $_POST['type'];
    $paid = $_POST['paid'];
    
    //select old price
    if(isset($array['s_price'])){
        $old_price = $array['s_price'];
    }
    elseif(isset($array['p_price'])){
        $old_price = $array['p_price'];
    }

    //select new price
    if(is_numeric($product_id)){
        $select_new_product = "SELECT * FROM `products`
        WHERE `p_id` = $product_id ";
        $run_new_product = mysqli_query($connect , $select_new_product);
        $product_array = mysqli_fetch_array($run_new_product);
        $new_price =$product_array['p_price'];
    }
    else if(is_numeric($service_id)){
        $select_new_service = "SELECT * FROM `services`
        WHERE `s_id` = $service_id";
        $run_new_service = mysqli_query($connect , $select_new_service);
        $service_array = mysqli_fetch_array($run_new_service);
        $new_price =$service_array['s_price'];
    }


    //total price
    $total_price = $array['total_price'] - $old_price  + $new_price;


    //update_details
    if(is_numeric($service_id)){
    $update_details = "UPDATE `order_details`
    SET `service_id` = $service_id 
    WHERE `details_id` = $details_id
    ";
    }
    else if(is_numeric($product_id)){
        $update_details = "UPDATE `order_details`
        SET `product_id` = $product_id 
        WHERE `details_id` = $details_id
        ";
    }
    $run_update_details = mysqli_query($connect , $update_details);


    //update order
    $update_order = "UPDATE `orders`
    SET `address_id` = $address_id , 
    `date` = '$date' , 
    `time` = '$time' , 
    `service_type` = '$service_type',
    `total_price` = $total_price 
    WHERE `o_id` = $order_id";
    $run_update_order = mysqli_query($connect , $update_order); 

    //update payment
    $update_payment = "UPDATE `payments`
    SET `paid` = $paid
    WHERE `id` = $payment_id ";
    $run_update_payment = mysqli_query($connect , $update_payment);

    header("location:order_details.php?details=".$order_id);
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
    <link rel="stylesheet" href="./css/Add-Order.css">
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

    <!--*-------------------------------------------- Add Order ---------------------------------------------->
    <section class="mainBg py-3">
        <div class="container forms">
            <div class="wrapper">
                <div class="inner">
                    <div class="image-holder">
                        <img src="../images/Forms/orders.svg" alt="">
                    </div>
                    <form method="POST" class="pt-0">
                        <h3>Update Order</h3>
                        <div class="form-holder">
                            <!-- <input type="text" placeholder="Product" class="form-control" style="font-size: 15px;"  name="product" value="<?php echo $array['p_name']; ?>"> -->
                            <select id="savedAddress" aria-label="address" class="form-select py-2 " name="product">
                                <option value="<?php if(!is_null($array['p_id'])){
                                echo $array['p_id'];   ?> " selected> <?php echo $array['p_name']; } ?> </option>
                     
                                    <option value=""> NOTHING </option>
                          
                                <?php foreach ($run_select_product as $product) { ?>
                                    <option value="<?php echo $product['p_id']; ?>"> <?php echo $product['p_name']; ?> </option>
                                <?php } ?>
                                <!-- <option value="Deliver to your Home"> Deliver to your Home </option> -->
                            </select>
                        </div>
                        <div class="form-holder">
                            <select id="savedAddress" aria-label="address" class="form-select py-2 " name="service">
                            <option value="<?php if(!is_null($array['s_id'])){
                                echo $array['p_id'];   ?> " selected> <?php echo $array['s_name'];  }?> </option>
                                    <option value=""> NOTHING </option>
                                    
                                <?php foreach ($run_select_service as $service) { ?>
                                    <option value="<?php echo $service['s_id']; ?>"> <?php echo $service['s_name']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-holder">
                            <select name="address" id="savedAddress" aria-label="address" class="form-select py-2 ">
                            <option value="<?php echo $array['add_id'];  ?> " selected> <?php echo $array['address']; ?> </option>
                                <?php foreach ($run_select_address as $data) { ?>
                                    <option value="<?php echo $data['add_id'] ?>"><?php echo $data['city'] . ", " . $data['name'] . ", " . $data['address'] ?></option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="form-holder">
                            <input type="date" placeholder="Date" class="form-control" style="font-size: 15px;" required name="date" value="<?php echo $array['date']; ?>">
                        </div>
                        <div class="form-holder">
                            <select id="time" aria-label="time" class="form-select " name="time" required>
                                <option value="<?php echo $array['time']; ?>" selected> <?php echo $array['time']; ?></option>
                                <option value="10:00 : 10:45"> 10:00 : 10:45</option>
                                <option value="11:00 : 11:45"> 11:00 : 11:45</option>
                                <option value="12:00 : 12:45"> 12:00 : 12:45</option>
                                <option value="13:00 : 13:45"> 13:00 : 13:45</option>
                                <option value="14:00 : 14:45"> 14:00 : 14:45</option>
                                <option value="15:00 : 15:45"> 15:00 : 15:45</option>
                                <option value="16:00 : 16:45"> 16:00 : 16:45</option>
                                <option value="17:00 : 17:45"> 17:00 : 17:45</option>
                            </select>
                        </div>
                        <div class="form-holder">
                            <select id="savedAddress" aria-label="address" class="form-select py-2 " name="type" required>
                                <option value="<?php echo $array['service_type'];  ?> " selected> <?php echo $array['service_type']; ?> </option>
                                <option value="At Carix Center"> At Carix Center </option>
                                <option value="Deliver to your Home"> Deliver to your Home </option>
                            </select>
                        </div>
                        <div class="form-holder">
                        <select id="savedAddress" aria-label="address" class="form-select py-2 " name="paid" required>
                                <option value="<?php echo $array['paid'];  ?> " selected> <?php if($array['paid'] == 0){
                                    echo 'NO' ; } else{
                                        echo 'YES';
                                    }
                                 ?> </option>
                                <option value="0"> NO </option>
                                <option value="1"> YES </option>
                            </select>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="up" name="update">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>