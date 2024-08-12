<?php

include "../connection.php";
if($_SESSION['role'] == 'System_admin'){
    header("location:dashboard.php");
}

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
else{
    header("location:dashboard.php");
}


//nav and footer
$select_website = "SELECT * FROM `website`";
$run_select_website = mysqli_query($connect, $select_website);
$fetch_web = mysqli_fetch_assoc($run_select_website);
$web_name = $fetch_web['name'];
$web_logo = $fetch_web['logo'];
$web_address = $fetch_web['address'];
$web_phone = $fetch_web['phone'];
$web_wh = $fetch_web['working_hours'];
$web_hotline = $fetch_web['hotline'];
$web_mail = $fetch_web['mail'];




//select main service from cart
$select_main = "SELECT * FROM `services`
JOIN `carts` 
ON `services`.`s_id` = `carts`.`service_id`
WHERE `services`.`type` = 'main'
AND `carts`.`user_id` = $user_id ";
$run_select_main = mysqli_query($connect, $select_main);

//select sum main 
$select_sum_main = "SELECT SUM(s_price)  as total_main FROM `services`
JOIN `carts` 
ON `services`.`s_id` = `carts`.`service_id`
WHERE `services`.`type` = 'main'
AND `carts`.`user_id` = $user_id ";
$run_sum_main = mysqli_query($connect, $select_sum_main);
$array_main = mysqli_fetch_array($run_sum_main);
$sum_main = $array_main['total_main'];


//delete main service
if (isset($_GET['delete_main'])) {
    $cart_id = $_GET['delete_main'];
    $delete_main = "DELETE FROM `carts` WHERE `id`=$cart_id";
    $run_delete_main = mysqli_query($connect, $delete_main);
    header("location:checkout.php");
}


//select additional service from cart
$select_add = "SELECT * FROM `services`
JOIN `carts` 
ON `services`.`s_id` = `carts`.`service_id`
WHERE `services`.`type` = 'additional'
AND `carts`.`user_id` = $user_id ";
$run_select_add = mysqli_query($connect, $select_add);


//select sum add 
$select_sum_add = "SELECT SUM(s_price)  as total_add FROM `services`
JOIN `carts` 
ON `services`.`s_id` = `carts`.`service_id`
WHERE `services`.`type` = 'additional'
AND `carts`.`user_id` = $user_id ";
$run_sum_add = mysqli_query($connect, $select_sum_add);
$array_add = mysqli_fetch_array($run_sum_add);
$sum_add = $array_add['total_add'];





//delete add serivice
if (isset($_GET['delete_add'])) {
    $cart_id = $_GET['delete_add'];
    $delete_add = "DELETE FROM `carts` WHERE `id`=$cart_id";
    $run_delete_add = mysqli_query($connect, $delete_add);
    header("location:checkout.php");
}


//select products
$select_product = "SELECT * FROM `products`
JOIN `carts` 
ON `products`.`p_id` = `carts`.`product_id`
WHERE `carts`.`user_id` = $user_id ";
$run_select_product = mysqli_query($connect, $select_product);



//select sum product 
$select_sum_product = "SELECT SUM(p_price)  as total_product FROM `products`
JOIN `carts` 
ON `products`.`p_id` = `carts`.`product_id`
WHERE `carts`.`user_id` = $user_id ";
$run_sum_product = mysqli_query($connect, $select_sum_product);
$array_product = mysqli_fetch_array($run_sum_product);
$sum_product = $array_product['total_product'];



//delete product
if (isset($_GET['delete_product'])) {
    $cart_id = $_GET['delete_product'];
    $delete_product = "DELETE FROM `carts` WHERE `id`=$cart_id";
    $run_delete_product = mysqli_query($connect, $delete_product);
    header("location:checkout.php");
}

$total_sum = $sum_product + $sum_add + $sum_main;
$_SESSION['total_price'] = $total_sum;


//check if checkout = zero 

if(isset($_GET['checkout'])){
    $checkout_price = $_GET['checkout'];
    if($checkout_price <= 0){
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong>You have nothing in the cart!</strong><br>you can select services or accessories to proceed .
    </div> ";
    }
    else{
        $service_type = 'At Carix Center';
        $status = 'active';
        $date = date('Y-m-d ');
        $time = date('H:i:s');
        

        //address
        $select_address = "SELECT * FROM `user_address` WHERE `user_id` = $user_id";
        $run_select_address = mysqli_query($connect , $select_address);
        $address = mysqli_fetch_array($run_select_address);
        $address_id = $address['add_id'];
        //make order
        $insert_order = "INSERT INTO `orders` VALUES(NULL , $user_id , $checkout_price , $address_id , '$date' , '$time' , '$status' , '$service_type') ";
        $run_insert_order = mysqli_query($connect, $insert_order);

        //select cart
        $select_cart = "SELECT * FROM `carts` WHERE `user_id` = $user_id ";
        $run_select_cart = mysqli_query($connect, $select_cart);

        //order details
        $select_order = "SELECT * FROM `orders` WHERE `user_id` = $user_id
        ORDER BY `o_id` DESC LIMIT 1";
        $run_select_order = mysqli_query($connect, $select_order);
        $order = mysqli_fetch_array($run_select_order);
        $order_id = $order['o_id'];
        foreach ($run_select_cart as $cart) {
            $cart_id = $cart['id'];
            $product_id = $cart['product_id'];
            $service_id = $cart['service_id'];
            if ($product_id == null && $service_id != null) {
                $insert_details = "INSERT INTO `order_details` VALUES(NULL , $order_id , $user_id , NULL , $service_id )";
            } else if ($service_id == null && $product_id !== null) {
                $insert_details = "INSERT INTO `order_details` VALUES(NULL , $order_id , $user_id , $product_id , NULL  )";
                 //change quantity
                $select_product = "SELECT * FROM `products` WHERE `p_id` = $product_id";
                $run_select_product = mysqli_query($connect , $select_product);
                $product = mysqli_fetch_array($run_select_product);
                $quantity = $product['quantity'] - 1 ;
                $update_product = "UPDATE `products` 
                SET `quantity` = $quantity 
                WHERE `p_id` = $product_id";
                $run_update_product = mysqli_query($connect , $update_product);
                } else {
                    $insert_details = "INSERT INTO `order_details` VALUES(NULL , $order_id , $user_id , NULL , NULL )";
                }
                $run_insert_details = mysqli_query($connect, $insert_details);

            
                //delete from cart
                $delete_cart = "DELETE FROM `carts` WHERE `id` = $cart_id";
                $run_delete_cart = mysqli_query($connect, $delete_cart);
            }
                //payment
                $insert_payment = "INSERT INTO `payments` VALUES(NULL , $order_id , $user_id  , 1 )";
                $run_insert_payment = mysqli_query($connect, $insert_payment);

    } 
    header("location:vieworder.php");
}


//logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location:Admin-Login.php");
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
    <link rel="stylesheet" href="../customer/css/style.css" />
    <link rel="stylesheet" href="../customer/css/steps.css" />
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

    <!--*------------------------------------------- CheckOut --------------------------------------------->
    <section class="checkOut mainBg">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="custom-card">
                        <div class="d-flex align-items-center gap-4">
                            <a href="./step4.php" class="py-2 px-3 text-black"><i class="fa-solid fa-angle-left fa-xl"></i></a>
                            <div class="mainText">
                                <h4 class="text-capitalize">checkout</h4>
                                <p class="add">Now You can checkout Services Easily</p>
                            </div>
                        </div>
                        <hr>
                        <h4 class="fw-bold mb-4">Selected Services</h4>

                        <div class="d-flex gap-2 flex-column">
                            <?php foreach ($run_select_main as $main) { ?>
                                <div class="d-flex flex-wrap gap-4 justify-content-between align-items-end">
                                    <div class="d-flex flex-column">
                                        <p class="fw-bold mb-2"><?php echo $main['s_name']; ?></p>
                                        <p class="checkPrice"> <?php echo $main['s_price']; ?> EGP </p>
                                    </div>
                                    <a href="checkout.php?delete_main=<?php echo $main['id'] ?>" class="btn ms-auto fs-small rounded-0 checkBtn">
                                        Delete <i class="ms-2 fa-solid fa-trash fa-sm"></i></a>
                                </div>                        
                            <hr>
                            <?php } ?>
                            <h4 class="fw-bold mb-4">Selected Add-on Services</h4>
                            <?php foreach($run_select_add as $add){ ?> 
                            <div class="d-flex flex-wrap gap-4 justify-content-between align-items-end">
                                <div class="d-flex flex-column">
                                    <p class="text-black fw-bold mb-2"><?php echo $add['s_name'];?></p>
                                    <p class="checkPrice"> <?php echo $add['s_price'];?> EGP </p>
                                </div>
                                <a href="checkout.php?delete_add=<?php echo $add['id'] ?>" class="btn ms-auto fs-small rounded-0 checkBtn">
                                        Delete <i class="ms-2 fa-solid fa-trash fa-sm"></i></a>
                            </div>
                            <hr>
                            <?php } ?>
                            <h4 class="fw-bold mb-4">Selected accessories</h4>
                            <?php foreach($run_select_product as $product){?>
                            <div class="d-flex flex-wrap gap-4 justify-content-between align-items-end">
                                <div class="d-flex flex-column">
                                    <p class="text-black fw-bold mb-2"><?php echo $product['p_name'] ; ?></p>
                                    <p class="checkPrice"> <?php echo $product['p_price'];?> EGP </p>
                                </div>
                                <a href="checkout.php?delete_product=<?php echo $product['id'] ?>" class="btn ms-auto fs-small rounded-0 checkBtn">
                                        Delete <i class="ms-2 fa-solid fa-trash fa-sm"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 d-flex flex-column gap-4 vertical">
                    <div class="custom-card">
                        <p class="fw-bold text-capitalize">selected car</p>
                        <p class="p-3 fs-5 fw-bold carSelected text-white">NONE</p>
                    </div>
                    <div class="custom-card">
                        <div class="d-flex flex-column gap-3">
                            <p class="fs-4 fw-bold text-black d-flex gap-2 justify-content-between mb-4">
                                <span>Total</span><span class="total"><?php echo $total_sum ; ?> EGP</span>
                            </p>
                        </div>
                        <a  href="checkout.php?checkout=<?php echo $_SESSION['total_price'] ?>" class=" p-3 fs-5 fw-bold carSelected text-white text-center carSelected w-100 text-decoration-none" type="submit" name="checkout">Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--!---------------------------------------------- Footer -------------------------------------------->


    <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>