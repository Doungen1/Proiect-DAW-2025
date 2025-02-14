<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('admin/inc/essentials.php');
require('admin/inc/db_config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = filter_var($_POST['room_id'], FILTER_SANITIZE_NUMBER_INT);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $user_id = $_SESSION['user_id'];
    $booking_date = date('Y-m-d');

    if (strtotime($start_date) < strtotime($end_date)) {
        $stmt = $con->prepare("SELECT COUNT(*) FROM bookings WHERE room_id = ? AND 
                            (start_date BETWEEN ? AND ? 
                            OR end_date BETWEEN ? AND ? 
                            OR (? BETWEEN start_date AND end_date) 
                            OR (? BETWEEN start_date AND end_date))");
        $stmt->bind_param("isssss", $room_id, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $stmt = $con->prepare("INSERT INTO bookings (user_id, room_id, start_date, end_date, booking_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $user_id, $room_id, $start_date, $end_date, $booking_date);
            if ($stmt->execute()) {
                $_SESSION['booking_message'] = "Booking successful!";
                header("Location: booking_confirmation.php");
                exit();
            } else {
                echo "Booking failed. Please try again.";
            }
            $stmt->close();
        } else {
            echo "This room is already booked for the selected period.";
        }
    } else {
        echo "End date must be after start date.";
    }
}
?>
