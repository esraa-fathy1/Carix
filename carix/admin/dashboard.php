<?php

include '../connection.php';


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


//admin
$select_admin = "SELECT * FROM `admins`"; 
$run_select_admin = mysqli_query($connect , $select_admin);
$admin_count = mysqli_num_rows($run_select_admin);

//user
$select_user = "SELECT * FROM `users`"; 
$run_select_user = mysqli_query($connect , $select_user);
$user_count = mysqli_num_rows($run_select_user);


//feedback
$select_feedback = "SELECT * FROM `feedbacks`"; 
$run_select_feedback = mysqli_query($connect , $select_feedback);
$feedback_count = mysqli_num_rows($run_select_feedback);


//order
$select_order = "SELECT * FROM `orders`"; 
$run_select_order = mysqli_query($connect , $select_order);
$order_count = mysqli_num_rows($run_select_order);

//appointment
$select_appointment = "SELECT * FROM `appointments`"; 
$run_select_appointment = mysqli_query($connect , $select_appointment);
$appointment_count = mysqli_num_rows($run_select_appointment);



//product
$select_product = "SELECT * FROM `products`"; 
$run_select_product = mysqli_query($connect , $select_product);
$product_count = mysqli_num_rows($run_select_product);

//service
$select_service = "SELECT * FROM `services`"; 
$run_select_service = mysqli_query($connect , $select_service);
$service_count = mysqli_num_rows($run_select_service);


//car
$select_car = "SELECT * FROM `cars`"; 
$run_select_car = mysqli_query($connect , $select_car);
$car_count = mysqli_num_rows($run_select_car);


//emergency car status
$select_emergency_car = "SELECT * FROM `emergency_car`"; 
$run_select_emergency_car = mysqli_query($connect , $select_emergency_car);
$array = mysqli_fetch_array($run_select_emergency_car);


//change emergency car status
if(isset($_POST['submit'])){
    $status = $_POST['status'];
    $update = "UPDATE `emergency_car`
    SET `status` = '$status'";
    $run_update = mysqli_query($connect , $update);
    header("location:dashboard.php");
}

//logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location:./index.php");
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
    <link rel="stylesheet" href="./css/dashboard.css">
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

    <!---------------------------------------------- Dashboard --------------------------------------------->
    <section class="mainBg pt-5">
        <div class="container dashBoard">
            <div class="card card-stats w-50 m-auto mb-3">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon"><i class="fa-solid fa-rotate material-icons"></i></div>
                    <p class="card-category">Status of Emergency Cars</p>
                    <?php if($array['status'] == 'Available'){ ?>
                    <h5 class="card-title text-success fw-bold">Available</h5>
                    <?php } else { ?>
                        <h5 class="card-title text-danger fw-bold">Not Available</h5>
                    <!-- <td class="p-0 text-center text-danger fw-bold">Not Available</td> -->
                    <?php } ?>
                    <!-- <td class="p-0 text-center text-primary fw-bold">Pending</td> -->
                </div>
                <form method="POST">
                    <div class="panel-footer d-flex">
                        <select id="viewTable" aria-label="Sort By" class="form-select mx-3" name="status">
                            <!-- <option>Choose</option> -->
                            <option value="Available" class="text-success">Available</option>
                            <option value="Not Available" class="text-danger">Not Available</option>
                        </select>
                        <button type="submit" class="px-3 py-1 rounded yellOw mx-3" name="submit">Change</button>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-purple card-header-icon">
                            <div class="card-icon"><i class="fa-solid fa-user-group material-icons"></i></div>
                            <p class="card-category">Admins</p>
                            <h3 class="card-title"><?php echo $admin_count ; ?></h3>
                        </div>
                        <a href="./viewadmin.php" class="purPle">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-dark card-header-icon">
                            <div class="card-icon"><i class="fa-solid fa-users material-icons"></i></div>
                            <p class="card-category">Users</p>
                            <h3 class="card-title"><?php echo $user_count ; ?></h3>
                        </div>
                        <a href="./viewuser.php" class="beiGe">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-purple card-header-icon">
                            <div class="card-icon"><i class="fa-solid fa-comments material-icons"></i></div>
                            <p class="card-category">Feedbacks</p>
                            <h3 class="card-title"><?php echo $feedback_count ; ?></h3>
                        </div>
                        <a href="./viewreviews.php" class="purPle">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-dark card-header-icon">
                            <div class="card-icon"><i class="fa fa-shopping-cart material-icons"></i></div>
                            <p class="card-category">Orders</p>
                            <h3 class="card-title"><?php echo $order_count ; ?></h3>
                        </div>
                        <a href="./vieworder.php" class="beiGe">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-dark card-header-icon">
                            <div class="card-icon"><i class="fa-solid fa-calendar-check material-icons"></i></div>
                            <p class="card-category">Appointments</p>
                            <h3 class="card-title"><?php echo $appointment_count ; ?></h3>
                        </div>
                        <a href="./viewappointment.php" class="beiGe">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-purple card-header-icon">
                            <div class="card-icon"><i class="fa-brands fa-product-hunt material-icons"></i></div>
                            <p class="card-category">Products</p>
                            <h3 class="card-title"><?php echo $product_count ; ?></h3>
                        </div>
                        <a href="./viewproduct.php" class="purPle">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-dark card-header-icon">
                            <div class="card-icon"><i class="fa-solid fa-screwdriver-wrench material-icons"></i></div>
                            <p class="card-category">Services</p>
                            <h3 class="card-title"><?php echo $service_count ; ?></h3>
                        </div>
                        <a href="./viewservice.php" class="beiGe">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-purple card-header-icon">
                            <div class="card-icon"><i class="fa-solid fa-car material-icons"></i></div>
                            <p class="card-category">Cars</p>
                            <h3 class="card-title"><?php echo $car_count ; ?></h3>
                        </div>
                        <a href="./viewcars.php" class="purPle">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>