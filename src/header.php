<?php
// Check if a session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>HijackGuard | Real-Time Session Protection</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="../src/assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../src/assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="../src/assets/img/favicons/favicon.ico">
    <link rel="manifest" href="../src/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="../src/assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="../src/assets/css/theme.css" rel="stylesheet" />
    <link href="../src/assets/custom.css" rel="stylesheet" />

    <link href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet" />

</head>

<body>

<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">

    <!-- ============================================-->
    <!-- <section> begin ============================-->
    <section class="bg-primary py-2 d-none d-sm-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto d-none d-lg-block">
                    <p class="my-2 fs--1"><i class="fas fa-map-marker-alt me-3"></i><span>الجامعة التقنيه </span></p>
                </div>
                <div class="col-auto ms-md-auto order-md-2 d-none d-sm-block">
                    <ul class="list-unstyled list-inline my-2">
                        <li class="list-inline-item"><a class="text-decoration-none" href="#!"><i class="fab fa-facebook-f text-900"></i></a></li>
                        <li class="list-inline-item"><a class="text-decoration-none" href="#!"><i class="fab fa-pinterest text-900"></i></a></li>
                        <li class="list-inline-item"><a class="text-decoration-none" href="#!"><i class="fab fa-twitter text-900"></i></a></li>
                        <li class="list-inline-item"><a class="text-decoration-none" href="#!"><i class="fab fa-instagram text-900"> </i></a></li>
                    </ul>
                </div>
                <div class="col-auto">
                    <p class="my-2 fs--1"><i class="fas fa-envelope me-3"></i><a class="text-900" href="mailto:support@hijackguard.com">support@hijackguard.com</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- <section> close ============================-->

    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block" style="background-color: #24364c !important;" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="../src/assets/img/logo.png" height="70px" alt="logo" /> <strong>HijackGuard</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> </span></button>
            <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                    <li class="nav-item px-2"><a class="nav-link active" href="../index.php">Home</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="../about_us.php">About US</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="../contact_us.php">Contact Us</a></li>
                </ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Show dashboard and logout if the user is logged in -->
                    <a style="margin-right: 12px;" class="btn btn-primary order-1 order-lg-0" href="../dashboard.php">Dashboard</a>
                    <a class="btn btn-danger order-1 order-lg-0 mr-2" href="../logout.php">Logout</a>
                <?php else: ?>
                    <!-- Show sign in and sign up if the user is not logged in -->
                    <a style="margin-right: 12px;" class="btn btn-primary order-1 order-lg-0" href="../login.php">Sign In</a>
                    <a class="btn btn-primary order-1 order-lg-0 mr-2" href="../register.php">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
