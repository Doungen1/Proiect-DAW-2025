<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set('Europe/Bucharest');

// Afișează erorile și avertismentele pentru debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['check_availability'])) {
    if (!isset($_POST['check_in']) || !isset($_POST['check_out'])) {
        echo json_encode(["status" => "error", "message" => "Check-in or check-out dates not provided"]);
        exit;
    }

    $frm_data = filteration($_POST);

    try {
        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($frm_data['check_in']);
        $checkout_date = new DateTime($frm_data['check_out']);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Invalid date format"]);
        exit;
    }

    if ($checkin_date >= $checkout_date) {
        if ($checkin_date == $checkout_date) {
            $status = 'check_in_out_equal';
        } else {
            $status = 'check_out_earlier';
        }
        $result = json_encode(["status" => $status]);
    } elseif ($checkin_date < $today_date) {
        $status = 'check_in_earlier';
        $result = json_encode(["status" => $status]);
    } else {
        session_start();
        if (!isset($_SESSION['room']) || !isset($_SESSION['room']['price'])) {
            echo json_encode(["status" => "error", "message" => "Room information or price not available"]);
            exit;
        }

        $count_days = $checkout_date->diff($checkin_date)->days;
        if ($count_days <= 0) {
            $result = json_encode(["status" => "error", "message" => "Invalid number of days"]);
        } else {
            $payment = $_SESSION['room']['price'] * $count_days;
            $_SESSION['room']['payment'] = $payment;
            $_SESSION['room']['available'] = true;

            $result = json_encode(["status" => 'available', "days" => $count_days, "payment" => $payment]);
        }
    }

    echo $result;
} elseif (isset($_POST['pay_now'])) {
    session_start();

    if (!isset($_SESSION['uID']) || !isset($_SESSION['room'])) {
        echo json_encode(["status" => "error", "message" => "Session data is missing"]);
        exit();
    }

    $user_id = $_SESSION['uID'];
    $room_id = $_SESSION['room']['id'];
    $checkin_date = $_POST['checkin'];
    $checkout_date = $_POST['checkout'];
    $payment = $_SESSION['room']['payment'];

    $query = "INSERT INTO booking_order (user_id, room_id, checkin_date, checkout_date, payment, status) VALUES (?, ?, ?, ?, ?, 'confirmed')";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement"]);
        exit();
    }

    $stmt->bind_param("iissd", $user_id, $room_id, $checkin_date, $checkout_date, $payment);
    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to execute statement"]);
        exit();
    }

    $order_id = $stmt->insert_id;
    $name = $_POST['name'];
    $phone = $_POST['phonenum'];
    $address = $_POST['address'];

    $query = "INSERT INTO booking_details (order_id, name, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare details statement"]);
        exit();
    }

    $stmt->bind_param("isss", $order_id, $name, $phone, $address);
    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to execute details statement"]);
        exit();
    }

    echo json_encode(["status" => "success", "message" => "Booking confirmed successfully."]);
}
?>
