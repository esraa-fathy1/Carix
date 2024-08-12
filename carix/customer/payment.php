<?php
include '../connection.php';
// session_unset();
// session_destroy();
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
}
else{
    header('location:./signin.php');
}

if(!isset($_SESSION['date'])){
    header("location:./date&time.php");
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



//delievery
$select_services = "SELECT * FROM `carts`
WHERE `service_id` IS NOT NULL
AND `user_id` = $user_id ";
$run_select_service = mysqli_query($connect , $select_services);
$services_count = mysqli_num_rows($run_select_service);

$delievry_price = 50 ; 

if($_SESSION['service_type'] == 'Deliver to your Home' &&  $services_count >  0 ){
    if($_SESSION['area'] ==  'new cairo'){
        $delievry_price = 500 ; 
    }
    else if($_SESSION['area'] ==  'october'){
        $delievry_price = 400 ; 
    }
    else if($_SESSION['area'] ==  'zayed'){
        $delievry_price = 400 ; 
    }
    else if($_SESSION['area'] ==  'giza'){
        $delievry_price = 300 ; 
    }
    else{
        $delievry_price = 350 ; 
    }
    
}

//discount
$discount = 0;
if(isset($_SESSION['wallet']) || isset($_SESSION['code_cash'])){
    if(isset($_SESSION['wallet'])){
        $discount = $discount + $_SESSION['wallet'];
    }
    if(isset($_SESSION['code_cash'])){
        $discount = $discount + $_SESSION['code_cash'];
    }
}

$sub_total = $_SESSION['total_price'];
$total_price = $_SESSION['total_price'];



//wallet 
if(!isset($_SESSION['cash_wallet'])){
$select_wallet = "SELECT * FROM `wallets` WHERE `user_id` = $user_id";
$run_select_wallet = mysqli_query($connect, $select_wallet);
$wallet = mysqli_fetch_array($run_select_wallet);
// $cash = $wallet['cash'];
$_SESSION['wallet_id'] = $wallet['id'];
// $cash_wallet = $wallet['cash'];
$cash_wallet = $wallet['cash'];
$_SESSION['cash_wallet'] =$cash_wallet ;
}

//add from wallet 
if (isset($_POST['add_wallet'])) {
    if (!is_numeric($_POST['wallet'])) {
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong>wallet must be a valid number!</strong><br>
    </div> ";
    } 
    else if($_SESSION['cash_wallet'] < $_POST['wallet']){
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong>You don't have enough cash in wallet!</strong><br>
    </div> "; 
    }
    else{
    if(isset($_SESSION['wallet'])){
        $_SESSION['wallet'] =$_SESSION['wallet'] + $_POST['wallet'];
    }
    else{
        $_SESSION['wallet'] = $_POST['wallet'];
    }
  
    // if (empty($_POST['wallet'])) {
    //     $_SESSION['cash_wallet'] = $_SESSION['cash_wallet'] - 0;
    // }
     if(!empty($_POST['wallet'])) {
        $_SESSION['cash_wallet'] =  $_SESSION['cash_wallet'] - $_POST['wallet'];
        $total_price = $total_price - $_SESSION['wallet'];
    }
    header("location:payment.php");
    }
    
}



//coupon
if (isset($_POST['add_coupon'])) {
    $code = $_POST['code'];
    $select_code = "SELECT * FROM `users` 
    WHERE `code` = '$code' ";
    $run_select_code = mysqli_query($connect, $select_code);
    $count = mysqli_num_rows($run_select_code);

    //user can't use his code
    $select_user_code = "SELECT * FROM `users` 
    WHERE `code` = '$code'
    AND `id` = $user_id " ;
    $run_select_user_code = mysqli_query($connect , $select_user_code);
    $code_row = mysqli_num_rows($run_select_user_code);


    $select_user_order = "SELECT * FROM `orders` WHERE `user_id` = $user_id";
    $run_select_order = mysqli_query($connect , $select_user_order);
    $order_count = mysqli_num_rows($run_select_order);
    
    if($order_count >= 1){
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong> Sorry you can't use coupons!</strong><br> Coupons are only available for new users
    </div> ";
    }
    else if($code_row >= 1){
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong> Invalid coupon!</strong><br> You can't use your coupon
    </div> ";
    }
    else if (!$count) {
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong> Invalid coupon!</strong><br> you have to choose valid coupon
    </div> ";
    }   
    else if(isset($_SESSION['code_cash'])){
        echo  "<div class='alert alert-danger text-center w-50 m-auto'>
        <strong>Already used!</strong><br> You have already used a code
    </div> ";
    }
    else {
        $user = mysqli_fetch_array($run_select_code);
        $_SESSION['code_user_id'] = $user['id'];
        $_SESSION['code'] = $code;
        $_SESSION['code_cash'] = $total_price * 0.1 ;  
        // }          
        $total_price = $total_price - $_SESSION['code_cash'] ; 
        header("location:payment.php");
    }


// }
    }

    


//payment 
if (isset($_POST['submit'])) {

        $address = $_SESSION['address_id'];
        $date = $_SESSION['date'];
        $time = $_SESSION['time'];
        $type = $_SESSION['service_type'];
        $select_cart = "SELECT * FROM `carts` WHERE `user_id` = $user_id ";
        $run_select_cart = mysqli_query($connect, $select_cart);
        if(mysqli_num_rows($run_select_cart) == 0){
            echo  "<div class='alert alert-danger text-center w-50 m-auto'>
            <strong>you have nothing to pay!</strong><br> you can go to cart and select services
        </div> ";
        }else{
        if (isset($_SESSION['wallet'])) {
            $wallet_cash = $_SESSION['wallet'];
            $cash = $_SESSION['cash_wallet'];
            $wallet_id = $_SESSION['wallet_id'];
            $update_wallet = "UPDATE `wallets` SET `cash` = $cash  WHERE `id` = $wallet_id ";
            $run_update_wallet = mysqli_query($connect, $update_wallet);
            $total_price = $total_price - $wallet_cash;
            }
        

        $insert_order = "INSERT INTO `orders` VALUES(NULL , $user_id , $sub_total , $address , '$date' , '$time' , 'pending' , '$type') ";
        $run_insert_order = mysqli_query($connect, $insert_order);

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
            $delete_cart = "DELETE FROM `carts` WHERE `id` = $cart_id";
            $run_delete_cart = mysqli_query($connect, $delete_cart);
        }
        $insert_payment = "INSERT INTO `payments` VALUES(NULL , $order_id , $user_id  , 0 )";
        $run_insert_payment = mysqli_query($connect, $insert_payment);



        //add to wallet after payment
        if(!isset($_SESSION['code_cash'])){
        $new_wallet_cash =($total_price * 0.1) + $_SESSION['cash_wallet'];
        $wallet_id  = $_SESSION['wallet_id'] ;
        $add_to_wallet = "UPDATE  `wallets` SET `cash` = $new_wallet_cash  WHERE `id` = $wallet_id ";
        $run_add_to_wallet = mysqli_query($connect, $add_to_wallet);
        }


        //add cashback to original user
        if(isset($_SESSION['code'])){
            $code_user_id = $_SESSION['code_user_id'];
            $select_code_wallet = "SELECT * FROM `wallets` WHERE `user_id` = $code_user_id";
            $run_select_code_wallet = mysqli_query($connect , $select_code_wallet);
            $code_user_wallet = mysqli_fetch_array($run_select_code_wallet);
            $code_user_cash = $code_user_wallet['cash'];
            $code_discount = $code_user_cash + ($total_price * 0.05);
            $update_original_user = "UPDATE `wallets` SET `cash` = $code_discount WHERE `user_id` = $code_user_id";
            $run_update_original = mysqli_query($connect , $update_original_user);
        }



        if ($run_insert_payment) {
            unset($_SESSION['cash_wallet']);
            unset($_SESSION['wallet']);
            unset($_SESSION['code']);
            unset($_SESSION['code_cash']);
            unset($_SESSION['total_price']);
            unset($_SESSION['code_user_id']);
            header("location:orders.php");
        }
    }
} 



//logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location:./index.php");
}

?>



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
    <link rel="stylesheet" href="./css/multisteps.css" />
    <title>CariX</title>
</head>

<body>
    <!--!-------------------------------------------- Navbar ---------------------------------------------->
    <nav class="navbar navbar-expand-lg mainBg mt-3 sticky-top">
        <div class="container-fluid text-capitalize">
            <div class="navImage">
                <img src="../images/<?php echo $web_logo ?>" class="img-fluid" alt="Logo">
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse centerNav" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php#service">service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php#about">about us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            book a service
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="./index.php#appointment">Make an Appointment</a></li>
                            <li><a class="dropdown-item" href="./step1.php">make an order</a></li>
                            <!-- <li>
                                <hr class="dropdown-divider">
                            </li> -->
                            <li>
                            <!-- <li><a class="dropdown-item" href="#">Emergency car</a></li> -->
                        </ul>
                    </li>
                    <?php if(isset($_SESSION['user'])){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-user"></i> my profile
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="orders.php">orders</a></li>
                            <li><a class="dropdown-item" href="addcar.php">Add car</a></li>
                            <li><a class="dropdown-item" href="savedaddress.php">My Addresses</a></li>
                            <li><a class="dropdown-item" href="editprofile.php">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="changepass.php">Change Password</a></li>
                            <li><a class="dropdown-item" href="htu.php">How To Use</a></li>
                            <li><a class="dropdown-item" href="neargas.php">Nearest Gas Station</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="index.php?logout"><i class="fa-solid fa-right-from-bracket"></i>
                                    logout</a></li>
                        </ul>
                    </li>
                    <?php }?>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <?php if(!isset($_SESSION['user'])){ ?>
                        <a class="nav-link rightUser px-3" href="signin.php"><i
                                class="fa-solid fa-right-to-bracket"></i>&nbsp;Login</a>
                                <?php } ?>
                    
                    <a class="nav-link rightNav" href="//api.whatsapp.com/send?phone=2<?php echo $web_phone ?>"><i
                            class="fa-brands fa-whatsapp"></i></a>
                    <!--todo-------------------------------------------- Hotline ----------------------------------------------->
                    <a class="nav-link rightNav" href="#hotline"><img src="../images/services.png" alt="24"
                            width="100%"></a>
                    <div id="hotline" class="popup">
                        <a href="#" class="close">&times;</a>
                        <h3>Call Emergency Car</h3>
                        <p class="fs-5 fw-bold text-center"><i class="fa-solid fa-phone-volume"></i><?php echo $web_hotline ?></p>
                    </div>
                    <a href="#" class="close-popup"></a>
                    <!--todo----------------------------------------- End Hotline ----------------------------------------------->
                    <a class="nav-link navCart rightNav" href="./checkout.php"><i class="fa-solid fa-cart-shopping"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <!--*------------------------------------------ Multi Steps ------------------------------------------->
    <section class="mainBg py-5">
        <div class="container multiSteps">
            <form id="form5" method="POST">
                <div class="row align-items-center justify-content-center">
                    <!-- <div class="col-lg-4 col-md-4 col-sm-12">

                        <h3>Choose the Payment Method</h3>
                        <p>By choosing the method of payment, the order will be made and you will receive a confirmation
                            code</p>
                        <hr>
                        <div class="d-flex flex-column gap-3">
                            <div class="fw-bold border p-3">
                                <div class="form-check d-flex align-items-center gap-3">
                                    <input type="radio" name="payment" class="form-check-input" id="payment-1" value="Cash">
                                    <label class="form-check-label align-items-center d-flex gap-4" for="payment-1"><img alt="Cash" class="paymentImage" src="	https://backend.carviseta.com/uploads/Payment_types/UZu2jFgS7PpdltuQG5tBey8MxBIEY6Zx0zDJwRke.png" width="20%">
                                        <span class="d-flex flex-column">
                                            <span class="fw-bold">Cash (upon delivery)</span>
                                            <span class="discount">10% cashback</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="fw-bold border p-3">
                                <div class="form-check d-flex align-items-center gap-3">
                                    <input type="radio" name="payment" class="form-check-input" id="payment-2" value="Credit / Debit (on Delivery)">
                                    <label class="form-check-label align-items-center d-flex gap-4" for="payment-2"><img alt="Visa" class="paymentImage" src="https://backend.carviseta.com/uploads/Payment_types/3PWE9JM3sESsiw6AXW3FXfo3PZ0VQI2P1SfBsE4i.jpg" width="20%">
                                        <span class="d-flex flex-column">
                                            <span class="fw-bold text-black">Credit / Debit (on Delivery)</span>
                                            <span class="discount">5% cashback</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="fw-bold border p-3">
                                <div class="form-check d-flex align-items-center gap-3">
                                    <input type="radio" name="payment" class="form-check-input" id="payment-3" value="CIB Credit Card">
                                    <label class="form-check-label align-items-center d-flex gap-4" for="payment-3"><img alt="CIB" class="paymentImage" src="https://backend.carviseta.com/uploads/Payment_types/X0vtaaHXTbWOOFdwtMwYrdkH2YiNMCcNUrmzwtHk.jpg" width="20%">
                                        <span class="d-flex flex-column">
                                            <span class="fw-bold text-black">CIB Credit Card</span>
                                            <span class="discount">10% discount</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="fw-bold border p-3">
                                <div class="form-check d-flex align-items-center gap-3">
                                    <input type="radio" name="payment" class="form-check-input" id="payment-4" value="Installment Companies">
                                    <label class="form-check-label align-items-center d-flex gap-4" for="payment-4"><img alt="Company" class="paymentImage" src="	https://backend.carviseta.com/uploads/Payment_types/uSYbHXpkugOI1ANqP1891kWRkbnaFCN2NvNuWEGu.png" width="20%">
                                        <span class="d-flex flex-column">
                                            <span class="fw-bold text-black">Installment Companies</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>

                    </div> -->
                    <div class="col-4 col-md-4 col-sm-12">
                        <img src="../images/Forms/reg-img.jpg" alt="Form Image" class="w-100">
                    </div>
                    <div class="col-4 col-md-4 col-sm-12">
                        <p class="fw-bold">Your Wallet <?php echo $_SESSION['cash_wallet']; ?> EGP</p>
                        <div class="d-flex flex-column gap-3">
                            <form method="POST">
                                <div class="input-group">
                                    <input _type="number" placeholder="Add from Wallet" class="form-control" name="wallet">
                                    <button class="btn fw-bold px-5 rounded-0" type="submit" name="add_wallet"><span>Add</span></button>
                                </div>
                            </form>
                            <!-- <div class="input-group">
                            <input _type="text" placeholder="Referral Code" class="form-control">
                            <button type="button" class="btn fw-bold px-5 rounded-0"><span>Add</span></button>
                        </div> -->
                            <form method="POST">
                                <div class="input-group">
                                    <input _type="text" placeholder="Coupon" class="form-control" name="code">
                                    <button class="btn fw-bold px-5 rounded-0" type="submit" name="add_coupon"><span>Add</span></button>
                                </div>
                            </form>
                        </div>
                        <div class="border p-3 mt-3">
                            <div class="d-flex gap-2 flex-column">
                                <p class="d-flex justify-content-between fw-bold">
                                    <span>Sub-total</span>
                                    <span><?php echo $sub_total; ?></span>
                                </p>
                                <p class="d-flex justify-content-between fw-bold">
                                <span>Delivery</span>
                                <span><?php echo $delievry_price ; ?> EGP</span>

                            </p>
                                <p class="d-flex justify-content-between fw-bold wallet">
                                    <span>Discount</span>
                                    <span>-<?php echo $discount;  ?> EGP</span>
                                </p>
                            </div>
                            <hr>
                            <p class="d-flex justify-content-between text-dark-gray mb-4 fs-5 fw-bold">
                                <span>Total</span>
                                <span class="total"><?php echo $sub_total - $discount; ?> EGP</span>
                               

                            </p>
                        </div>
                        <button class="btn w-100 paymentBtn mt-3 fw-bold" type="submit" name="submit">Checkout Now</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!--!---------------------------------------------- Footer -------------------------------------------->
    <footer class="mainBg">
        <svg class="footer-wave-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 100"
            preserveAspectRatio="none">
            <path class="footer-wave-path"
                d="M851.8,100c125,0,288.3-45,348.2-64V0H0v44c3.7-1,7.3-1.9,11-2.9C80.7,22,151.7,10.8,223.5,6.3C276.7,2.9,330,4,383,9.8 c52.2,5.7,103.3,16.2,153.4,32.8C623.9,71.3,726.8,100,851.8,100z">
            </path>
        </svg>
        <div class="footer-section">
            <div class="container">
                <div class="footer-cta pt-5 pb-5" id="contact">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="single-cta d-flex justify-content-center">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="cta-text ps-3">
                                    <h4>Find us</h4>
                                    <span><?php echo $web_address ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="single-cta d-flex justify-content-center">
                                <i class="fas fa-phone"></i>
                                <div class="cta-text ps-3">
                                    <h4>Call us</h4>
                                    <span><?php echo $web_hotline ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 mail">
                            <div class="single-cta d-flex justify-content-center">
                                <i class="far fa-envelope-open"></i>
                                <div class="cta-text ps-3">
                                    <h4>Mail us</h4>
                                    <span><?php echo $web_mail ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-content pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-4 px-4 d-flex align-items-center footerCenter">
                            <div class="footer-logo">
                                <a href="index.php"><img src="../images/<?php echo $web_logo ?>" alt="logo" width="100%"></a>
                            </div>
                        </div>
                        <div class="col-lg-4 px-4 footerCenter d-flex flex-column">
                            <div class="footer-widget">
                                <div class="footer-widget-heading">
                                    <h5>Working Hours</h5>
                                </div>
                                <ul class="list-unstyled open-hours list-style-none">
                                    <li class="d-flex gap-4"><span>Sun-Thurs</span>
                                        <span><?php echo $web_wh ?></span>
                                    </li>
                                    <li class="d-flex gap-5"><span>Fri - Sat</span><span>
                                            Closed</span>
                                    </li>
                                </ul>
                            </div>
                            <!-- <div class="footer-widget">
                                <div class="footer-widget-heading">
                                    <h5>We Accept</h5>
                                </div>
                                <div class="footer-visa d-flex flex-row gap-4">
                                    <img src="../images/Home/VISA-logo.png" alt="Visa">
                                    <img src="../images/Home/footermastercard.webp" alt="">
                                </div>

                            </div> -->
                        </div>
                        <div class="col-lg-4 px-4 d-flex align-items-center footerCenter">
                            <div class="footer-text">
                                <p> <span class="fw-bold">CARIX WILL SERVICE YOUR CAR AT YOUR DOORSTEP</span> <br>
                                    We provide full-mobilized car workshop services at the chosen time and location,
                                    with experienced technicians,
                                    high-quality tools, and competitive prices.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright-area">
                <div class="copyright-text text-center">
                    <p class="py-1">Copyright &copy; 2024, All Right Reserved <a href="index.php">CariX</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>