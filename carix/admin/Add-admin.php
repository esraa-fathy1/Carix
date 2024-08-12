<?php
include "../connection.php";
if(isset($_SESSION['admin'])){
    $admin_id = $_SESSION['admin'];
    // echo $user_id;
}
if(!isset($_SESSION['admin'])){
    header("location:Admin-Login.php");
}
if($_SESSION['role'] != 'Manager'){
    header("location:dashboard.php");
}

if(!isset($_SESSION['role'])){
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

$alert="";



// Function to fetch enum values
function getEnumValues($table, $column, $pdo) {
    $query = $pdo->prepare("SHOW COLUMNS FROM $table LIKE '$column'");
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $enumList = str_replace("'", "", substr($row['Type'], 5, (strlen($row['Type'])-6)));
    return explode(',', $enumList);
}

// Database connection
$dsn = 'mysql:host=localhost;dbname=carix';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Fetch enum values
$enumValues = getEnumValues('admins', 'role', $pdo);

//add admin
if(isset($_POST['add'])){
    $email=$_POST['email'];
    $password=$_POST['password'];
    $role=$_POST['role'];

       //declaration variables for password
       $lowercase = preg_match('@[a-z]@', $password);
       $uppercase = preg_match('@[A-Z]@', $password);
       $number = preg_match('@[0-9]@', $password);
       $specialcharachter = preg_match('@[^\w]@', $password);
   
       if ($lowercase < 1 || $uppercase < 1 || $specialcharachter < 1 || $number < 1) {
           $alert="password is weak, it must contain at least one lowercase letter ,uppercase letter, special charachter ,number!";
       }else{
        $password=password_hash($password, PASSWORD_DEFAULT);

    //check from email
    $select = "SELECT * FROM `admins` WHERE `email` = '$email'";
    $run_select = mysqli_query($connect, $select);

    $num_rows = mysqli_num_rows($run_select);
    if($num_rows>0){
        $alert="Email already exists";
    }else{
        $insert ="INSERT INTO `admins` VALUES(NULL,'$email','$password','$role')";
        $run_insert = mysqli_query($connect, $insert);
        header("location:viewadmin.php");
    }
       }       
}

//Update
$update=false;
$email="";
$role="";
 if(isset($_GET['edit'])){
    $update=true;
    $id=$_GET['edit'];
    $select="SELECT * FROM `admins` WHERE `id`=$id";
    $run_select = mysqli_query($connect, $select);
    $fetch=mysqli_fetch_assoc($run_select);
    $email=$fetch["email"];
    $role=$fetch["role"];
 }

 if(isset($_POST['update'])){
    $new_email=$_POST['email'];
    $new_role=$_POST['role'];

    if($new_email==$email){
        if($new_role==$role){
            header("location:viewadmin.php");
        }
        else{
            $update_admin="UPDATE `admins` SET `role`='$new_role' WHERE `id`=$id";
            $run_update_admin=mysqli_query($connect,$update_admin);
            header("location:viewadmin.php");
        }
    }else{
 //check from email
             $select = "SELECT * FROM `admins` WHERE `email` = '$new_email'";
             $run_select = mysqli_query($connect, $select);

             $num_rows = mysqli_num_rows($run_select);
                 if($num_rows>0){
                          $alert="Email already exists";}
                 else{
                        if($new_role==$role){
                            $update_admin="UPDATE `admins` SET `email`='$new_email'WHERE `id`=$id";
                             $run_update_admin=mysqli_query($connect,$update_admin);
                             header("location:viewadmin.php"); }
                        else{
                            $update_admin="UPDATE `admins` SET `email`='$new_email' , `role`='$new_role' WHERE `id`=$id";
                            $run_update_admin=mysqli_query($connect,$update_admin);
                            header("location:viewadmin.php"); }
                     }
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
    <link rel="stylesheet" href="./css/add-admin.css" />
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


    <!--*------------------------------------------ Add Admin ---------------------------------------------->
    <section class="mainBg py-3">
        <div class="container forms">
        <?php if($alert){ ?>
                        <div class="alert alert-danger text-center w-100 mt-3 m-auto">
                            <strong><?php echo $alert; ?></strong>
                        </div>
                        <?php } ?>
            <div class="wrapper">
                <div class="inner">
                    <div class="image-holder">
                        <img src="../images/Forms/Profile.svg" alt="">
                    </div>
                    <form method="POST" class="pt-0">
                    <?php if(!$update){ ?>
                        <h3>Add Admin</h3>
                        <?php }else{ ?>
                        <h3>Update Admin</h3>
                        <?php } ?>
                        <div class="form-holder">
                            <input value="<?php echo $email ?>" type="email" name="email" placeholder="e-mail" class="form-control" style="font-size: 15px;" required>
                        </div>
                        <?php if(!$update){ ?>
                        <div class="form-holder">
                            <input type="password" name="password" placeholder="Password" class="form-control" style="font-size: 15px;" required>
                        </div>
                        <?php }else{?>
                        <div>
                            <label for="">Admin role: <?php echo $role; ?></label>
                        </div>
                        <?php } ?>
                        <div class="form-holder">
                            <select name="role" id="">
                            <?php foreach ($enumValues as $value){ ?>
                              <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                              <?php } ?>
                            </select>
                            <!-- <input type="text" placeholder="Role" class="form-control" style="font-size: 15px;"> -->
                        </div>
                        <div class="form-login">
                            <!-- <a href="" class="up">Add Admin</a> -->
                            <?php if(!$update){ ?>
                            <button class="up" type="submit" name="add">Add Admin</button>
                            <?php }else{ ?>
                            <button class="up" type="submit" name="update">Update Admin</button>
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