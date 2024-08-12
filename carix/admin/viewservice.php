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

//select with sort
if(isset($_POST['sort'])){
    $sort_by = $_POST['sort_by'];
    if($sort_by == 'high_price'){
        $select_service = "SELECT * FROM `services`
        ORDER BY `s_price` DESC";
    }
    else if($sort_by == 'low_price'){
        $select_service = "SELECT * FROM `services`
        ORDER BY `s_price` ASC";
    }
    else if($sort_by == 'main'){
        $select_service = "SELECT * FROM `services`
        WHERE `type` = 'main'";
    }
    else if($sort_by == 'additional'){
        $select_service = "SELECT * FROM `services`
        WHERE `type` = 'additional'";
    }
}
else if(isset($_POST['submit'])){
    $search = $_POST['search'];
    $select_service = "SELECT * FROM `services`
    WHERE `s_name` LIKE '%$search%'
    OR `s_price` LIKE '%$search%'
    OR `description` LIKE '%$search%'";
}
else{
    $select_service = "SELECT * FROM `services`";
}
$run_select_service = mysqli_query($connect , $select_service);



//delete service
if(isset($_GET['delete'])){
    $service_id = $_GET['delete'];
    $delete = "DELETE FROM `services` WHERE `s_id` = $service_id";
    $run_delete = mysqli_query($connect , $delete);
    header("location:viewservice.php");
}


//redirect to add page
if(isset($_POST['add'])){
    header("location:Add-service.php");
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

    <!--*----------------------------------------- View Service -------------------------------------------->
    <section class="mainBg py-5">
        <div class="container view">
            <section class="filterBar justify-content-between d-flex align-items-center my-3">
                <div class="addIcon">
                    <form method="POST">
                        <button type="submit" class="px-3 rounded align-items-center d-flex fs-5" name="add"><span
                                class="fs-4">+</span> Add</button>
                    </form>
                </div>
                <div class="search-ui d-flex align-items-center">
                    <div class="search-container">
                        <form method="POST">
                            <input type="text" placeholder="Search by service name..." name="search">
                            <button type="submit" name="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <form class="filter-ui d-flex addIcon" method="POST">
                        <select id="viewTable" aria-label="Sort By" class="form-select" name="sort_by">
                            <option value="high_price">Sort By: Highest Price</option>
                            <option value="low_price">Sort By: Lowest Price</option>
                            <option value="main">Sort By: Main Type</option>
                            <option value="additional">Sort By: Additional Type</option>
                        </select>
                        <button type="submit" class="px-3 rounded align-items-center d-flex ms-2" name="sort">Sort</button>
                    </form>
                </div>
            </section>
            <table>
                <tr class="table-header">
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th class="ps-0 text-center">Type</th>
                    <th class="ps-0 text-center">Update</th>
                    <th class="ps-0 text-center">Delete</th>
                </tr>
                <?php foreach($run_select_service as $service){ ?>
                <tr>
                    <td class="serviceName"><?php echo $service['s_name'] ; ?></td>
                    <td class="price"><?php echo $service['s_price'] ; ?> EGP</td>
                    <td class="content">
                        <div>
                            <p class="text-dark-gray fw-bold mb-2"> This service contains </p>
                            <ul class="list-discs">
                                <li><?php echo $service['description'] ;?></li>
                                <!-- <li>Air Filter</li>
                                <li>Oil Filter</li>
                                <li>Motor oil Shell (5Wِ-40 Ultra) 10,000 1.0L</li>
                                <li>Lubricant</li> -->
                            </ul>
                        </div>
                    </td>
                    <td class="p-0 text-center"><?php echo $service['type'] ; ?></td>
                    <td class="update text-center fs-4"><a href="Add-service.php?update=<?php echo $service['s_id'] ?>"><i class="fa-solid fa-rotate"></i></a></td>
                    <td class="delete text-center fs-4"><a href="viewservice.php?delete=<?php echo $service['s_id'] ?>"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </section>

    <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>