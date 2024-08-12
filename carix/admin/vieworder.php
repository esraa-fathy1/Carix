<?php

include '../connection.php';

if(!isset($_SESSION['admin'])){
    header("location:Admin-Login.php");
}

if($_SESSION['role'] == 'System_admin'){
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

//select order with users with address
if(isset($_POST['sort'])){
    $sort_by = $_POST['sort_by'];
    if($sort_by == 'old'){
        $select_order = "SELECT * FROM `orders`
        INNER JOIN `users`
        ON `orders`.`user_id` = `users`.`id`
        INNER JOIN `user_address`
        ON `orders`.`address_id` = `user_address`.`add_id`
        INNER JOIN `payments`
        ON `payments`.`order_id` = `orders`.`o_id`
        ORDER BY `orders`.`o_id` ASC";
    }
    else if($sort_by == 'new'){
        $select_order = "SELECT * FROM `orders`
        INNER JOIN `users`
        ON `orders`.`user_id` = `users`.`id`
        INNER JOIN `user_address`
        ON `orders`.`address_id` = `user_address`.`add_id`
        INNER JOIN `payments`
        ON `payments`.`order_id` = `orders`.`o_id`
        ORDER BY `orders`.`o_id` DESC";
    }
    else if($sort_by == 'high_price'){
        $select_order = "SELECT * FROM `orders`
        INNER JOIN `users`
        ON `orders`.`user_id` = `users`.`id`
        INNER JOIN `user_address`
        ON `orders`.`address_id` = `user_address`.`add_id`
        INNER JOIN `payments`
        ON `payments`.`order_id` = `orders`.`o_id`
        ORDER BY `orders`.`total_price` DESC";
    }
    else if($sort_by == 'low_price'){
        $select_order = "SELECT * FROM `orders`
        INNER JOIN `users`
        ON `orders`.`user_id` = `users`.`id`
        INNER JOIN `user_address`
        ON `orders`.`address_id` = `user_address`.`add_id`
        INNER JOIN `payments`
        ON `payments`.`order_id` = `orders`.`o_id`
        ORDER BY `orders`.`total_price` ASC";
    }
}
else if(isset($_POST['submit'])){
    $search = $_POST['search'];
    $select_order = "SELECT * FROM `orders`
    INNER JOIN `users`
    ON `orders`.`user_id` = `users`.`id`
    INNER JOIN `user_address`
    ON `orders`.`address_id` = `user_address`.`add_id`
    INNER JOIN `payments`
    ON `payments`.`order_id` = `orders`.`o_id`
    WHERE `orders`.`service_type` LIKE '%$search%'
    OR `orders`.`status` LIKE '%$search%'
    OR `orders`.`date` LIKE '%$search%'
    OR `orders`.`time` LIKE '%$search%'
    OR `users`.`phone` LIKE '%$search%'
    OR `users`.`email` LIKE '%$search%'
    OR `user_address`.`address` LIKE '%$search%'" ;
}
else{
    $select_order = "SELECT * FROM `orders`
    INNER JOIN `users`
    ON `orders`.`user_id` = `users`.`id`
    INNER JOIN `user_address`    
    ON `orders`.`address_id` = `user_address`.`add_id`
    INNER JOIN `payments`
    ON `payments`.`order_id` = `orders`.`o_id`";
}
$run_select_order = mysqli_query($connect , $select_order);


//delete 
if(isset($_GET['delete'])){
    $order_id = $_GET['delete'];
    $delete = "DELETE FROM `orders` WHERE `o_id` = $order_id";
    $run_delete = mysqli_query($connect , $delete);
    header("location:vieworder.php");
}


//change status
if(isset($_POST['change'])){
    $order_id = $_POST['change'];
    $status = $_POST['status'];
    $update = "UPDATE `orders` 
    SET `status` = '$status'
    WHERE `o_id` = $order_id";
    $run_update = mysqli_query($connect , $update);
    header("location:vieworder.php");
}


//add order
if(isset($_POST['add'])){
    header("location:step2.php");
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

    <!--*------------------------------------------- View Order ------------------------------------------->
    <section class="mainBg py-5 order">
        <div class="container view">
            <section class="filterBar justify-content-between d-flex align-items-center my-3">
                <div class="addIcon">
                    <form method="POST">
                        <button type="submit" class="px-3 rounded align-items-center d-flex fs-6" name="add"><span
                                class="fs-5">+</span> Add</button>
                    </form>
                </div>
                <div class="search-ui d-flex align-items-center">
                    <div class="search-container">
                    <form method="POST">
                            <input type="text" placeholder="Search by ID..." name="search">
                            <button type="submit" name="submit"><i class="fa fa-search" ></i></button>
                        </form>
                    </div>
                    <form method="POST" class="filter-ui d-flex addIcon" >
                        <select id="viewTable" aria-label="Sort By" class="form-select" name="sort_by">
                            <option value="old">Sort By: Oldest</option>
                            <option value="new">Sort By: Newest</option>
                            <option value="high_price">Sort By: Highest Price</option>
                            <option value="low_price">Sort By: Lowest Price</option>
                        </select>
                        <button type="submit" class="px-3 rounded align-items-center d-flex ms-2" name="sort">Sort</button>
                    </form>
                </div>
            </section>
            <div class="table-responsive">
                <table class="appointment">
                    <tr class="table-header">
                        <th class="ps-0 text-center">ORDER ID</th>
                        <th class="ps-0 text-center">User Phone</th>
                        <th class="ps-0 text-center">User Email</th>
                        <th class="ps-0 text-center">Address</th>
                        <th class="ps-0 text-center">Total Price</th>
                        <th class="ps-0 text-center">Service Type</th>
                        <th class="ps-0 text-center">Actual Status</th>
                        <th class="ps-0 text-center">Status</th>
                        <th class="ps-0 text-center">Date</th>
                        <th class="ps-0 text-center">Time</th>
                        <th class="ps-0 text-center">Paid</th>
                        <th class="ps-0 text-center">view</th>
                        <th class="ps-0 text-center">Delete</th>
                    </tr>
                    <?php foreach($run_select_order as $order){ 
                        $email = substr($order['email'], 0, strpos($order['email'], "@"));
                        ?>
                    <tr>
                    <td class="p-0 text-center"><?php echo $order['o_id'] ; ?></td>
                        <td class="p-0 text-center"><?php if(is_null($order['phone'])){ echo '-' ; } else{echo $order['phone'] ; }?></td>
                        <td class="p-0 text-center"><?php echo $order['email'] ;?> <br> @gmail.com</td>
                        <td class="p-0 text-center"><?php echo $order['address'] ; ?></td>
                        <td class="p-0 text-center"><?php echo $order['total_price'] ;?> EGP</td>
                        <td class="p-0 text-center"><?php echo $order['service_type'] ;?></td>
                        <?php if($order['status'] == 'active'){ ?>
                        <td class="p-0 text-center text-success fw-bold"><?php echo $order['status'] ; ?></td>
                        <?php } else if($order['status'] == 'decline'){?>
                        <td class="p-0 text-center text-danger fw-bold"><?php echo $order['status'] ; ?></td>
                        <?php } else { ?>
                        <td class="p-0 text-center text-primary fw-bold"><?php echo $order['status'] ; ?></td>
                        <?php } ?>
                        <td class="py-3 px-2 border-0 text-center status">
                            <form method="POST">
                            <select id="viewTable" aria-label="Sort By" class="form-select" name="status">
                                <!-- <option>Choose</option> -->
                                <option value="active" class="text-success">Active</option>
                                <option value="decline" class="text-danger">Decline</option>
                                <option value="pending" class="text-primary">Pending</option>
                            </select> <br>
                            <button type="submit" class="px-3 py-1 rounded align-items-center d-flex" value="<?php echo $order['o_id'] ; ?>" name="change">Change</button>
                            </form>
                        </td>
                        <td class="p-0 text-center"><?php echo $order['date'] ;?></td>
                        <td class="p-0 text-center"><?php echo $order['time'] ;?></td>
                        <td class="text-center"><?php if($order['paid'] == 0){
                            echo 'NO';}
                            else{
                                echo 'YES';
                            }
                         ?></td>
                        <td class="update text-center fs-5"><a href="order_details.php?details=<?php echo $order['o_id'] ?>"><i class="fa-solid fa-rotate"></i></a></td>
                        <td class="delete text-center fs-5"><a href="vieworder.php?delete=<?php echo $order['o_id'] ?>"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </section>

    <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>