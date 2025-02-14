<?php
require('../inc/essentials.php');
require('../inc/db_config.php');
adminLogin();

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $con->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Booking canceled successfully!";
    } else {
        $_SESSION['message'] = "Failed to cancel the booking!";
    }

    $stmt->close();
    header("Location: bookings.php");
    exit;
} else {
    header("Location: bookings.php");
    exit;
}
?>
