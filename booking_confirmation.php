<?php
session_start();
if (!isset($_SESSION['booking_message'])) {
    header("Location: rooms.php");
    exit();
}

$message = $_SESSION['booking_message'];
unset($_SESSION['booking_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>
<body>
    <h1><?php echo $message; ?></h1>
    <a href="rooms.php">Back to Rooms</a>
</body>
</html>
