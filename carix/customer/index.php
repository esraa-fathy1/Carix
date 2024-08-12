<?php
include "../connection.php";
$address = false;
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    $select_add = "SELECT * FROM `user_address`
JOIN `areas` ON `user_address`.`area_id`=`areas`.`id`
WHERE `user_address`.`user_id`=$user_id";
    $run_add = mysqli_query($connect, $select_add);
    $rows = mysqli_num_rows($run_add);
    if ($rows > 0) {
        $address = true;
    } else {
        $address = false;
    }
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

//brands slider
$select_brands = "SELECT * FROM `brands`";
$run_select_brands = mysqli_query($connect, $select_brands);

//appointment form
$alert = "";
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $reason = $_POST['reason'];
    $car_details = $_POST['details'];
    $date = $_POST['date'];
    $date = date("Y-m-d", strtotime($date));
    $time = $_POST['time'];
    $address = $_POST['address'];


    $select_appointment = "SELECT * FROM `appointments` WHERE `date` = '$date' AND `time` = '$time'";
    $run_select = mysqli_query($connect, $select_appointment);
    $count = mysqli_num_rows($run_select);
    // echo $count;
    if ($date <= date("Y-m-d")) {
        $alert = "Invalid date !!";
    } else {
        if ($count > 3) {
            $alert = "this time is already booked! Please Select different Time.";

        } else {
            if (isset($_SESSION['user'])) {
                $insert_appointment = "INSERT INTO `appointments` values(NULL,'$name','$email','$phone','$reason',
            '$car_details','$user_id','$date','$time',DEFAULT,'$address')";
                $run_insert_appointment = mysqli_query($connect, $insert_appointment);
            } else {
                $insert_appointment = "INSERT INTO `appointments` values(NULL,'$name','$email','$phone','$reason',
            '$car_details',NULL,'$date','$time',DEFAULT,'$address')";
                $run_insert_appointment = mysqli_query($connect, $insert_appointment);
            }
        }
    }
}

//feedbacks
$select_feedbacks = "SELECT * FROM `feedbacks` JOIN `users` ON `feedbacks`.`user_id`=`users`.`id` ORDER BY RAND()
LIMIT 3";
$run_select_feedbacks = mysqli_query($connect, $select_feedbacks);


//add feedback
if (isset($_POST['send'])) {
    $comment = $_POST['comment'];
    $id = $_SESSION['user'];

    $insert = "INSERT INTO `feedbacks` VALUES(NULL,'$comment',$id)";
    $run_insert = mysqli_query($connect, $insert);

    header("location:index.php");
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
                    <?php if (isset($_SESSION['user'])) { ?>
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
                                <li><a class="dropdown-item" href="index.php?logout"><i
                                            class="fa-solid fa-right-from-bracket"></i>
                                        logout</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <?php if (!isset($_SESSION['user'])) { ?>
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
                        <p class="fs-5 fw-bold text-center"><i class="fa-solid fa-phone-volume"></i>
                            <?php echo $web_hotline ?>
                        </p>
                    </div>
                    <a href="#" class="close-popup"></a>
                    <!--todo----------------------------------------- End Hotline ----------------------------------------------->
                    <a class="nav-link navCart rightNav" href="./checkout.php"><i
                            class="fa-solid fa-cart-shopping"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <!--*-------------------------------------------- Intro ----------------------------------------------->
    <section class="intro mainBg" id="itro">
        <div class="container py-2">
            <div class="cashBack text-center fw-bold">
                <p>
                    <img src="../images/Home/money-back_9226551.png" alt="Money" width="3%">
                    Cash Back – Enjoy 10% Cash back from your purchases worth
                </p>
            </div>
            <div class="text-center">
                <h1 class="text-capitalize">we care <span class="colored">about</span> your car</h1>
                <div class="lineSeparator my-3"></div>
                <p class="fs-5">We believe in providing top quality workmanship and are so confident in our level of
                    service that we
                    back it up with a good quality.</p>
                <img src="../images/Home/photo_2024-06-22_01-29-21.jpg" alt="BMW" class="bmwCar w-50">
                <p class="fs-5 pt-3">
                    <span class="fw-bold">Carix </span>saves you time and effort.
                    To Provide an Honest Auto Repair Shop for the Cairo & Giza area with Great Customer Service!
                    <br><br>
                    <span class="fw-bold gasses">We.Can.Help.You.To Find your Nearest Gas Station
                        <a href="neargas.php">
                            <i class="fa-solid fa-location-crosshairs"></i>
                            Click Here!
                        </a>
                    </span>
                </p>
            </div>
        </div>
    </section>

    <!--*------------------------------------------- Services --------------------------------------------->
    <section class="ourServices mainBg pt-5" id="service">
        <div class="container">
            <div class="text-center middle pt-4">
                <div class="lineSeparator mb-2"></div>
                <h2 class="d-flex flex-column"><span>Our Featured</span>Services</h2>
                <div class="lineSeparator mb-3"></div>
                <p class="fs-5">We provide full-mobilized car workshop services at the chosen time and location, with
                    experienced
                    technicians,
                    high-quality tools, and competitive prices.</p>
            </div>
            <div class="slider">
                <div class="slider-container">
                    <div class="slide active" style="background-image: url('../images/Home/CarServices/Brakes.jpg');">
                        <div class="info">
                            <h1>Brakes</h1>
                            <p>Brakes inspection and maintenance is an important procedure to be done regularly,
                                ensuring
                                the
                                safety of the car and
                                preventing any sudden accidents or potential tragedies.</p>
                        </div>
                    </div>
                    <div class="slide"
                        style="background-image: url('../images/Home/CarServices/Periodic Maintenance.webp');">
                        <div class="info">
                            <h1>Periodic Maintenance</h1>
                            <p>Periodic Maintenance is crucial for the overall health of your car, diagnosing and
                                efficiently
                                fixing the engine
                                problems.</p>
                        </div>
                    </div>
                    <div class="slide" style="background-image: url('../images/Home/CarServices/Coolant System.png');">
                        <div class="info">
                            <h1>Coolant System</h1>
                            <p>Periodical Inspection of the Coolant System and checking the concentration of the coolant
                                are
                                critical steps to protect
                                your engine from overheating or freezing.</p>
                        </div>
                    </div>
                    <div class="slide" style="background-image: url('../images/Home/CarServices/Diagnostics.jpg');">
                        <div class="info">
                            <h1>Diagnostics</h1>
                            <p>We provide several checkup services providing accurate assessment for Cars using latest
                                tools.</p>
                        </div>
                    </div>
                </div>
                <div class="eraser"></div>
                <div class="buttons-container">
                    <button id="previous"><i class="fas fa-chevron-left"></i></i></button>
                    <button id="next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="otherServices d-flex py-4 justify-content-center align-items-center gap-5 flex-wrap">
                <div class="d-flex flex-column gap-3 align-items-center justify-content-center">
                    <img src="../images/Home/CarServices/Air Conditioning.svg" alt="Air Conditioning">
                    <p class="text-capitalize fw-bold text-center">air conditioning</p>
                </div>
                <div class="d-flex flex-column gap-3 align-items-center justify-content-center">
                    <img src="../images/Home/CarServices/batteries.svg" alt="Batteries">
                    <p class="text-capitalize fw-bold text-center">batteries</p>
                </div>
                <div class="d-flex flex-column gap-3 align-items-center justify-content-center">
                    <img src="../images/Home/CarServices/Car Checks.svg" alt="Car Check">
                    <p class="text-capitalize fw-bold text-center">car check</p>
                </div>
                <div class="d-flex flex-column gap-3 align-items-center justify-content-center">
                    <img src="../images/Home/CarServices/DPF & Exhaust.svg" alt="DPF & Exhaust">
                    <p class="text-capitalize fw-bold text-center">DPF & Exhaust</p>
                </div>
                <div class="d-flex flex-column gap-3 align-items-center justify-content-center">
                    <img src="../images/Home/CarServices/oil changes.svg" alt="Oil Changes">
                    <p class="text-capitalize fw-bold text-center">oil changes</p>
                </div>
                <div class="d-flex flex-column gap-3 align-items-center justify-content-center">
                    <img src="../images/Home/CarServices/Suspension.svg" alt="Suspension">
                    <p class="text-capitalize fw-bold text-center">suspension</p>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center gap-5 flex-wrap py-5">
                <div class="d-flex flex-column gap-3 align-items-center">
                    <img src="../images/Home/Perfect/Fast Shipping.svg" alt="Fast Shipping">
                    <p class="fw-bold text-capitalize fs-5">fast shipping</p>
                </div>
                <div class="d-flex flex-column gap-3 align-items-center">
                    <img src="../images/Home/Perfect/Guaranteed Fit.svg" alt="Guaranteed Fit">
                    <p class="fw-bold text-capitalize fs-5">guaranteed fit</p>
                </div>
            </div>
        </div>
    </section>

    <!--*------------------------------------------ Car Brands -------------------------------------------->
    <section class="carBrands mainBg">
        <div class="container ">
            <div class="text-center middle pt-4">
                <div class="lineSeparator mb-2"></div>
                <h2 class="d-flex flex-column"><span>Servicing and repair the Area’s</span>Most Popular Brands</h2>
                <div class="lineSeparator mb-3"></div>
            </div>
            <div class="wrapper">
                <div class="photobanner">

                    <img class="first" src="../images/Home/CarBrands/logo-1.svg" alt="Honda" />
                    <?php foreach ($run_select_brands as $rows) { ?>
                        <img src="../images/Home/CarBrands/<?php echo $rows['logo'] ?>" alt="<?php echo $rows['name'] ?>" />
                    <?php } ?>
                    <!-- <img src="../images/Home/CarBrands/logo-3.svg" alt="Jeep" />
                    <img src="../images/Home/CarBrands/logo-4.svg" alt="KIA" /> -->
                    <!-- <img src="../images/Home/CarBrands/logo-5.svg" alt="BMW" />
                    <img src="../images/Home/CarBrands/logo-6.svg" alt="Mazda" />
                    <img src="../images/Home/CarBrands/logo-7.svg" alt="Mercedes" />
                    <img src="../images/Home/CarBrands/logo-8.svg" alt="Mitsubishi" />
                    <img src="../images/Home/CarBrands/logo-9.svg" alt="Nissan" />
                    <img src="../images/Home/CarBrands/logo-10.svg" alt="OPEL" />
                    <img src="../images/Home/CarBrands/logo-11.svg" alt="ALEA ROMEO" />
                    <img src="../images/Home/CarBrands/logo-12.svg" alt="Logan" />
                    <img src="../images/Home/CarBrands/logo-13.svg" alt="Seat" />
                    <img src="../images/Home/CarBrands/logo-14.svg" alt="Skooda" />
                    <img src="../images/Home/CarBrands/logo-15.svg" alt="Toyota" />
                    <img src="../images/Home/CarBrands/logo-16.svg" alt="W" />
                    <img src="../images/Home/CarBrands/logo-17.svg" alt="Audi" />
                    <img src="../images/Home/CarBrands/logo-18.svg" alt="BYD" />
                    <img src="../images/Home/CarBrands/logo-19.svg" alt="Chevrolet" />
                    <img src="../images/Home/CarBrands/logo-20.svg" alt="Citroen" />
                    <img src="../images/Home/CarBrands/logo-21.svg" alt="Peugeot" />
                    <img src="../images/Home/CarBrands/logo-22.svg" alt="Fiat" />
                    <img src="../images/Home/CarBrands/logo-23.svg" alt="Ford" />
                    <img src="../images/Home/CarBrands/logo-24.svg" alt="Geely" /> -->
                </div>
            </div>
        </div>
    </section>

    <!--?---------------------------------------------- Why ----------------------------------------------->
    <section class="sectionWhy mainBg pt-3" id="about">
        <div class="contentWhy">
            <h2>Why To Choose CariX</h2>
            <div class="lineSeparator mb-2"></div>
            <div class="row d-flex justify-content-center mx-auto py-3 px-4">
                <div class="columnA">
                    <h3 class="p1">We Make it Easy</h3>
                    <p class="paragraph1"> We provide 24/7 booking services and our mechanics are available during the
                        day
                        and in
                        the evenings. Whether you're at home, office, or anywhere, we will reach you at the scheduled
                        time.
                    </p>
                </div>
                <div class="columnB">
                    <h3 class="p2">Genuine & Aftermarket Parts</h3>
                    <p class="paragraph2">We offer both genuine and aftermarket parts, that are manufactured and
                        provided to
                        our
                        customers upon request, following the industry specifications.</p>
                </div>
            </div>
            <div class="row d-flex justify-content-center mx-auto py-3 px-4">
                <div class="columnA">
                    <h3 class="p3">Fair and Transparent Pricing</h3>
                    <p class="paragraph3">Because we aim to be your permanent partner, we put more value into our
                        products,
                        offering fair and transparent pricing to our customers. We work to achieve quality,
                        transparency,
                        and
                        affordability.</p>
                </div>
                <div class="columnB">
                    <h3 class="p4">High-quality</h3>
                    <p class="paragraph4">As we don't settle for less, we offer extensive training to our technicians,
                        utilizing
                        high-quality services and tools to achieve customer satisfaction. All you have to do is; book
                        through our
                        application.</p>
                </div>
            </div>
            <div>
                <p class="doit">Do it in just 3 simple steps</p>
                <p class="t2">Your Guide in Repairing <br> your Car.</p>
                <div class="lineSeparator mb-2"></div>
            </div>
        </div>
        <div class="yourGuide container">
            <div class="columnGuide">
                <div class="icon"><img src="../images/Home/Why/icon1.svg" alt="Icon 1"></div>
                <p>1. Choose the services you want and find out their prices.</p>
                <p>____</p>
            </div>
            <div class="columnGuide">
                <div class="icon"><img src="../images/Home/Why/icon2.svg" alt="Icon 2" alt="Icon 2"></div>
                <p>2. Choose a convenient date and time.</p>
                <p>____</p>
            </div>
            <div class="columnGuide pb-4">
                <div class="icon"><img src="../images/Home/Why/icon3.svg" alt="Icon 3" alt="Icon 3"></div>
                <p>3. Enjoy hassle-free car maintenance in record time, right where you are.</p>
                <p>____</p>
            </div>
        </div>
    </section>

    <!--?------------------------------------------ Appointment ------------------------------------------->
    <section class="sectionAppointment mainBg" id="appointment">
        <div class="container pt-5">
            <div class="row align-items-center pt-5">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="info-container">
                        <h2 class="info-title">Make an Appointment</h2>
                        <p>If you have any questions or concerns, please don't hesitate to reach out to us. You can
                            contact us
                            via email
                            at [
                            <?php echo $web_mail ?>], or by phone at [
                            <?php echo $web_phone ?>]. Our dedicated support team is
                            available
                            [Saturday -
                            Thursday
                            <?php echo $web_wh ?>]. We look forward to hearing from you and resolving any issues you
                            may have.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="login-box">
                        <form action="index.php#appointment" method="POST">
                            <div class="user-box">
                                <input type="text" id="name" name="name" required>
                                <label for="name">Your Name</label>
                            </div>
                            <div class="user-box">
                                <input type="email" id="email" name="email" required>
                                <label for="email">Email</label>
                            </div>
                            <div class="user-box">
                                <input type="tel" id="mobile" name="phone" required>
                                <label for="mobile">Phone Number</label>
                            </div>
                            <div class="user-box">
                                <input type="text" id="reason" name="reason" required>
                                <label for="reason">Appointment Reason</label>
                            </div>
                            <div class="user-box">
                                <input type="text" id="vehicle" name="details" required>
                                <label for="vehicle">Car_details</label>
                            </div>
                            <?php if (!isset($_SESSION['user']) || !$address) { ?>
                            <div class="user-box">
                                <input type="text" id="vehicle" name="address" required>
                                <label for="vehicle">Address</label>
                            </div>
                            <?php } ?>

                            <div class="user-box">
                                <input type="date" id="day" name="date" required>
                            </div>
                            <!-- <div class="user-box">
                                <input type="time" id="time" name="time" required>
                            </div> -->
                            <select id="time" aria-label="time" class="form-select" name="time" required>
                                <option value="">Select Time</option>
                                <option value="10:00 : 10:45"> 10:00 : 10:45</option>
                                <option value="11:00 : 11:45"> 11:00 : 11:45</option>
                                <option value="12:00 : 12:45"> 12:00 : 12:45</option>
                                <option value="13:00 : 13:45"> 13:00 : 13:45</option>
                                <option value="14:00 : 14:45"> 14:00 : 14:45</option>
                                <option value="15:00 : 15:45"> 15:00 : 15:45</option>
                                <option value="16:00 : 16:45"> 16:00 : 16:45</option>
                                <option value="17:00 : 17:45"> 17:00 : 17:45</option>
                            </select>
                            <?php if (isset($_SESSION['user']) && $address) { ?>
                                <div class="col-12">
                                    <!-- <label for="address" class="form-label">Address</label> -->
                                    <select id="address" aria-label="model" class="form-select mt-3" name="address">
                                        <option value="">Select your address</option>
                                        <?php foreach ($run_add as $row) { ?>
                                            <option
                                                value="<?php echo $row['address'] ?>, <?php echo $row['name'] ?>,<?php echo $row['city'] ?>">
                                                <?php echo $row['address'] ?>, <?php echo $row['name'] ?><br>
                                                <?php echo $row['city'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                            <button type="submit" name="submit" class="fw-bold my-4">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                submit
                            </button>
                            <?php if ($alert) { ?>
                                <div class='alert alert-danger text-center w-75 m-auto'>
                                    <strong><?php echo $alert ?></strong><br>
                                </div>
                            <?php } ?>

                            <!-- <a href="#">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                Submit
                            </a> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--?-------------------------------------------- Feedback -------------------------------------------->
    <section class="feedback mainBg pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="container__left">
                        <h1>Read what our customers love about us</h1>
                        <p>
                            Over 200 companies firm diverse sectors consult us to enhance the
                            user experience of their products and services.
                        </p>
                        <p>
                            We have helped companies increase their customer base and generate
                            multifold revenue with our service.
                        </p>
                        <!--! ----------------------------- Add Feedback ----------------------------------->
                        <?php if (isset($_SESSION['user'])) { ?>
                        <h1>Add your comment</h1>
                        <form method="POST" class="form-floating mt-4">
                            <textarea name="comment" class="form-control" placeholder="Leave a comment here"
                                id="floatingTextarea2" style="height: 100px"></textarea>
                            <label for="floatingTextarea">Your Comment:</label>
                            <!--Todo----------------------------- Button Feedback ----------------------------------->
                            <button name="send" type="submit" class="mt-3">
                                Send
                                <div class="star-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1"
                                        style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 784.11 815.53" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs></defs>
                                        <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer"></metadata>
                                            <path class="fil0"
                                                d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="star-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1"
                                        style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 784.11 815.53" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs></defs>
                                        <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer"></metadata>
                                            <path class="fil0"
                                                d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="star-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1"
                                        style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 784.11 815.53" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs></defs>
                                        <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer"></metadata>
                                            <path class="fil0"
                                                d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="star-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1"
                                        style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 784.11 815.53" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs></defs>
                                        <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer"></metadata>
                                            <path class="fil0"
                                                d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="star-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1"
                                        style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 784.11 815.53" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs></defs>
                                        <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer"></metadata>
                                            <path class="fil0"
                                                d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="star-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1"
                                        style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 784.11 815.53" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs></defs>
                                        <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer"></metadata>
                                            <path class="fil0"
                                                d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                            </button>
                            <!--Todo-------------------------- End Button Feedback ---------------------------------->
                            </form>
                        <?php } ?>
                        <!--! ----------------------------- End Add Feedback ----------------------------------->
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="container__right w-100">
                        <?php foreach ($run_select_feedbacks as $rows) { ?>
                        <div class="card w-100">
                            <!-- <img src="../images/Home/Feedback/Man.jpg" alt="user" /> -->
                                <div class="card__content w-100">
                                    <span><i class="ri-double-quotes-l"></i></span>
                                    <div class="card__details w-100">
                                        <p>
                                            <?php echo $rows['message'] ?>
                                        </p>
                                        <h4><?php echo $rows['name'] ?></h4>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
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
                                <a href="index.php"><img src="../images/<?php echo $web_logo ?>" alt="logo"
                                        width="100%"></a>
                            </div>
                        </div>
                        <div class="col-lg-4 px-4 footerCenter d-flex flex-column align-items-center justify-content-center">
                            <div class="footer-widget">
                                <div class="footer-widget-heading">
                                    <h5>Working Hours</h5>
                                </div>
                                <ul class="list-unstyled open-hours list-style-none">
                                    <li class="d-flex gap-4"><span>Mon-Sat</span>
                                        <span><?php echo $web_wh ?></span>
                                    </li>
                                    <li class="d-flex gap-5"><span>Sun</span><span class="ps-4">
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
    <script src="./js/script.js"></script>
</body>

</html>