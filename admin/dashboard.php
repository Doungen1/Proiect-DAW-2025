<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
session_regenerate_id(true);

// Statistici utilizatori
$new_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS new_users FROM user_cred WHERE DATE(datentime) = CURDATE()"))['new_users'];
$total_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total_users FROM user_cred"))['total_users'];
$verified_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS verified_users FROM user_cred WHERE is_verified = 1"))['verified_users'];
$active_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS active_users FROM user_cred WHERE status = 1"))['active_users'];

// Statistici rezervări
$total_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total_bookings FROM booking_order"))['total_bookings'];
$confirmed_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS confirmed FROM booking_order WHERE status = 'confirmed'"))['confirmed'];
$pending_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS pending FROM booking_order WHERE status = 'pending'"))['pending'];
$cancelled_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS cancelled FROM booking_order WHERE status = 'cancelled'"))['cancelled'];

// Venitul total din rezervări confirmate
$total_revenue = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(payment) AS revenue FROM booking_order WHERE status = 'confirmed'"))['revenue'];

// Venit în ultima lună
$last_month_revenue = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(payment) AS revenue FROM booking_order WHERE status = 'confirmed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"))['revenue'];

$total_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total_queries FROM user_queries"))['total_queries'];
$unseen_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS unseen_queries FROM user_queries WHERE seen = 0"))['unseen_queries'];
?>


<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Statistici</title>
    <?php require('inc/links.php'); ?>
    <style>
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="text-center fw-bold mb-4">Statistici Utilizatori</h3>

            <div class="row text-center">
                <!-- Statistici Utilizatori -->
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Utilizatori Noi Azi</h5>
                        <h2 class="text-success"><?php echo $new_users; ?></h2>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Utilizatori Totali</h5>
                        <h2 class="text-primary"><?php echo $total_users; ?></h2>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Utilizatori Verificați</h5>
                        <h2 class="text-info"><?php echo $verified_users; ?></h2>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Utilizatori Activi</h5>
                        <h2 class="text-warning"><?php echo $active_users; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Statistici Rezervări -->
            <h3 class="text-center fw-bold my-4">Statistici Rezervări</h3>
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Rezervări Totale</h5>
                        <h2 class="text-primary"><?php echo $total_bookings; ?></h2>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Confirmate</h5>
                        <h2 class="text-success"><?php echo $confirmed_bookings; ?></h2>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">În Așteptare</h5>
                        <h2 class="text-warning"><?php echo $pending_bookings; ?></h2>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Anulate</h5>
                        <h2 class="text-danger"><?php echo $cancelled_bookings; ?></h2>
                    </div>
                </div>
            </div>



            <!-- Total Queries -->

            <div class="row text-center mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Total Cereri Utilizatori</h5>
                        <h2 class="text-primary"><?php echo $total_queries; ?></h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <h5 class="fw-bold">Cereri Noi
                        </h5>
                        <h2 class="text-danger"><?php echo $unseen_queries; ?></h2>
                    </div>
                </div>
            </div>

                        <!-- Venit Total -->
                        <div class="text-center mt-4">
                <div class="card border-0 shadow-sm">
                    <h5 class="fw-bold">Venit Total din Rezervări Confirmate</h5>
                    <h2 class="text-success">$<?php echo number_format($total_revenue, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('inc/scripts.php'); ?>
</body>
</html>
